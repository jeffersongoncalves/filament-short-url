<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HandlesManagerExceptions;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HashesPassword;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Schemas\ShortUrlForm;
use JeffersonGoncalves\LaravelShortUrl\ShortUrlManager;

class CreateShortUrl extends CreateRecord
{
    use HandlesManagerExceptions;
    use HashesPassword;
    use HasWizard;

    protected static string $resource = ShortUrlResource::class;

    public function form(Schema $schema): Schema
    {
        if (! FilamentShortUrlPlugin::get()->isWizardFormEnabled()) {
            return parent::form($schema);
        }

        return $schema
            ->columns(null)
            ->components([$this->getWizardComponent()]);
    }

    public function getSteps(): array
    {
        return ShortUrlForm::steps();
    }

    protected function hasSkippableSteps(): bool
    {
        return true;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->hashPassword($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        return $this->withManagerExceptionHandling(fn (): Model => app(ShortUrlManager::class)->create($data));
    }
}
