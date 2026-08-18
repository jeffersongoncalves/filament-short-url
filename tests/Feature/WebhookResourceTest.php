<?php

use Illuminate\Support\Facades\Queue;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\CreateWebhook;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\ListWebhooks;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Widgets\WebhookDeliveries;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Factories\UserFactory;
use JeffersonGoncalves\LaravelShortUrl\Jobs\SendWebhookJob;
use JeffersonGoncalves\LaravelShortUrl\Models\Webhook;
use JeffersonGoncalves\LaravelShortUrl\Models\WebhookDelivery;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->admin = UserFactory::new()->create();

    filament()->setCurrentPanel(filament()->getPanel('admin'));

    $this->actingAs($this->admin);
});

it('can render the webhooks list page', function () {
    livewire(ListWebhooks::class)->assertSuccessful();
});

it('can create a webhook', function () {
    livewire(CreateWebhook::class)
        ->fillForm([
            'url' => 'https://example.com/hook',
            'events' => ['link.created'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Webhook::query()->where('url', 'https://example.com/hook')->exists())->toBeTrue();
});

it('dispatches a test delivery for the test action', function () {
    Queue::fake();

    $webhook = Webhook::factory()->create();

    livewire(ListWebhooks::class)
        ->callTableAction('test', $webhook);

    Queue::assertPushed(SendWebhookJob::class, fn (SendWebhookJob $job): bool => $job->webhookId === $webhook->id && $job->event === 'test');
});

it('shows the view payload action on a delivery', function () {
    $webhook = Webhook::factory()->create();
    $delivery = WebhookDelivery::query()->create([
        'webhook_id' => $webhook->id,
        'event' => 'link.created',
        'payload' => ['url_key' => 'abc123'],
        'attempt' => 1,
        'succeeded' => true,
        'response_status' => 200,
    ]);

    livewire(WebhookDeliveries::class, ['record' => $webhook])
        ->assertTableActionVisible('view_payload', $delivery);
});

it('replays a webhook delivery', function () {
    Queue::fake();

    $webhook = Webhook::factory()->create();
    $delivery = WebhookDelivery::query()->create([
        'webhook_id' => $webhook->id,
        'event' => 'link.created',
        'payload' => ['url_key' => 'abc123'],
        'attempt' => 1,
        'succeeded' => false,
        'response_status' => 500,
    ]);

    livewire(WebhookDeliveries::class, ['record' => $webhook])
        ->callTableAction('replay', $delivery);

    Queue::assertPushed(SendWebhookJob::class, fn (SendWebhookJob $job): bool => $job->webhookId === $webhook->id && $job->payload === ['url_key' => 'abc123']);
});
