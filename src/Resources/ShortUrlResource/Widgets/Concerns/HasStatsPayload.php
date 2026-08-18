<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns;

use Illuminate\Support\Carbon;
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
        return app(StatsAggregator::class)
            ->for($this->record)
            ->between(
                $this->from ? Carbon::parse($this->from)->startOfDay() : now()->subDays(30)->startOfDay(),
                $this->to ? Carbon::parse($this->to)->endOfDay() : now()->endOfDay(),
            )
            ->get();
    }
}
