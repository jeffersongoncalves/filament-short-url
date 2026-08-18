<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HandlesUnsafeDestination;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HashesPassword;
use JeffersonGoncalves\LaravelShortUrl\Services\KeyGenerator;

class CreateShortUrl extends CreateRecord
{
    use HandlesUnsafeDestination;
    use HashesPassword;

    protected static string $resource = ShortUrlResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['url_key'])) {
            $data['url_key'] = app(KeyGenerator::class)->generate($data['custom_domain_id'] ?? null);
        }

        return $this->hashPassword($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        return $this->withUnsafeDestinationHandling(fn (): Model => parent::handleRecordCreation($data));
    }
}
