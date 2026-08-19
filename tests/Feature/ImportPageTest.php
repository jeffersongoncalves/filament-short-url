<?php

use Illuminate\Support\Facades\Storage;
use JeffersonGoncalves\Filament\ShortUrl\Pages\ImportPage;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);

    Storage::fake('local');
});

it('can render the import page', function () {
    livewire(ImportPage::class)->assertSuccessful();
});

it('previews and imports a csv file', function () {
    $csv = "destination_url,url_key,title\nhttps://example.com/a,,Example A\nhttps://example.com/b,,Example B\n";
    Storage::disk('local')->put('short-url-imports/links.csv', $csv);

    $component = livewire(ImportPage::class)
        ->set('data.driver', 'csv')
        // Filament v3's FileUpload keeps its live state as a UUID-keyed
        // array rather than a plain string.
        ->set('data.file', ['fake-upload-uuid' => 'short-url-imports/links.csv'])
        ->call('runPreview');

    expect($component->get('preview')['totalRows'])->toBe(2);

    $component->call('runImport');

    expect($component->get('report')['imported'])->toBe(2)
        ->and(ShortUrl::query()->where('destination_url', 'https://example.com/a')->exists())->toBeTrue();
});
