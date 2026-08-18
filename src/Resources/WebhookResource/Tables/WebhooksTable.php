<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\LaravelShortUrl\Jobs\SendWebhookJob;
use JeffersonGoncalves\LaravelShortUrl\Models\Webhook;

class WebhooksTable
{
    public static function configure(Table $table): Table
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
            ->recordActions([
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
}
