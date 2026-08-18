<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\HasStatsPayload;

class HourlyChart extends ChartWidget
{
    use HasStatsPayload;

    public function getHeading(): ?string
    {
        return __('filament-short-url::resources/short-url.stats.hourly');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $hourly = $this->getPayload()->hourlyStats;

        $labels = range(0, 23);
        $data = array_map(fn (int $hour): int => $hourly[$hour] ?? 0, $labels);

        return [
            'datasets' => [
                [
                    'label' => __('filament-short-url::resources/short-url.stats.total_visits'),
                    'data' => $data,
                    'backgroundColor' => '#6366f1',
                ],
            ],
            'labels' => array_map(fn (int $hour): string => sprintf('%02d:00', $hour), $labels),
        ];
    }
}
