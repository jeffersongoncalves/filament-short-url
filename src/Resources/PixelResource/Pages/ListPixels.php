<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource;

class ListPixels extends ListRecords
{
    protected static string $resource = PixelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
