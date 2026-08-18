<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource;
use JeffersonGoncalves\LaravelShortUrl\Models\ApiKey;

class CreateApiKey extends CreateRecord
{
    protected static string $resource = ApiKeyResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $generated = ApiKey::generate(
            $data['name'],
            $data['abilities'] ?? [],
            $data['expires_at'] ?? null,
        );

        Notification::make()
            ->title(__('filament-short-url::resources/api-key.actions.created_title'))
            ->body(__('filament-short-url::resources/api-key.actions.created_body', ['token' => $generated['token']]))
            ->persistent()
            ->success()
            ->send();

        return $generated['key'];
    }
}
