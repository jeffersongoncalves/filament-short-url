<?php

use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\Filament\ShortUrl\Widgets\UsageOverview;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('is hidden when multi-tenancy is disabled', function () {
    config()->set('short-url.tenancy.enabled', false);

    expect(UsageOverview::canView())->toBeFalse();
});

it('shows usage vs the configured plan limit when multi-tenancy is enabled', function () {
    config()->set('short-url.tenancy.enabled', true);
    config()->set('short-url.tenancy.plans.default.links_per_month', 10);

    ShortUrl::factory()->count(3)->create();

    livewire(UsageOverview::class)
        ->assertSuccessful()
        ->assertSee('3 / 10');
});
