<?php

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

it('renders the qr download modal action on the table', function () {
    $shortUrl = ShortUrl::factory()->create();

    livewire(ListShortUrls::class)
        ->assertTableActionVisible('qr', $shortUrl);
});

it('renders the edit page with a destination matching a registered deep link app', function () {
    $shortUrl = ShortUrl::factory()->create(['destination_url' => 'https://open.spotify.com/track/abc']);

    livewire(EditShortUrl::class, ['record' => $shortUrl->getRouteKey()])
        ->assertSuccessful();
});
