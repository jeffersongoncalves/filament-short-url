<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns;

use Illuminate\Support\Carbon;
use JeffersonGoncalves\Filament\ShortUrl\Support\StatsPayloadMemo;
use JeffersonGoncalves\LaravelShortUrl\Contracts\StatsAggregator;
use JeffersonGoncalves\LaravelShortUrl\Data\StatsPayload;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

trait HasStatsPayload
{
    public ?ShortUrl $record = null;

    public ?string $from = null;

    public ?string $to = null;

    protected function getPayload(): StatsPayload
    {
        $key = 'short-url:'.($this->record?->getKey() ?? 'null').':'.$this->from.':'.$this->to;

        return StatsPayloadMemo::remember($key, fn () => app(StatsAggregator::class)
            ->for($this->record)
            ->between(
                $this->from ? Carbon::parse($this->from)->startOfDay() : now()->subDays(30)->startOfDay(),
                $this->to ? Carbon::parse($this->to)->endOfDay() : now()->endOfDay(),
            )
            ->get());
    }
}
