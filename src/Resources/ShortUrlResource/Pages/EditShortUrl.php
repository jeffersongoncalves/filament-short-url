<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HandlesManagerExceptions;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HashesPassword;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\AuditTimeline;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\ShortUrlManager;

class EditShortUrl extends EditRecord
{
    use HandlesManagerExceptions;
    use HashesPassword;

    protected static string $resource = ShortUrlResource::class;

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
