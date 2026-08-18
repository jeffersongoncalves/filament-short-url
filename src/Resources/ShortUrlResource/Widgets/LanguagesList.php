<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets;

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\Concerns\RankedListWidget;

class LanguagesList extends RankedListWidget
{
    protected function heading(): string
    {
        return __('filament-short-url::resources/short-url.stats.languages');
    }

    protected function statsKey(): string
    {
        return 'languageStats';
    }
}
