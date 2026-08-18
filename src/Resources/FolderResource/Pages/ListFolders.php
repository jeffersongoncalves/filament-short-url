<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource;

class ListFolders extends ListRecords
{
    protected static string $resource = FolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
