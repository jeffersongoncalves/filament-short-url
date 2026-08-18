<?php

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\Models\UtmTemplate;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('syncs utm fields into the destination url query string', function () {
    livewire(CreateShortUrl::class)
        ->fillForm(['destination_url' => 'https://example.com/landing'])
        ->set('data.utm_source', 'newsletter')
        ->set('data.utm_medium', 'email')
        ->call('create')
        ->assertHasNoFormErrors();

    $shortUrl = ShortUrl::query()->latest('id')->first();

    expect($shortUrl->destination_url)->toContain('utm_source=newsletter')
        ->and($shortUrl->destination_url)->toContain('utm_medium=email');
});

it('reads utm params back out of a pasted destination url', function () {
    $component = livewire(CreateShortUrl::class)
        ->set('data.destination_url', 'https://example.com/x?utm_source=google&utm_campaign=spring');

    expect($component->get('data.utm_source'))->toBe('google')
        ->and($component->get('data.utm_campaign'))->toBe('spring');
});

it('fills utm fields from a saved template', function () {
    $template = UtmTemplate::factory()->create([
        'utm_source' => 'facebook',
        'utm_medium' => 'social',
        'utm_campaign' => 'launch',
    ]);

    $component = livewire(CreateShortUrl::class)
        ->fillForm(['destination_url' => 'https://example.com/landing'])
        ->set('data.utm_template_id', $template->id);

    expect($component->get('data.utm_source'))->toBe('facebook')
        ->and($component->get('data.utm_medium'))->toBe('social');
});
