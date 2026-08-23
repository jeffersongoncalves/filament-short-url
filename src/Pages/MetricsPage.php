<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Pages;

use Filament\Pages\Page;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\ExpiringLinks;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\GlobalCampaignBreakdown;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\GlobalCityBreakdown;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\GlobalCountryBreakdown;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\GlobalMediumBreakdown;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\GlobalOverview;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\GlobalSourceBreakdown;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\TopLinks;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\UsageOverview;

/**
 * A dedicated home for every global (cross-link) widget, kept off the
 * host panel's own Dashboard so it doesn't compete with the host's other
 * widgets — everything here renders core-computed StatsPayload/counter
 * data, nothing calculated in the plugin.
 */
class MetricsPage extends Page
{
    use HasPluginNavigationGroup;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament-short-url::pages.metrics';

    // Filament's own default for an unsorted nav item is -1 (see
    // NavigationItem::getSort()), so -1 here would just tie with every
    // other unsorted resource/page and fall back to registration order.
    protected static ?int $navigationSort = -100;

    public function getTitle(): string
    {
        return __('filament-short-url::resources/short-url.metrics.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-short-url::resources/short-url.metrics.title');
    }

    public static function canAccess(): bool
    {
        return ! FilamentShortUrlPlugin::get()->isStatisticsHidden();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GlobalOverview::class,
            UsageOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            TopLinks::class,
            ExpiringLinks::class,
            GlobalMediumBreakdown::class,
            GlobalSourceBreakdown::class,
            GlobalCampaignBreakdown::class,
            GlobalCountryBreakdown::class,
            GlobalCityBreakdown::class,
        ];
    }
}
