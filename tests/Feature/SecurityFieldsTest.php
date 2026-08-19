<?php

use Illuminate\Support\Facades\Hash;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\EditShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\AuditTimeline;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Contracts\SafeBrowsingChecker;
use JeffersonGoncalves\LaravelShortUrl\Data\SafetyResult;
use JeffersonGoncalves\LaravelShortUrl\Models\AuditLog;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('hashes the password field into password_hash on create', function () {
    livewire(CreateShortUrl::class)
        ->fillForm([
            'destination_url' => 'https://example.com/secret',
            'password' => 'super-secret',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $shortUrl = ShortUrl::query()->where('destination_url', 'https://example.com/secret')->firstOrFail();

    expect($shortUrl->password_hash)->not->toBeNull()
        ->and(Hash::check('super-secret', $shortUrl->password_hash))->toBeTrue();
});

it('keeps the current password when the field is left blank on edit', function () {
    $shortUrl = ShortUrl::factory()->create(['password_hash' => Hash::make('original')]);

    livewire(EditShortUrl::class, ['record' => $shortUrl->getRouteKey()])
        ->fillForm(['title' => 'Updated'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Hash::check('original', $shortUrl->fresh()->password_hash))->toBeTrue();
});

it('blocks saving a destination flagged unsafe by safe browsing', function () {
    config()->set('short-url.security.safe_browsing.enabled', true);
    config()->set('short-url.security.safe_browsing.mode', 'sync');

    $this->instance(SafeBrowsingChecker::class, new class implements SafeBrowsingChecker
    {
        public function check(string $url): SafetyResult
        {
            return new SafetyResult('unsafe', now(), ['MALWARE']);
        }
    });

    livewire(CreateShortUrl::class)
        ->fillForm(['destination_url' => 'https://malware.example.com/bad'])
        ->call('create');

    expect(ShortUrl::query()->where('destination_url', 'https://malware.example.com/bad')->exists())->toBeFalse();
});

it('hides the security section when disabled on the plugin', function () {
    FilamentShortUrlPlugin::get()->hideSecurity();

    livewire(CreateShortUrl::class)
        ->assertSuccessful()
        ->assertFormFieldDoesNotExist('password');

    FilamentShortUrlPlugin::get()->hideSecurity(false);
});

it('hides the password column from the table when security is disabled', function () {
    FilamentShortUrlPlugin::get()->hideSecurity();

    ShortUrl::factory()->create();

    livewire(ListShortUrls::class)
        ->assertTableColumnHidden('is_protected');

    FilamentShortUrlPlugin::get()->hideSecurity(false);
});

it('shows audit log entries on the edit page', function () {
    config()->set('short-url.audit.enabled', true);

    $shortUrl = ShortUrl::factory()->create();

    AuditLog::query()->create([
        'short_url_id' => $shortUrl->id,
        'event' => 'link.created',
    ]);

    livewire(AuditTimeline::class, ['record' => $shortUrl])
        ->assertSuccessful()
        ->assertSee('link.created');
});
