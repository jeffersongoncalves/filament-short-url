@php
    use JeffersonGoncalves\LaravelShortUrl\Registries\DeepLinkRegistry;

    $app = $destinationUrl ? app(DeepLinkRegistry::class)->forUrl($destinationUrl) : null;
@endphp

<div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
    @if (! $app)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('filament-short-url::resources/short-url.deep_link.no_match') }}
        </p>
    @else
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                <x-heroicon-o-device-phone-mobile class="h-6 w-6" />
            </span>
            <div>
                <p class="font-medium text-gray-950 dark:text-white">{{ $app->label }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $app->scheme }}</p>
            </div>
        </div>
    @endif
</div>
