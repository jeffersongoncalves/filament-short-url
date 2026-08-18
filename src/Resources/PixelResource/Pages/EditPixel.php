<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\Concerns\MapsConfigFields;

class EditPixel extends EditRecord
{
    use MapsConfigFields;

    protected static string $resource = PixelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->unpackConfigFields($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->packConfigFields($data);
    }
}
