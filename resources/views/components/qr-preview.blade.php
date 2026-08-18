@php
    use JeffersonGoncalves\LaravelShortUrl\Contracts\QrCodeBuilder;
    use JeffersonGoncalves\LaravelShortUrl\Data\QrDesign;

    $svg = null;
    $unavailable = ! class_exists(\Endroid\QrCode\Builder\Builder::class);

    if (! $unavailable) {
        try {
            $qrDesign = new QrDesign(
                dotsStyle: $design['dotsStyle'],
                eyesStyle: $design['eyesStyle'],
                gradient: $design['gradient'],
                margin: $design['margin'],
                logoPath: $design['logoPath'] ? storage_path('app/public/'.$design['logoPath']) : null,
                logoSizePercent: $design['logoSizePercent'],
                errorCorrection: $design['errorCorrection'],
            );

            $svg = app()->makeWith(QrCodeBuilder::class, ['data' => 'https://short.example/preview'])
                ->design($qrDesign)
                ->toSvg();
        } catch (\Throwable $e) {
            $svg = null;
        }
    }
@endphp

<div class="flex items-center justify-center rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
    @if ($unavailable)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('filament-short-url::resources/short-url.qr.package_missing') }}
        </p>
    @elseif ($svg)
        <div class="h-48 w-48 [&_svg]:h-full [&_svg]:w-full">
            {!! $svg !!}
        </div>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('filament-short-url::resources/short-url.qr.preview_unavailable') }}
        </p>
    @endif
</div>
