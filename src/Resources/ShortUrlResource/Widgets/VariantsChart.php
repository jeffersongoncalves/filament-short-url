<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use Filament\Widgets\Widget;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\HasStatsPayload;

class VariantsChart extends Widget
{
    use HasStatsPayload;

    /**
     * @var view-string
     */
    protected string $view = 'filament-short-url::widgets.variants-chart';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $payload = $this->getPayload();

        $total = array_sum($payload->variantStats) ?: 1;

        $weights = collect($this->record->rotation_variants['variants'] ?? [])
            ->mapWithKeys(fn (array $variant): array => [($variant['label'] ?? $variant['url'] ?? '') => (int) ($variant['weight'] ?? 0)])
            ->all();

        return [
            'heading' => __('filament-short-url::resources/short-url.stats.variants'),
            'variants' => $payload->variantStats,
            'total' => $total,
            'weights' => $weights,
            'significance' => $payload->variantSignificance,
        ];
    }
}
