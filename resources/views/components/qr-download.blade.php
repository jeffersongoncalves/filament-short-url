@php
    $available = class_exists(\Endroid\QrCode\Builder\Builder::class);
    $formats = ['svg' => 'SVG', 'png' => 'PNG', 'pdf' => 'PDF', 'eps' => 'EPS'];
@endphp

<div class="fi-su-dns">
    @if (! $available)
        <p class="fi-su-qr-preview-empty">
            {{ __('filament-short-url::resources/short-url.qr.package_missing') }}
        </p>
    @else
        @include('filament-short-url::components.qr-preview', ['design' => $record->qr_design ?? [], 'previewUrl' => $record->fullUrl()])

        <div class="fi-su-qr-formats">
            @foreach ($formats as $format => $label)
                <a
                    href="{{ route('short-url.qr', ['urlKey' => $record->url_key, 'format' => $format]) }}"
                    target="_blank"
                    rel="noopener"
                    class="fi-su-qr-format-link"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    @endif
</div>
