<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Concerns;

use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use JeffersonGoncalves\LaravelShortUrl\Exceptions\PlanLimitExceeded;
use JeffersonGoncalves\LaravelShortUrl\Exceptions\RequiredUtmParameterMissing;
use JeffersonGoncalves\LaravelShortUrl\Exceptions\UnsafeDestinationException;

/**
 * Create/Edit route through ShortUrlManager::create()/update() rather than
 * Filament's default Eloquent save, so the core's own business rules
 * (required UTM parameters, plan limits, UTM template resolution) apply in
 * the admin panel exactly as they do everywhere else — see
 * ShortUrlManager::assertRequiredUtmParametersPresent() and PlanLimits.
 */
trait HandlesManagerExceptions
{
    protected function withManagerExceptionHandling(callable $callback): mixed
    {
        try {
            return $callback();
        } catch (UnsafeDestinationException $exception) {
            $this->haltWith(
                __('filament-short-url::resources/short-url.security.safe_browsing_blocked'),
                $exception->getMessage(),
            );
        } catch (RequiredUtmParameterMissing $exception) {
            $this->haltWith(
                __('filament-short-url::resources/short-url.utm.required_missing_title'),
                __('filament-short-url::resources/short-url.utm.required_missing_body', ['parameter' => $exception->parameter]),
            );
        } catch (PlanLimitExceeded $exception) {
            $this->haltWith(
                __('filament-short-url::resources/short-url.usage.plan_limit_exceeded_title'),
                __('filament-short-url::resources/short-url.usage.plan_limit_exceeded_body', [
                    'limit_key' => $exception->limitKey,
                    'limit' => $exception->limit,
                ]),
            );
        }
    }

    protected function haltWith(string $title, string $body): never
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->danger()
            ->send();

        throw new Halt;
    }
}
