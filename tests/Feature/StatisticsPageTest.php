<?php

use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Statistics;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\ExpiringLinks;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\GlobalOverview;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\TopLinks;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\Models\Visit;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the statistics page for a short url', function () {
    $shortUrl = ShortUrl::factory()->create();

    Visit::query()->create([
        'short_url_id' => $shortUrl->id,
        'visited_at' => now(),
        'device_type' => 'mobile',
        'browser' => 'Chrome',
        'operating_system' => 'Android',
        'country_code' => 'BR',
        'city' => 'São Paulo',
        'referer_type' => 'search',
        'browser_language' => 'pt-BR',
        'is_qr_scan' => false,
        'is_bot' => false,
        'is_vpn' => false,
        'is_proxy' => false,
        'is_tor' => false,
        'is_datacenter' => false,
    ]);

    livewire(Statistics::class, ['record' => $shortUrl->getRouteKey()])
        ->assertSuccessful();
});

it('hides the statistics row action and dashboard widgets when hideStatistics is enabled', function () {
    FilamentShortUrlPlugin::get()->hideStatistics();

    $shortUrl = ShortUrl::factory()->create();

    livewire(ListShortUrls::class)
        ->assertTableActionHidden('statistics', $shortUrl);

    FilamentShortUrlPlugin::get()->hideStatistics(false);
});

it('renders the dashboard widgets', function () {
    ShortUrl::factory()->create(['expires_at' => now()->addDays(3)]);

    livewire(GlobalOverview::class)->assertSuccessful();
    livewire(TopLinks::class)->assertSuccessful();
    livewire(ExpiringLinks::class)->assertSuccessful();
});
