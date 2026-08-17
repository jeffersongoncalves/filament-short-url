<?php

namespace JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource;

class EditShortUrl extends EditRecord
{
    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
