<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class GlobalOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $stats = [
            Stat::make(
                __('filament-short-url::resources/short-url.dashboard.total_links'),
                ShortUrl::query()->count(),
            ),
            Stat::make(
                __('filament-short-url::resources/short-url.dashboard.total_visits'),
                ShortUrl::query()->sum('total_visits'),
            ),
            Stat::make(
                __('filament-short-url::resources/short-url.dashboard.total_unique_visits'),
                ShortUrl::query()->sum('unique_visits'),
            ),
        ];

        return $stats;
    }
}
