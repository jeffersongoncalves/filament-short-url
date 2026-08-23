<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Widgets\Concerns\GlobalRankedListWidget;

class GlobalCountryBreakdown extends GlobalRankedListWidget
{
    protected function heading(): string
    {
        return __('filament-short-url::resources/short-url.dashboard.by_country');
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
