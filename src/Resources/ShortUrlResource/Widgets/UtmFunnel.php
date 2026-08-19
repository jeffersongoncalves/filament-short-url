<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use Filament\Widgets\Widget;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\HasStatsPayload;

class UtmFunnel extends Widget
{
    use HasStatsPayload;

    /**
     * @var view-string
     */
    protected static string $view = 'filament-short-url::widgets.utm-funnel';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $payload = $this->getPayload();

        $sorted = function (array $stats): array {
            arsort($stats);

            return array_slice($stats, 0, 5, true);
        };

        return [
            'heading' => __('filament-short-url::resources/short-url.stats.utm_funnel'),
            'source' => $sorted($payload->utmSourceStats),
            'medium' => $sorted($payload->utmMediumStats),
            'campaign' => $sorted($payload->utmCampaignStats),
        ];
    }
}
