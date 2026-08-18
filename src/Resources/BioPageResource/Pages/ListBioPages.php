<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource;

class ListBioPages extends ListRecords
{
    protected static string $resource = BioPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
