<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets\Concerns;

use Illuminate\Support\Carbon;
use JeffersonGoncalves\LaravelShortUrl\Contracts\StatsAggregator;
use JeffersonGoncalves\LaravelShortUrl\Data\StatsPayload;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

/**
 * Aggregates across every short url the current (tenant-scoped) query
 * returns, via the core's StatsAggregator::forShortUrls() — added in
 * laravel-short-url 1.2.0 specifically for cross-link dashboard breakdowns.
 * Link selection stays the caller's job (ShortUrl's own scoped query);
 * this only renders what the aggregator computes.
 */
trait HasGlobalStatsPayload
{
    protected function getGlobalPayload(): StatsPayload
    {
        $shortUrlIds = ShortUrl::query()->pluck('id')->all();

        return app(StatsAggregator::class)
            ->forShortUrls($shortUrlIds)
            ->between(
                Carbon::now()->subDays(30)->startOfDay(),
                Carbon::now()->endOfDay(),
            )
            ->get();
    }
}
