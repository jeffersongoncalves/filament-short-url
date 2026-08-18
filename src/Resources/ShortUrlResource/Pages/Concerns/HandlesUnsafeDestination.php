<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns;

use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use JeffersonGoncalves\LaravelShortUrl\Exceptions\UnsafeDestinationException;

trait HandlesUnsafeDestination
{
    protected function withUnsafeDestinationHandling(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (UnsafeDestinationException $exception) {
            Notification::make()
                ->title(__('filament-short-url::resources/short-url.security.safe_browsing_blocked'))
                ->body($exception->getMessage())
                ->danger()
                ->send();

            throw new Halt;
        }
    }
}
