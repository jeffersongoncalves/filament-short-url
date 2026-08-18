<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\RankedListWidget;

class CitiesList extends RankedListWidget
{
    protected function heading(): string
    {
        return __('filament-short-url::resources/short-url.stats.cities');
    }

    protected function statsKey(): string
    {
        return 'cityStats';
    }
}
