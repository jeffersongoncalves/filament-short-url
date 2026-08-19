<?php

use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\EditShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('saves the qr design and deep link fields', function () {
    livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'https://instagram.com/example',
            'qr_design' => [
                'dotsStyle' => 'dots',
                'eyesStyle' => 'rounded',
                'errorCorrection' => 'H',
                'margin' => 10,
            ],
            'auto_open_app_mobile' => true,
            'app_scheme_override' => 'myapp://',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $shortUrl = ShortUrl::query()->where('destination_url', 'https://instagram.com/example')->firstOrFail();

    expect($shortUrl->qr_design['dotsStyle'])->toBe('dots')
        ->and($shortUrl->qr_design['errorCorrection'])->toBe('H')
        ->and($shortUrl->auto_open_app_mobile)->toBeTrue()
        ->and($shortUrl->app_scheme_override)->toBe('myapp://');
});

it('updates the qr preview when the design changes', function () {
    $component = livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'https://example.com',
            'qr_design' => ['margin' => 0],
        ]);

    $htmlBefore = $component->html();

    $component->fillForm(['qr_design' => ['margin' => 40]]);

    $htmlAfter = $component->html();

    expect($htmlBefore)->not->toBe($htmlAfter);
});

it('hides the qr design and deep link sections when disabled on the plugin', function () {
    FilamentShortUrlPlugin::get()->hideQrDesigner()->hideDeepLinking();

    livewire(CreateShortUrl::class)
        ->assertSuccessful()
        ->assertFormFieldDoesNotExist('qr_design.margin')
        ->assertFormFieldDoesNotExist('app_scheme_override');

    FilamentShortUrlPlugin::get()->hideQrDesigner(false)->hideDeepLinking(false);
});

it('renders the qr download modal action on the table', function () {
    $shortUrl = ShortUrl::factory()->create();

    livewire(ListShortUrls::class)
        ->assertTableActionVisible('qr', $shortUrl);
});

it('hides qr scans column and action from the table when the qr designer is disabled', function () {
    FilamentShortUrlPlugin::get()->hideQrDesigner();

    $shortUrl = ShortUrl::factory()->create();

    livewire(ListShortUrls::class)
        ->assertTableColumnHidden('qr_scans')
        ->assertTableActionHidden('qr', $shortUrl);

    FilamentShortUrlPlugin::get()->hideQrDesigner(false);
});

it('renders the edit page with a destination matching a registered deep link app', function () {
    $shortUrl = ShortUrl::factory()->create(['destination_url' => 'https://open.spotify.com/track/abc']);

    livewire(EditShortUrl::class, ['record' => $shortUrl->getRouteKey()])
        ->assertSuccessful();
});
