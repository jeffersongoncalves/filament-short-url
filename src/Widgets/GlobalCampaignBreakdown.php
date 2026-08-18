<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Widgets\Concerns\GlobalRankedListWidget;

class GlobalCampaignBreakdown extends GlobalRankedListWidget
{
    protected function heading(): string
    {
        return __('filament-short-url::resources/short-url.dashboard.by_campaign');
    }

    protected function statsKey(): string
    {
        return 'utmCampaignStats';
    }
}
