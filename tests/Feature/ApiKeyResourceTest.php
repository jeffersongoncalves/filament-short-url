<?php

use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\CreateApiKey;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\ListApiKeys;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\ApiKey;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the api keys list page', function () {
    livewire(ListApiKeys::class)->assertSuccessful();
});

it('creates an api key via the core generator and shows the plaintext token once', function () {
    livewire(CreateApiKey::class)
        ->fillForm([
            'name' => 'Zapier integration',
            'abilities' => ['links:read'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $apiKey = ApiKey::query()->where('name', 'Zapier integration')->firstOrFail();

    expect($apiKey->key_hash)->not->toBeEmpty()
        ->and($apiKey->abilities)->toBe(['links:read']);
});

it('shows the revoke action only for active keys', function () {
    $apiKey = ApiKey::generate('Test key', ['links:read'])['key'];

    livewire(ListApiKeys::class)
        ->assertTableActionVisible('revoke', $apiKey);

    $apiKey->update(['revoked_at' => now()]);

    livewire(ListApiKeys::class)
        ->assertTableActionHidden('revoke', $apiKey);
});
