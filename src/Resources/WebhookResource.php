<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Filament\Forms\Components\CheckboxList;
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
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\CreateWebhook;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\EditWebhook;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\ListWebhooks;
use JeffersonGoncalves\LaravelShortUrl\Jobs\SendWebhookJob;
use JeffersonGoncalves\LaravelShortUrl\Models\Webhook;

class WebhookResource extends Resource
{
    use HasPluginNavigationGroup;

    /**
     * @var array<string, string>
     */
    public const EVENTS = [
        '*' => 'All events',
        'link.created' => 'link.created',
        'link.updated' => 'link.updated',
        'link.deleted' => 'link.deleted',
        'link.visited' => 'link.visited',
        'link.unsafe_detected' => 'link.unsafe_detected',
        'domain.verified' => 'domain.verified',
        'domain.failed' => 'domain.failed',
        'alert.triggered' => 'alert.triggered',
        'conversion.recorded' => 'conversion.recorded',
    ];

    protected static ?string $model = Webhook::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('url')
                ->label(__('filament-short-url::resources/webhook.fields.url'))
                ->required()
                ->url()
                ->columnSpanFull(),

            CheckboxList::make('events')
                ->label(__('filament-short-url::resources/webhook.fields.events'))
                ->options(static::EVENTS)
                ->required()
                ->columns(2)
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label(__('filament-short-url::resources/webhook.fields.is_active'))
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('url')
                    ->label(__('filament-short-url::resources/webhook.fields.url'))
                    ->searchable()
                    ->limit(50),

                TextColumn::make('events')
                    ->label(__('filament-short-url::resources/webhook.fields.events'))
                    ->badge()
                    ->separator(','),

                IconColumn::make('status')
                    ->label(__('filament-short-url::resources/webhook.fields.status'))
                    ->boolean()
                    ->state(fn (Webhook $record): bool => $record->is_active && $record->disabled_at === null)
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('failure_count')
                    ->label(__('filament-short-url::resources/webhook.fields.failure_count'))
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'warning' : 'gray'),
            ])
            ->actions([
                Action::make('test')
                    ->label(__('filament-short-url::resources/webhook.actions.test'))
                    ->icon('heroicon-o-paper-airplane')
                    ->color('gray')
                    ->action(function (Webhook $record): void {
                        SendWebhookJob::dispatch($record->id, 'test', [
                            'message' => 'This is a test webhook delivery.',
                        ]);

                        Notification::make()
                            ->title(__('filament-short-url::resources/webhook.actions.test_sent'))
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
            'index' => ListWebhooks::route('/'),
            'create' => CreateWebhook::route('/create'),
            'edit' => EditWebhook::route('/{record}/edit'),
        ];
    }
}
