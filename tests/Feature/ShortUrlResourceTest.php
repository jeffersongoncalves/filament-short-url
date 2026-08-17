<?php

use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages\EditShortUrl;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\FilamentShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the list page', function () {
    livewire(ListShortUrls::class)->assertSuccessful();
});

it('lists the expected table columns', function () {
    $shortUrl = ShortUrl::factory()->create(['url_key' => 'listme1']);

    livewire(ListShortUrls::class)
        ->assertCanSeeTableRecords([$shortUrl])
        ->assertTableColumnExists('url_key')
        ->assertTableColumnExists('destination_url')
        ->assertTableColumnExists('title')
        ->assertTableColumnExists('is_enabled')
        ->assertTableColumnExists('total_visits')
        ->assertTableColumnExists('expires_at')
        ->assertTableColumnExists('created_at');
});

it('can render the create page', function () {
    livewire(CreateShortUrl::class)->assertSuccessful();
});

it('can create a short url and auto-generates the key when left empty', function () {
    livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'https://example.com/target',
            'title' => 'My link',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $shortUrl = ShortUrl::query()->where('destination_url', 'https://example.com/target')->firstOrFail();

    expect($shortUrl->url_key)->not->toBeEmpty();
});

it('validates that destination_url is required and must be a url', function () {
    livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'not-a-url',
        ])
        ->call('create')
        ->assertHasFormErrors(['destination_url' => 'url']);
});

it('can render the edit page', function () {
    $shortUrl = ShortUrl::factory()->create();

    livewire(EditShortUrl::class, ['record' => $shortUrl->getRouteKey()])
        ->assertSuccessful()
        ->assertFormSet([
            'destination_url' => $shortUrl->destination_url,
            'url_key' => $shortUrl->url_key,
        ]);
});

it('can update a short url', function () {
    $shortUrl = ShortUrl::factory()->create();

    livewire(EditShortUrl::class, ['record' => $shortUrl->getRouteKey()])
        ->fillForm(['title' => 'Updated title'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($shortUrl->fresh()->title)->toBe('Updated title');
});
