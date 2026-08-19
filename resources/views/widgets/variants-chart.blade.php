<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        @if ($variants === [])
            <p class="fi-su-empty-text">
                {{ __('filament-short-url::resources/short-url.stats.no_data') }}
            </p>
        @else
            <div class="fi-su-variants-list">
                @foreach ($variants as $label => $count)
                    @php
                        $clickShare = (int) round($count / $total * 100);
                        $weight = $weights[$label] ?? null;
                        $z = $significance[$label] ?? null;
                    @endphp
                    <div>
                        <div class="fi-su-variant-header">
                            <span class="fi-su-variant-label">{{ $label !== '' ? $label : __('filament-short-url::resources/short-url.stats.unknown') }}</span>
                            <span class="fi-su-variant-meta">
                                {{ __('filament-short-url::resources/short-url.stats.click_share') }}: {{ $clickShare }}%
                                @if ($weight !== null)
                                    · {{ __('filament-short-url::resources/short-url.stats.weight') }}: {{ $weight }}%
                                @endif
                                @if ($z !== null)
                                    · z={{ number_format($z, 2) }}
                                    @if (abs($z) >= 1.96)
                                        <span class="fi-su-variant-badge-significant">
                                            {{ __('filament-short-url::resources/short-url.stats.significant') }}
                                        </span>
                                    @endif
                                @endif
                            </span>
                        </div>
                        <div class="fi-su-variant-bar-track">
                            <span class="fi-su-variant-bar-fill" style="width: {{ max(2, $clickShare) }}%"></span>
                            @if ($weight !== null)
                                <span class="fi-su-variant-bar-marker" style="left: {{ $weight }}%"></span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
