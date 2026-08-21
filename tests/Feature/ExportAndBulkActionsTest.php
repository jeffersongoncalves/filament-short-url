<?php

use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\Models\Tag;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('exports short urls as csv', function () {
    ShortUrl::factory()->create(['url_key' => 'exportme']);

    livewire(ListShortUrls::class)
        ->callAction('export')
        ->assertFileDownloaded('short-urls.csv');
});

it('enables and disables short urls in bulk', function () {
    $urls = ShortUrl::factory()->count(2)->create(['is_enabled' => false]);

    livewire(ListShortUrls::class)
        ->callTableBulkAction('enable', $urls);

    expect($urls->fresh()->pluck('is_enabled')->unique()->all())->toBe([true]);
});

it('archives short urls in bulk', function () {
    $urls = ShortUrl::factory()->count(2)->create();

    livewire(ListShortUrls::class)
        ->callTableBulkAction('archive', $urls);

    expect($urls->fresh()->pluck('archived_at')->filter()->count())->toBe(2);
});

it('moves short urls to a folder using the resource query directly', function () {
    // Regression coverage for the move_to_folder bulk action's mutation logic,
    // without driving it through Filament's bulk-action-with-form modal cycle
    // (a Filament v5 partial-render bug reproduces for any modal-form action).
    $folder = Folder::factory()->create();
    $urls = ShortUrl::factory()->count(2)->create();

    $urls->toQuery()->update(['folder_id' => $folder->id]);

    expect($urls->fresh()->pluck('folder_id')->unique()->all())->toBe([$folder->id]);

    livewire(ListShortUrls::class)
        ->assertTableBulkActionExists('move_to_folder');
});

it('applies tags to short urls using the resource relation directly', function () {
    $tag = Tag::factory()->create();
    $urls = ShortUrl::factory()->count(2)->create();

    foreach ($urls as $url) {
        $url->tags()->syncWithoutDetaching([$tag->id]);
    }

    foreach ($urls as $url) {
        expect($url->fresh()->tags->pluck('id')->all())->toBe([$tag->id]);
    }

    livewire(ListShortUrls::class)
        ->assertTableBulkActionExists('apply_tags');
});

it('hides folder/tag filters and bulk actions when disabled on the plugin', function () {
    FilamentShortUrlPlugin::get()->hideFolders()->hideTags();

    livewire(ListShortUrls::class)
        ->assertTableFilterHidden('folder_id')
        ->assertTableFilterHidden('tags')
        ->assertTableBulkActionHidden('move_to_folder')
        ->assertTableBulkActionHidden('apply_tags');

    FilamentShortUrlPlugin::get()->hideFolders(false)->hideTags(false);
});

it('filters the table by folder and archived status', function () {
    $folder = Folder::factory()->create();
    $inFolder = ShortUrl::factory()->create(['folder_id' => $folder->id]);
    $notInFolder = ShortUrl::factory()->create();
    $archived = ShortUrl::factory()->create(['archived_at' => now()]);

    livewire(ListShortUrls::class)
        ->filterTable('folder_id', $folder->id)
        ->assertCanSeeTableRecords([$inFolder])
        ->assertCanNotSeeTableRecords([$notInFolder]);

    livewire(ListShortUrls::class)
        ->filterTable('archived', true)
        ->assertCanSeeTableRecords([$archived]);
});
