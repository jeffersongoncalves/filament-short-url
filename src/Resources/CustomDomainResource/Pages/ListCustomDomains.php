<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource;

class ListCustomDomains extends ListRecords
{
    protected static string $resource = CustomDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
