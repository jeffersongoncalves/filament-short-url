@php
    $available = class_exists(\Endroid\QrCode\Builder\Builder::class);
    $formats = ['svg' => 'SVG', 'png' => 'PNG', 'pdf' => 'PDF', 'eps' => 'EPS'];
@endphp

<div class="space-y-3">
    @if (! $available)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('filament-short-url::resources/short-url.qr.package_missing') }}
        </p>
    @else
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
            @foreach ($formats as $format => $label)
                <a
                    href="{{ route('short-url.qr', ['urlKey' => $record->url_key, 'format' => $format]) }}"
                    target="_blank"
                    rel="noopener"
                    class="flex items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    @endif
</div>
