<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\CreateApiKey;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\EditApiKey;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\ListApiKeys;
use JeffersonGoncalves\LaravelShortUrl\Models\ApiKey;

class ApiKeyResource extends Resource
{
    use HasPluginNavigationGroup;

    /**
     * @var array<string, string>
     */
    public const ABILITIES = [
        '*' => 'Full access',
        'links:read' => 'links:read',
        'links:write' => 'links:write',
        'conversions:write' => 'conversions:write',
    ];

    protected static ?string $model = ApiKey::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label(__('filament-short-url::resources/api-key.fields.name'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            CheckboxList::make('abilities')
                ->label(__('filament-short-url::resources/api-key.fields.abilities'))
                ->options(static::ABILITIES)
                ->required()
                ->columnSpanFull(),

            DateTimePicker::make('expires_at')
                ->label(__('filament-short-url::resources/api-key.fields.expires_at'))
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-short-url::resources/api-key.fields.name'))
                    ->searchable(),

                TextColumn::make('key_prefix')
                    ->label(__('filament-short-url::resources/api-key.fields.prefix'))
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => "{$state}…"),

                TextColumn::make('abilities')
                    ->label(__('filament-short-url::resources/api-key.fields.abilities'))
                    ->badge()
                    ->separator(','),

                TextColumn::make('status')
                    ->label(__('filament-short-url::resources/api-key.fields.status'))
                    ->state(fn (ApiKey $record): string => match (true) {
                        $record->revoked_at !== null => __('filament-short-url::resources/api-key.status.revoked'),
                        $record->expires_at?->isPast() => __('filament-short-url::resources/api-key.status.expired'),
                        default => __('filament-short-url::resources/api-key.status.active'),
                    })
                    ->badge()
                    ->color(fn (ApiKey $record): string => match (true) {
                        $record->revoked_at !== null, $record->expires_at?->isPast() => 'danger',
                        default => 'success',
                    }),

                TextColumn::make('last_used_at')
                    ->label(__('filament-short-url::resources/api-key.fields.last_used_at'))
                    ->dateTime()
                    ->since(),

                TextColumn::make('expires_at')
                    ->label(__('filament-short-url::resources/api-key.fields.expires_at'))
                    ->dateTime(),
            ])
            ->actions([
                Action::make('revoke')
                    ->label(__('filament-short-url::resources/api-key.actions.revoke'))
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ApiKey $record): bool => $record->revoked_at === null)
                    ->action(fn (ApiKey $record) => $record->update(['revoked_at' => now()])),

                Action::make('rotate')
                    ->label(__('filament-short-url::resources/api-key.actions.rotate'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (ApiKey $record): void {
                        $record->update(['revoked_at' => now()]);

                        $generated = ApiKey::generate($record->name, $record->abilities, $record->expires_at);

                        Notification::make()
                            ->title(__('filament-short-url::resources/api-key.actions.rotated_title'))
                            ->body($generated['token'])
                            ->persistent()
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function canViewAny(): bool
    {
        $callback = FilamentShortUrlPlugin::get()->getAuthorizeUsing();

        if ($callback !== null) {
            return (bool) $callback();
        }

        return parent::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApiKeys::route('/'),
            'create' => CreateApiKey::route('/create'),
            'edit' => EditApiKey::route('/{record}/edit'),
        ];
    }
}
