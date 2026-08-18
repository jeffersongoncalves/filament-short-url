<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\PieStatWidget;

class BrowsersChart extends PieStatWidget
{
    public function getHeading(): ?string
    {
        return __('filament-short-url::resources/short-url.stats.browsers');
    }

    protected function statsKey(): string
    {
        return 'browserStats';
    }
}
