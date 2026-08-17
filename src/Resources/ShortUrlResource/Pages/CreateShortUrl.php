<?php

namespace JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\LaravelShortUrl\Services\KeyGenerator;

class CreateShortUrl extends CreateRecord
{
    protected static string $resource = ShortUrlResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['url_key'])) {
            $data['url_key'] = app(KeyGenerator::class)->generate($data['custom_domain_id'] ?? null);
        }

        return $data;
    }
}
