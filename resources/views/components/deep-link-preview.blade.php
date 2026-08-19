@php
    use JeffersonGoncalves\LaravelShortUrl\Registries\DeepLinkRegistry;

    $app = $destinationUrl ? app(DeepLinkRegistry::class)->forUrl($destinationUrl) : null;
@endphp

<div class="fi-su-deep-link">
    @if (! $app)
        <p class="fi-su-deep-link-empty">
            {{ __('filament-short-url::resources/short-url.deep_link.no_match') }}
        </p>
    @else
        <div class="fi-su-deep-link-row">
            <span class="fi-su-deep-link-icon-wrap">
                <x-filament::icon icon="heroicon-o-device-phone-mobile" class="fi-su-deep-link-icon" />
            </span>
            <div>
                <p class="fi-su-deep-link-label">{{ $app->label }}</p>
                <p class="fi-su-deep-link-scheme">{{ $app->scheme }}</p>
            </div>
        </div>
    @endif
</div>
