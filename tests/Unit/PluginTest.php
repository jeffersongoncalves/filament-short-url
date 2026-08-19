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
        ->and($plugin->getAuthorizeSettingsUsing())->toBe($authorizeSettingsUsing)
        ->and($plugin->hideUtm())->toBe($plugin)
        ->and($plugin->isUtmHidden())->toBeTrue()
        ->and($plugin->hidePixels())->toBe($plugin)
        ->and($plugin->isPixelsHidden())->toBeTrue()
        ->and($plugin->hideTargeting())->toBe($plugin)
        ->and($plugin->isTargetingHidden())->toBeTrue()
        ->and($plugin->hideWebhooks())->toBe($plugin)
        ->and($plugin->isWebhooksHidden())->toBeTrue()
        ->and($plugin->hideFolders())->toBe($plugin)
        ->and($plugin->isFoldersHidden())->toBeTrue()
        ->and($plugin->hideTags())->toBe($plugin)
        ->and($plugin->isTagsHidden())->toBeTrue();
});

it('simpleMode() hides every optional section and the webhooks resource at once', function () {
    $plugin = FilamentShortUrlPlugin::make();

    expect($plugin->simpleMode())->toBe($plugin)
        ->and($plugin->isQrDesignerHidden())->toBeTrue()
        ->and($plugin->isDeepLinkingHidden())->toBeTrue()
        ->and($plugin->isSecurityHidden())->toBeTrue()
        ->and($plugin->isUtmHidden())->toBeTrue()
        ->and($plugin->isPixelsHidden())->toBeTrue()
        ->and($plugin->isTargetingHidden())->toBeTrue()
        ->and($plugin->isWebhooksHidden())->toBeTrue();

    $plugin->simpleMode(false);

    expect($plugin->isQrDesignerHidden())->toBeFalse()
        ->and($plugin->isWebhooksHidden())->toBeFalse();
});
