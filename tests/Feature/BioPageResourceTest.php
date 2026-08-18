<?php

use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\CreateBioPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\EditBioPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\ListBioPages;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Widgets\BlockAnalytics;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\BioLink;
use JeffersonGoncalves\LaravelShortUrl\Models\BioPage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the bio pages list page', function () {
    livewire(ListBioPages::class)->assertSuccessful();
});

it('creates a bio page with blocks', function () {
    livewire(CreateBioPage::class)
        ->fillForm([
            'handle' => 'jefferson',
            'title' => 'Jefferson Gonçalves',
            'is_published' => true,
            'links' => [
                ['type' => 'link', 'label' => 'My Site', 'content' => ['url' => 'https://example.com'], 'is_enabled' => true],
                ['type' => 'text', 'content' => ['body' => 'Welcome!'], 'is_enabled' => true],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $bioPage = BioPage::query()->where('handle', 'jefferson')->firstOrFail();

    expect($bioPage->links)->toHaveCount(2)
        ->and($bioPage->links->firstWhere('type', 'link')->content['url'])->toBe('https://example.com');
});

it('can edit a bio page and reorder blocks', function () {
    $bioPage = BioPage::factory()->create();
    BioLink::query()->create(['bio_page_id' => $bioPage->id, 'type' => 'link', 'label' => 'A', 'content' => ['url' => 'https://a.example'], 'position' => 0]);

    livewire(EditBioPage::class, ['record' => $bioPage->getRouteKey()])
        ->assertSuccessful()
        ->assertFormSet(['handle' => $bioPage->handle]);
});

it('shows per-block click analytics', function () {
    $bioPage = BioPage::factory()->create();
    $link = BioLink::query()->create([
        'bio_page_id' => $bioPage->id,
        'type' => 'link',
        'label' => 'Popular link',
        'content' => ['url' => 'https://example.com'],
        'click_count' => 42,
    ]);

    livewire(BlockAnalytics::class, ['record' => $bioPage])
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$link]);
});
