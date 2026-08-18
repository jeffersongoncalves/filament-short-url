<?php

use Illuminate\Support\Facades\Queue;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\CreateCustomDomain;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\EditCustomDomain;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\ListCustomDomains;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Jobs\VerifyDomainJob;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the custom domains list page', function () {
    livewire(ListCustomDomains::class)->assertSuccessful();
});

it('can create a custom domain', function () {
    livewire(CreateCustomDomain::class)
        ->fillForm(['domain' => 'links.example.com'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(CustomDomain::query()->where('domain', 'links.example.com')->exists())->toBeTrue();
});

it('can edit a custom domain', function () {
    $domain = CustomDomain::factory()->create();

    livewire(EditCustomDomain::class, ['record' => $domain->getRouteKey()])
        ->fillForm(['is_wildcard' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($domain->fresh()->is_wildcard)->toBeTrue();
});

it('shows the dns instructions action on the table', function () {
    $domain = CustomDomain::factory()->create();

    livewire(ListCustomDomains::class)
        ->assertTableActionVisible('dns_instructions', $domain);
});

it('dispatches a VerifyDomainJob for the verify now action', function () {
    Queue::fake();

    $domain = CustomDomain::factory()->create();

    livewire(ListCustomDomains::class)
        ->callTableAction('verify', $domain);

    Queue::assertPushed(VerifyDomainJob::class, fn (VerifyDomainJob $job): bool => $job->customDomainId === $domain->id);
});
