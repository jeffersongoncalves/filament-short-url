<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HandlesUnsafeDestination;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns\HashesPassword;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\AuditTimeline;

class EditShortUrl extends EditRecord
{
    use HandlesUnsafeDestination;
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
        return $this->withUnsafeDestinationHandling(fn (): Model => parent::handleRecordUpdate($record, $data));
    }

    protected function getFooterWidgets(): array
    {
        return [
            AuditTimeline::class,
        ];
    }
}
