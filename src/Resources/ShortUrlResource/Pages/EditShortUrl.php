<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HandlesManagerExceptions;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HashesPassword;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Schemas\ShortUrlForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\AuditTimeline;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\ShortUrlManager;

class EditShortUrl extends EditRecord
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->hashPassword($data);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof ShortUrl) {
            return parent::handleRecordUpdate($record, $data);
        }

        return $this->withManagerExceptionHandling(fn (): Model => app(ShortUrlManager::class)->update($record, $data));
    }

    protected function getFooterWidgets(): array
    {
        return [
            AuditTimeline::class,
        ];
    }
}
