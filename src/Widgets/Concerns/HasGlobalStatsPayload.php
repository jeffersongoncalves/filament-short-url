<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets\Concerns;

use Illuminate\Support\Carbon;
use JeffersonGoncalves\Filament\ShortUrl\Support\StatsPayloadMemo;
use JeffersonGoncalves\LaravelShortUrl\Contracts\StatsAggregator;
use JeffersonGoncalves\LaravelShortUrl\Data\StatsPayload;

/**
 * Aggregates across every short url site-wide, via the core's
 * StatsAggregator::forShortUrls(null) — the "no scope" path added in
 * laravel-short-url 4.4.5 (see issue #17) so this doesn't have to
 * enumerate every ShortUrl id into a whereIn() and risk exceeding PDO's
 * bound-parameter limit on a site with enough links.
 */
trait HasGlobalStatsPayload
{
    protected ?string $pollingInterval = null;

    protected function getGlobalPayload(): StatsPayload
    {
        $key = 'global:'.Carbon::now()->toDateString();

        return StatsPayloadMemo::remember($key, fn () => app(StatsAggregator::class)
            ->forShortUrls(null)
            ->between(
                Carbon::now()->subDays(30)->startOfDay(),
                Carbon::now()->endOfDay(),
            )
            ->get());
    }
}
