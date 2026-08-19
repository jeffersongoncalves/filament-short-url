<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HandlesManagerExceptions;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HashesPassword;
use JeffersonGoncalves\LaravelShortUrl\ShortUrlManager;

class CreateShortUrl extends CreateRecord
{
    use HandlesManagerExceptions;
    use HashesPassword;
    use HasWizard;

    protected static string $resource = ShortUrlResource::class;

    public function form(Form $form): Form
    {
        if (! FilamentShortUrlPlugin::get()->isWizardFormEnabled()) {
            return parent::form($form);
        }

        return $form
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps()),
            ])
            ->columns(null);
    }

    public function getSteps(): array
    {
        return ShortUrlResource::steps();
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
