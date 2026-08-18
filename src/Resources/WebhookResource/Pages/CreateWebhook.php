<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource;

class CreateWebhook extends CreateRecord
{
    protected static string $resource = WebhookResource::class;
}
