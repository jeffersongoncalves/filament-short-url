<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;

class ListShortUrls extends ListRecords
{
    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
