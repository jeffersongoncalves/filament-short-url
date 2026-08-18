<?php

namespace JeffersonGoncalves\Filament\ShortUrl;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
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

    public function packageBooted(): void
    {
        FilamentAsset::register(
            [
                Css::make('filament-short-url-styles', __DIR__.'/../resources/dist/filament-short-url.css'),
            ],
            'jeffersongoncalves/filament-short-url'
        );
    }
}
