<?php

use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Collection;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Tables\ShortUrlsTable;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\Models\Tag;

use function Pest\Livewire\livewire;

/**
 * Regression coverage for the assign_custom_domain bulk action's mutation
 * logic, without driving it through Filament's bulk-action-with-form modal
 * cycle (a Filament v5 partial-render bug reproduces for any modal-form
 * action — see the move_to_folder/apply_tags tests below).
 *
 * @param  array<string, mixed>  $data
 */
function assignCustomDomainToRecords(Collection $records, array $data): void
{
    $method = new ReflectionMethod(ShortUrlsTable::class, 'assignCustomDomainToRecords');
    $method->invoke(null, $records, $data);
}

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

it('shows the assign custom domain bulk action only when domains are enabled', function () {
    config(['short-url.domains.enabled' => false]);

    livewire(ListShortUrls::class)
        ->assertTableBulkActionHidden('assign_custom_domain');

    config(['short-url.domains.enabled' => true]);

    livewire(ListShortUrls::class)
        ->assertTableBulkActionExists('assign_custom_domain');
});

it('assigns a custom domain to short urls in bulk', function () {
    $domain = CustomDomain::factory()->verified()->create();
    $urls = ShortUrl::factory()->count(2)->create();

    assignCustomDomainToRecords($urls, ['custom_domain_id' => $domain->id]);

    expect($urls->fresh()->pluck('custom_domain_id')->unique()->all())->toBe([$domain->id]);
});

it('blocks bulk custom domain assignment when the short key already exists on the target domain', function () {
    $domain = CustomDomain::factory()->verified()->create();
    ShortUrl::factory()->create(['custom_domain_id' => $domain->id, 'url_key' => 'taken']);
    $urls = ShortUrl::factory()->count(1)->create(['url_key' => 'taken']);

    expect(fn () => assignCustomDomainToRecords($urls, ['custom_domain_id' => $domain->id]))
        ->toThrow(Halt::class);

    expect($urls->fresh()->first()->custom_domain_id)->toBe(0);
});

it('blocks bulk custom domain assignment when selected short urls share the same key', function () {
    $domain = CustomDomain::factory()->verified()->create();
    $otherDomain = CustomDomain::factory()->verified()->create();

    // Both start on different domains (0 and $otherDomain) so the composite
    // unique(custom_domain_id, url_key) index doesn't reject the seed data —
    // the collision only appears once both target the same $domain.
    $first = ShortUrl::factory()->create(['url_key' => 'shared-key']);
    $second = ShortUrl::factory()->create(['url_key' => 'shared-key', 'custom_domain_id' => $otherDomain->id]);
    $urls = ShortUrl::query()->whereKey([$first->id, $second->id])->get();

    expect(fn () => assignCustomDomainToRecords($urls, ['custom_domain_id' => $domain->id]))
        ->toThrow(Halt::class);

    expect($first->fresh()->custom_domain_id)->toBe(0)
        ->and($second->fresh()->custom_domain_id)->toBe($otherDomain->id);
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
