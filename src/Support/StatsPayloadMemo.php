<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Support;

use Closure;

/**
 * Request-scoped memoization shared across widget instances, so multiple
 * HasStatsPayload/HasGlobalStatsPayload widgets on the same page render
 * compute an identical StatsPayload only once.
 *
 * Bound as `scoped` in the service provider (not a static property), so
 * Octane/queue workers flush it between requests instead of serving one
 * request's payload for the rest of the process lifetime.
 */
class StatsPayloadMemo
{
    protected array $cache = [];

    public function remember(string $key, Closure $callback): mixed
    {
        return $this->cache[$key] ??= $callback();
    }
}
