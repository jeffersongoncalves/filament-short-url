<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource;

class EditCustomDomain extends EditRecord
{
    protected static string $resource = CustomDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
