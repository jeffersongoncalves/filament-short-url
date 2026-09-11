<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Support;

use Closure;

/**
 * Request-scoped memoization shared across widget instances, so multiple
 * HasStatsPayload/HasGlobalStatsPayload widgets on the same page render
 * compute an identical StatsPayload only once.
 */
class StatsPayloadMemo
{
    protected static array $cache = [];

    public static function remember(string $key, Closure $callback): mixed
    {
        return static::$cache[$key] ??= $callback();
    }
}
