<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;

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
