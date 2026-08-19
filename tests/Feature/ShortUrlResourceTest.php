<?php

use Illuminate\Support\Facades\Gate;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\EditShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
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

it('can render the create page as a wizard when enabled', function () {
    FilamentShortUrlPlugin::get()->wizardForm();

    livewire(CreateShortUrl::class)->assertSuccessful();

    FilamentShortUrlPlugin::get()->wizardForm(false);
});

it('can render the edit page as a wizard when enabled', function () {
    FilamentShortUrlPlugin::get()->wizardForm();

    $shortUrl = ShortUrl::factory()->create();

    livewire(EditShortUrl::class, ['record' => $shortUrl->getRouteKey()])->assertSuccessful();

    FilamentShortUrlPlugin::get()->wizardForm(false);
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

it('locks url_key from being changed on edit', function () {
    $shortUrl = ShortUrl::factory()->create(['url_key' => 'original-key']);

    livewire(EditShortUrl::class, ['record' => $shortUrl->getRouteKey()])
        ->assertFormFieldIsDisabled('url_key')
        ->fillForm(['url_key' => 'attempted-change'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($shortUrl->fresh()->url_key)->toBe('original-key');
});

it('hides the pixels section when disabled on the plugin', function () {
    FilamentShortUrlPlugin::get()->hidePixels();

    livewire(CreateShortUrl::class)
        ->assertSuccessful()
        ->assertFormFieldDoesNotExist('pixels');

    FilamentShortUrlPlugin::get()->hidePixels(false);
});

it('hides rule/split targeting when disabled on the plugin', function () {
    FilamentShortUrlPlugin::get()->hideTargeting();

    livewire(CreateShortUrl::class)
        ->assertSuccessful()
        ->assertFormFieldDoesNotExist('destination_type');

    FilamentShortUrlPlugin::get()->hideTargeting(false);
});

it('filters the table by enabled status', function () {
    $enabled = ShortUrl::factory()->create(['is_enabled' => true]);
    $disabled = ShortUrl::factory()->create(['is_enabled' => false]);

    livewire(ListShortUrls::class)
        ->filterTable('is_enabled', true)
        ->assertCanSeeTableRecords([$enabled])
        ->assertCanNotSeeTableRecords([$disabled]);
});

it('filters the table by created period', function () {
    $old = ShortUrl::factory()->create(['created_at' => now()->subDays(10)]);
    $recent = ShortUrl::factory()->create(['created_at' => now()]);

    livewire(ListShortUrls::class)
        ->filterTable('created_at', ['created_from' => now()->subDay()->toDateString()])
        ->assertCanSeeTableRecords([$recent])
        ->assertCanNotSeeTableRecords([$old]);
});

it('allows access when no plugin authorization closure is set', function () {
    livewire(ListShortUrls::class)->assertSuccessful();
});

it('denies access when the plugin authorization closure returns false', function () {
    FilamentShortUrlPlugin::get()->authorizeUsing(fn (): bool => false);

    livewire(ListShortUrls::class)->assertForbidden();

    FilamentShortUrlPlugin::get()->authorizeUsing(null);
});

it('lets the plugin authorization closure override a denying policy', function () {
    $denyingPolicy = new class
    {
        public function viewAny(): bool
        {
            return false;
        }
    };

    Gate::policy(ShortUrl::class, $denyingPolicy::class);

    FilamentShortUrlPlugin::get()->authorizeUsing(fn (): bool => true);

    livewire(ListShortUrls::class)->assertSuccessful();

    FilamentShortUrlPlugin::get()->authorizeUsing(null);
});
