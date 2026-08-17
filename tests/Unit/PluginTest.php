<?php

use Filament\Contracts\Plugin;
use JeffersonGoncalves\FilamentShortUrl\FilamentShortUrlPlugin;

it('has the correct id', function () {
    expect(FilamentShortUrlPlugin::make()->getId())->toBe('filament-short-url');
});

it('implements the Filament Plugin contract', function () {
    expect(FilamentShortUrlPlugin::make())->toBeInstanceOf(Plugin::class);
});

it('make() returns an instance', function () {
    expect(FilamentShortUrlPlugin::make())->toBeInstanceOf(FilamentShortUrlPlugin::class);
});
