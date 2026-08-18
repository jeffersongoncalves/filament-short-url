<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\LaravelShortUrl\Models\ApiKey;

class ApiKeysTable
{
    public static function configure(Table $table): Table
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
            ->recordActions([
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
}
