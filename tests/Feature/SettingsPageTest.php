<?php

use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Pages\SettingsPage;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Contracts\SettingsRepository;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the settings page with tabs built from the core schema', function () {
    livewire(SettingsPage::class)->assertSuccessful();
});

it('persists a changed setting via the core SettingsRepository', function () {
    livewire(SettingsPage::class)
        ->set('data.redirect.default_status_code', 301)
        ->call('save');

    expect(app(SettingsRepository::class)->get('redirect.default_status_code'))->toBe(301);
});

it('denies access to settings when the plugin closure returns false', function () {
    FilamentShortUrlPlugin::get()->authorizeSettingsUsing(fn (): bool => false);

    expect(SettingsPage::canAccess())->toBeFalse();

    FilamentShortUrlPlugin::get()->authorizeSettingsUsing(null);
});
