<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\PieStatWidget;

class ReferrerTypesChart extends PieStatWidget
{
    public function getHeading(): ?string
    {
        return __('filament-short-url::resources/short-url.stats.referrer_types');
    }

    protected function statsKey(): string
    {
        return 'refererTypeStats';
    }
}
