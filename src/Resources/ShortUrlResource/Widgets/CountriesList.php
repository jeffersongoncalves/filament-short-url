<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\RankedListWidget;

class CountriesList extends RankedListWidget
{
    protected function heading(): string
    {
        return __('filament-short-url::resources/short-url.stats.countries');
    }

    protected function statsKey(): string
    {
        return 'countryStats';
    }

    protected function isCountryCode(): bool
    {
        return true;
    }
}
