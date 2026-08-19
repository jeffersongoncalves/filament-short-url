<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns;

use Filament\Widgets\ChartWidget;

abstract class PieStatWidget extends ChartWidget
{
    use HasStatsPayload;

    protected static ?string $heading = null;

    protected string $chartType = 'doughnut';

    protected function getType(): string
    {
        return $this->chartType;
    }

    abstract protected function statsKey(): string;

    public function getHeading(): ?string
    {
        return static::$heading;
    }

    protected function getData(): array
    {
        $stats = $this->getPayload()->{$this->statsKey()};

        arsort($stats);
        $stats = array_slice($stats, 0, 8, true);

        return [
            'datasets' => [
                [
                    'data' => array_values($stats),
                    'backgroundColor' => [
                        '#6366f1', '#22c55e', '#f59e0b', '#ef4444',
                        '#06b6d4', '#a855f7', '#ec4899', '#84cc16',
                    ],
                ],
            ],
            'labels' => array_keys($stats),
        ];
    }
}
