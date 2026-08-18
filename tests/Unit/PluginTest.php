<?php

use Filament\Contracts\Plugin;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;

it('has the correct id', function () {
    expect(FilamentShortUrlPlugin::make()->getId())->toBe('filament-short-url');
});

it('implements the Filament Plugin contract', function () {
    expect(FilamentShortUrlPlugin::make())->toBeInstanceOf(Plugin::class);
});

it('make() returns an instance', function () {
    expect(FilamentShortUrlPlugin::make())->toBeInstanceOf(FilamentShortUrlPlugin::class);
});

it('exposes fluent configuration methods', function () {
    $plugin = FilamentShortUrlPlugin::make();
    $authorizeUsing = fn (): bool => true;
    $authorizeSettingsUsing = fn (): bool => false;

    expect($plugin->navigationGroup('Marketing'))->toBe($plugin)
        ->and($plugin->navigationLabel('Links Curtos'))->toBe($plugin)
        ->and($plugin->navigationIcon('heroicon-o-link'))->toBe($plugin)
        ->and($plugin->navigationSort(50))->toBe($plugin)
        ->and($plugin->resources([]))->toBe($plugin)
        ->and($plugin->hideStatistics())->toBe($plugin)
        ->and($plugin->isStatisticsHidden())->toBeTrue()
        ->and($plugin->hideBioPages())->toBe($plugin)
        ->and($plugin->isBioPagesHidden())->toBeTrue()
        ->and($plugin->authorizeUsing($authorizeUsing))->toBe($plugin)
        ->and($plugin->getAuthorizeUsing())->toBe($authorizeUsing)
        ->and($plugin->authorizeSettingsUsing($authorizeSettingsUsing))->toBe($plugin)
        ->and($plugin->getAuthorizeSettingsUsing())->toBe($authorizeSettingsUsing);
});
