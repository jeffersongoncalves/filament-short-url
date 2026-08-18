<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\Concerns\MapsConfigFields;

class CreatePixel extends CreateRecord
{
    use MapsConfigFields;

    protected static string $resource = PixelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->packConfigFields($data);
    }
}
