<?php

use Illuminate\Support\Facades\DB;
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

it('shows the config value when no settings row exists', function () {
    config()->set('short-url.redirect.default_status_code', 301);

    livewire(SettingsPage::class)
        ->assertSet('data.redirect.default_status_code', 301);
});

it('does not create rows when saving without changes', function () {
    livewire(SettingsPage::class)->call('save');

    $table = config('short-url.table_prefix', 'short_url_').'settings';

    expect(DB::table($table)->count())->toBe(0);
});

it('drops the stored row when a setting is set back to the config value', function () {
    app(SettingsRepository::class)->set('key.length', 12);

    livewire(SettingsPage::class)
        ->assertSet('data.key.length', 12)
        ->set('data.key.length', config('short-url.key.length'))
        ->call('save');

    expect(app(SettingsRepository::class)->get('key.length', 'missing'))->toBe('missing');
});

it('denies access to settings when the plugin closure returns false', function () {
    FilamentShortUrlPlugin::get()->authorizeSettingsUsing(fn (): bool => false);

    expect(SettingsPage::canAccess())->toBeFalse();

    FilamentShortUrlPlugin::get()->authorizeSettingsUsing(null);
});
