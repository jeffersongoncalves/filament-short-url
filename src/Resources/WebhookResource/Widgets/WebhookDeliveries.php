<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Widgets;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use JeffersonGoncalves\LaravelShortUrl\Jobs\SendWebhookJob;
use JeffersonGoncalves\LaravelShortUrl\Models\Webhook;
use JeffersonGoncalves\LaravelShortUrl\Models\WebhookDelivery;

class WebhookDeliveries extends TableWidget
{
    public ?Webhook $record = null;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('filament-short-url::resources/webhook.deliveries.heading'))
            ->query(WebhookDelivery::query()->where('webhook_id', $this->record?->id)->latest('id'))
            ->columns([
                TextColumn::make('event')
                    ->label(__('filament-short-url::resources/webhook.deliveries.event'))
                    ->badge(),

                IconColumn::make('succeeded')
                    ->label(__('filament-short-url::resources/webhook.deliveries.succeeded'))
                    ->boolean(),

                TextColumn::make('response_status')
                    ->label(__('filament-short-url::resources/webhook.deliveries.response_status'))
                    ->badge(),

                TextColumn::make('attempt')
                    ->label(__('filament-short-url::resources/webhook.deliveries.attempt')),

                TextColumn::make('created_at')
                    ->label(__('filament-short-url::resources/webhook.deliveries.created_at'))
                    ->dateTime()
                    ->since(),
            ])
            ->actions([
                Action::make('view_payload')
                    ->label(__('filament-short-url::resources/webhook.deliveries.view_payload'))
                    ->icon('heroicon-o-code-bracket')
                    ->color('gray')
                    ->modalHeading(__('filament-short-url::resources/webhook.deliveries.view_payload'))
                    ->modalContent(fn (WebhookDelivery $record) => view('filament-short-url::components.payload-preview', [
                        'payload' => $record->payload,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('filament-short-url::resources/custom-domain.actions.close')),

                Action::make('replay')
                    ->label(__('filament-short-url::resources/webhook.deliveries.replay'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(function (WebhookDelivery $record): void {
                        SendWebhookJob::dispatch($record->webhook_id, $record->event, $record->payload);

                        Notification::make()
                            ->title(__('filament-short-url::resources/webhook.deliveries.replayed'))
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated([10, 25]);
    }
}
