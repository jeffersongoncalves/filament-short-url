<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class ExpiringLinks extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('filament-short-url::resources/short-url.dashboard.expiring_links'))
            ->query(
                ShortUrl::query()
                    ->whereNotNull('expires_at')
                    ->whereBetween('expires_at', [now(), now()->addDays(7)])
                    ->orderBy('expires_at'),
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('url_key')
                    ->label(__('filament-short-url::resources/short-url.fields.url_key')),
                TextColumn::make('destination_url')
                    ->label(__('filament-short-url::resources/short-url.fields.destination_url'))
                    ->limit(50),
                TextColumn::make('expires_at')
                    ->label(__('filament-short-url::resources/short-url.fields.expires_at'))
                    ->dateTime(),
            ]);
    }
}
