<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\CreateCustomDomain;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\EditCustomDomain;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\ListCustomDomains;
use JeffersonGoncalves\LaravelShortUrl\Jobs\VerifyDomainJob;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;

class CustomDomainResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = CustomDomain::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('domain')
                ->label(__('filament-short-url::resources/custom-domain.fields.domain'))
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->columnSpanFull(),

            Toggle::make('is_wildcard')
                ->label(__('filament-short-url::resources/custom-domain.fields.is_wildcard'))
                ->helperText(__('filament-short-url::resources/custom-domain.fields.is_wildcard_helper')),

            TextInput::make('root_redirect_url')
                ->label(__('filament-short-url::resources/custom-domain.fields.root_redirect_url'))
                ->url()
                ->nullable()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('domain')
                    ->label(__('filament-short-url::resources/custom-domain.fields.domain'))
                    ->searchable()
                    ->copyable(),

                IconColumn::make('is_verified')
                    ->label(__('filament-short-url::resources/custom-domain.fields.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor(fn (CustomDomain $record): string => $record->disabled_at ? 'danger' : 'warning'),

                TextColumn::make('dns_record_type')
                    ->label(__('filament-short-url::resources/custom-domain.fields.dns_record_type'))
                    ->badge(),

                IconColumn::make('is_wildcard')
                    ->label(__('filament-short-url::resources/custom-domain.fields.is_wildcard'))
                    ->boolean(),

                TextColumn::make('last_checked_at')
                    ->label(__('filament-short-url::resources/custom-domain.fields.last_checked_at'))
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('dns_instructions')
                    ->label(__('filament-short-url::resources/custom-domain.actions.dns_instructions'))
                    ->icon('heroicon-o-information-circle')
                    ->color('gray')
                    ->modalHeading(__('filament-short-url::resources/custom-domain.actions.dns_instructions'))
                    ->modalContent(fn (CustomDomain $record) => view('filament-short-url::components.dns-instructions', [
                        'domain' => $record,
                        'txtHost' => '_short-url-verify.'.$record->domain,
                        'txtValue' => $record->verification_token,
                        'cnameTarget' => config('short-url.route.domain') ?? parse_url((string) config('app.url'), PHP_URL_HOST),
                        'registrars' => [
                            'cloudflare' => 'Cloudflare',
                            'godaddy' => 'GoDaddy',
                            'registrobr' => 'Registro.br',
                            'namecheap' => 'Namecheap',
                            'hostinger' => 'Hostinger',
                        ],
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('filament-short-url::resources/custom-domain.actions.close')),

                Action::make('verify')
                    ->label(__('filament-short-url::resources/custom-domain.actions.verify_now'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(function (CustomDomain $record): void {
                        VerifyDomainJob::dispatch($record->id);

                        Notification::make()
                            ->title(__('filament-short-url::resources/custom-domain.actions.verify_queued'))
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
            'index' => ListCustomDomains::route('/'),
            'create' => CreateCustomDomain::route('/create'),
            'edit' => EditCustomDomain::route('/{record}/edit'),
        ];
    }
}
