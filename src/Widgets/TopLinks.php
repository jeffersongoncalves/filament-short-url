<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class TopLinks extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('filament-short-url::resources/short-url.dashboard.top_links'))
            ->query(ShortUrl::query()->orderByDesc('total_visits')->limit(10))
            ->paginated(false)
            ->columns([
                TextColumn::make('url_key')
                    ->label(__('filament-short-url::resources/short-url.fields.url_key'))
                    ->copyable()
                    ->copyableState(fn (ShortUrl $record): string => $record->fullUrl())
                    ->copyMessage(__('filament-short-url::resources/short-url.actions.copied')),
                TextColumn::make('destination_url')
                    ->label(__('filament-short-url::resources/short-url.fields.destination_url'))
                    ->limit(50),
                TextColumn::make('total_visits')
                    ->label(__('filament-short-url::resources/short-url.fields.total_visits'))
                    ->badge(),
            ]);
    }
}
