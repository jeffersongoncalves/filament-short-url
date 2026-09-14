<?php

namespace JeffersonGoncalves\Filament\ShortUrl;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentShortUrlServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-short-url';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            [
                Css::make('filament-short-url-styles', __DIR__.'/../resources/dist/filament-short-url.css'),
            ],
            'jeffersongoncalves/filament-short-url'
        );

        // Only one domain may be default per tenant. Enforced here (not just in
        // the panel form) so it also holds for programmatic creation/updates —
        // a mass update() on the query builder, so it doesn't re-trigger this
        // same "saved" event.
        CustomDomain::saved(function (CustomDomain $domain): void {
            if (! $domain->is_default) {
                return;
            }

            CustomDomain::query()
                ->whereKeyNot($domain->getKey())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        });
    }
}
