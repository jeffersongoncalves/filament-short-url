<?php

use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\CreateFolder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\ListFolders;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Pages\CreateTag;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Pages\ListTags;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;
use JeffersonGoncalves\LaravelShortUrl\Models\Tag;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the folders list page and shows nested folders indented', function () {
    $parent = Folder::factory()->create(['name' => 'Marketing']);
    Folder::factory()->create(['name' => 'Campaigns', 'parent_id' => $parent->id]);

    livewire(ListFolders::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$parent]);
});

it('creates a folder with a parent', function () {
    $parent = Folder::factory()->create();

    livewire(CreateFolder::class)
        ->fillForm(['name' => 'Sub Folder', 'parent_id' => $parent->id])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Folder::query()->where('name', 'Sub Folder')->first()?->parent_id)->toBe($parent->id);
});

it('can render the tags list page with usage counts', function () {
    $tag = Tag::factory()->create();

    livewire(ListTags::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$tag]);
});

it('creates a tag', function () {
    livewire(CreateTag::class)
        ->fillForm(['name' => 'campaign-2026'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Tag::query()->where('name', 'campaign-2026')->exists())->toBeTrue();
});
