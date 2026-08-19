@php
    use JeffersonGoncalves\LaravelShortUrl\Contracts\QrCodeBuilder;
    use JeffersonGoncalves\LaravelShortUrl\Data\QrDesign;

    $svg = null;
    $unavailable = ! class_exists(\Endroid\QrCode\Builder\Builder::class);

    if (! $unavailable) {
        try {
            $qrDesign = new QrDesign(
                dotsStyle: $design['dotsStyle'] ?? 'square',
                eyesStyle: $design['eyesStyle'] ?? 'square',
                gradient: $design['gradient'] ?? null,
                margin: $design['margin'] ?? 0,
                logoPath: ($design['logoPath'] ?? null) ? storage_path('app/public/'.$design['logoPath']) : null,
                logoSizePercent: $design['logoSizePercent'] ?? 20,
                errorCorrection: $design['errorCorrection'] ?? 'M',
            );

            $svg = app()->makeWith(QrCodeBuilder::class, ['data' => $previewUrl ?? 'https://short.example/preview'])
                ->design($qrDesign)
                ->toSvg();
        } catch (\Throwable $e) {
            $svg = null;
        }
    }
@endphp

<div class="fi-su-qr-preview">
    @if ($unavailable)
        <p class="fi-su-qr-preview-empty">
            {{ __('filament-short-url::resources/short-url.qr.package_missing') }}
        </p>
    @elseif ($svg)
        <div class="fi-su-qr-preview-image">
            {!! $svg !!}
        </div>
    @else
        <p class="fi-su-qr-preview-empty">
            {{ __('filament-short-url::resources/short-url.qr.preview_unavailable') }}
        </p>
    @endif
</div>
