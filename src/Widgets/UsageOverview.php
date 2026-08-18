<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\Tenancy\PlanLimits;

/**
 * Read-only usage-vs-limit for the two quotas the core's PlanLimits
 * actually enforces (links_per_month, domains). clicks_per_month and
 * members are declared as plan config keys but nothing in the core
 * counts them yet, so there's nothing honest to show for those here.
 */
class UsageOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return (bool) config('short-url.tenancy.enabled', false);
    }

    protected function getStats(): array
    {
        $limits = app(PlanLimits::class);

        $linksLimit = $limits->limit('links_per_month');
        $linksUsed = ShortUrl::query()->where('created_at', '>=', now()->startOfMonth())->count();

        $domainsLimit = $limits->limit('domains');
        $domainsUsed = CustomDomain::query()->count();

        return [
            Stat::make(
                __('filament-short-url::resources/short-url.usage.links_this_month'),
                $linksLimit === null
                    ? (string) $linksUsed
                    : "{$linksUsed} / {$linksLimit}",
            ),
            Stat::make(
                __('filament-short-url::resources/short-url.usage.domains'),
                $domainsLimit === null
                    ? (string) $domainsUsed
                    : "{$domainsUsed} / {$domainsLimit}",
            ),
            Stat::make(
                __('filament-short-url::resources/short-url.usage.plan'),
                ucfirst($limits->currentPlan()),
            ),
        ];
    }
}
