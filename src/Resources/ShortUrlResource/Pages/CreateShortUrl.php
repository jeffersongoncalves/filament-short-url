<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HandlesManagerExceptions;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HashesPassword;
use JeffersonGoncalves\LaravelShortUrl\ShortUrlManager;

class CreateShortUrl extends CreateRecord
{
    use HandlesManagerExceptions;
    use HashesPassword;

    protected static string $resource = ShortUrlResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->hashPassword($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        return $this->withManagerExceptionHandling(fn (): Model => app(ShortUrlManager::class)->create($data));
    }
}
