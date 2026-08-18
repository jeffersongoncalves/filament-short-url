<?php

use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('creates a short url with rule-based targeting', function () {
    livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'https://example.com/fallback',
            'destination_type' => 'rules',
            'targeting_rules' => [
                [
                    'match' => 'and',
                    'conditions' => [
                        ['type' => 'device', 'operator' => 'in', 'value' => ['mobile']],
                    ],
                    'destination' => 'https://example.com/mobile',
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $shortUrl = ShortUrl::query()->where('destination_url', 'https://example.com/fallback')->firstOrFail();

    expect($shortUrl->destination_type)->toBe('rules')
        ->and($shortUrl->targeting_rules[0]['destination'])->toBe('https://example.com/mobile')
        ->and($shortUrl->targeting_rules[0]['conditions'][0]['type'])->toBe('device');
});

it('creates a short url with an A/B split and rejects weights that do not sum to 100', function () {
    livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'https://example.com/fallback',
            'destination_type' => 'split',
            'rotation_variants' => [
                'sticky' => true,
                'variants' => [
                    ['label' => 'A', 'url' => 'https://example.com/a', 'weight' => 40],
                    ['label' => 'B', 'url' => 'https://example.com/b', 'weight' => 40],
                ],
            ],
        ])
        ->call('create')
        ->assertHasFormErrors(['rotation_variants.variants']);

    livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'https://example.com/fallback2',
            'destination_type' => 'split',
            'rotation_variants' => [
                'sticky' => true,
                'variants' => [
                    ['label' => 'A', 'url' => 'https://example.com/a', 'weight' => 60],
                    ['label' => 'B', 'url' => 'https://example.com/b', 'weight' => 40],
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $shortUrl = ShortUrl::query()->where('destination_url', 'https://example.com/fallback2')->firstOrFail();

    expect($shortUrl->rotation_variants['sticky'])->toBeTrue()
        ->and($shortUrl->rotation_variants['variants'])->toHaveCount(2);
});
