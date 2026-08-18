<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource;

class ListWebhooks extends ListRecords
{
    protected static string $resource = WebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
