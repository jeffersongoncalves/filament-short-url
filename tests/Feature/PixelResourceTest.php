<?php

use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\CreatePixel;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\EditPixel;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\ListPixels;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Data\PixelProvider;
use JeffersonGoncalves\LaravelShortUrl\Models\Pixel;
use JeffersonGoncalves\LaravelShortUrl\Registries\PixelProviderRegistry;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the pixels list page', function () {
    livewire(ListPixels::class)->assertSuccessful();
});

it('creates a pixel and stores provider-specific config under the right keys', function () {
    livewire(CreatePixel::class)
        ->fillForm([
            'name' => 'Meta Pixel — Homepage',
            'provider_key' => 'meta_pixel',
            'config_field_0' => '123456789',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $pixel = Pixel::query()->where('name', 'Meta Pixel — Homepage')->firstOrFail();

    expect($pixel->config)->toBe(['pixel_id' => '123456789']);
});

it('loads existing config back into the provider fields on edit', function () {
    $pixel = Pixel::factory()->create([
        'provider_key' => 'meta_pixel',
        'config' => ['pixel_id' => '999'],
    ]);

    livewire(EditPixel::class, ['record' => $pixel->getRouteKey()])
        ->assertFormSet(['config_field_0' => '999']);
});

it('registers a fake pixel provider and shows it in the pixel resource', function () {
    app(PixelProviderRegistry::class)->register(
        new PixelProvider(
            'fake_pixel',
            'Fake Pixel',
            [['key' => 'token', 'label' => 'Token', 'type' => 'text']],
            "fake('{token}')",
        ),
    );

    livewire(CreatePixel::class)
        ->assertFormFieldExists('provider_key')
        ->fillForm([
            'name' => 'Fake',
            'provider_key' => 'fake_pixel',
            'config_field_0' => 'abc',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $pixel = Pixel::query()->where('name', 'Fake')->firstOrFail();

    expect($pixel->config)->toBe(['token' => 'abc']);
});
