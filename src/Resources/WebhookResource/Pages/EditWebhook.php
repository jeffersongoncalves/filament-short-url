<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Widgets\WebhookDeliveries;

class EditWebhook extends EditRecord
{
    protected static string $resource = WebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            WebhookDeliveries::class,
        ];
    }
}
