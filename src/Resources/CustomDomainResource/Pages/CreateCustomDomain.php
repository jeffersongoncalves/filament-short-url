<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource;

class CreateCustomDomain extends CreateRecord
{
    protected static string $resource = CustomDomainResource::class;
}
