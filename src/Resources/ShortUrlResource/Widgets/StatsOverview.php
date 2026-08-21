<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\HasStatsPayload;

class StatsOverview extends BaseWidget
{
    use HasStatsPayload;

    protected function getStats(): array
    {
        $payload = $this->getPayload();

        $stats = [
            Stat::make(__('filament-short-url::resources/short-url.stats.total_visits'), $payload->totalVisits),
            Stat::make(__('filament-short-url::resources/short-url.stats.unique_visits'), $payload->uniqueVisits),
        ];

        $stats[] = Stat::make(__('filament-short-url::resources/short-url.stats.bot_visits'), $payload->botVisits);

        return $stats;
    }
}
