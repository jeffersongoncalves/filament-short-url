<?php

namespace JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class ShortUrlsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('url_key')
                    ->label(__('filament-short-url::resources/short-url.fields.url_key'))
                    ->copyable()
                    ->searchable(),

                TextColumn::make('destination_url')
                    ->label(__('filament-short-url::resources/short-url.fields.destination_url'))
                    ->limit(40)
                    ->tooltip(fn (ShortUrl $record): string => $record->destination_url)
                    ->searchable(),

                TextColumn::make('title')
                    ->label(__('filament-short-url::resources/short-url.fields.title'))
                    ->searchable(),

                ToggleColumn::make('is_enabled')
                    ->label(__('filament-short-url::resources/short-url.fields.is_enabled')),

                TextColumn::make('total_visits')
                    ->label(__('filament-short-url::resources/short-url.fields.total_visits'))
                    ->badge(),

                TextColumn::make('expires_at')
                    ->label(__('filament-short-url::resources/short-url.fields.expires_at'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament-short-url::resources/short-url.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
