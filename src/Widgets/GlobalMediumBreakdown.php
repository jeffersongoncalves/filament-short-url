<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Widgets\Concerns\GlobalRankedListWidget;

class GlobalMediumBreakdown extends GlobalRankedListWidget
{
    protected function heading(): string
    {
        return __('filament-short-url::resources/short-url.dashboard.by_medium');
    }

    protected function statsKey(): string
    {
        return 'utmMediumStats';
    }
}
