<?php

use JeffersonGoncalves\Filament\ShortUrl\Widgets\Concerns\HasGlobalStatsPayload;
use JeffersonGoncalves\LaravelShortUrl\Data\StatsPayload;

function globalPayload(): StatsPayload
{
    return (new class
    {
        use HasGlobalStatsPayload;

        public function payload(): StatsPayload
        {
            return $this->getGlobalPayload();
        }
    })->payload();
}

beforeEach(function () {
    config()->set('short-url.tenancy.enabled', true);
});

it('reuses the memoized global payload within one request', function () {
    config()->set('short-url.tenancy.current_tenant_id', 1);

    expect(globalPayload())->toBe(globalPayload());
});

it('never shares the global payload between tenants', function () {
    config()->set('short-url.tenancy.current_tenant_id', 1);
    $tenantA = globalPayload();

    config()->set('short-url.tenancy.current_tenant_id', 2);

    expect(globalPayload())->not->toBe($tenantA);
});

it('recomputes the payload on the next request of a persistent worker', function () {
    config()->set('short-url.tenancy.current_tenant_id', 1);
    $first = globalPayload();

    // What Octane does between requests.
    app()->forgetScopedInstances();

    expect(globalPayload())->not->toBe($first);
});
