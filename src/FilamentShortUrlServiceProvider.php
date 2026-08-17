<?php

namespace JeffersonGoncalves\FilamentShortUrl;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentShortUrlServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-short-url';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }
}
