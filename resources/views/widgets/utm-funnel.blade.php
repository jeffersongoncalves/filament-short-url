<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        <div class="fi-su-funnel-grid">
            @foreach (['source' => $source, 'medium' => $medium, 'campaign' => $campaign] as $key => $stats)
                <div>
                    <h4 class="fi-su-funnel-col-title">
                        {{ __("filament-short-url::resources/short-url.stats.utm_{$key}") }}
                    </h4>
                    @if ($stats === [])
                        <p class="fi-su-empty-text">
                            {{ __('filament-short-url::resources/short-url.stats.no_data') }}
                        </p>
                    @else
                        <ul class="fi-su-funnel-list">
                            @foreach ($stats as $label => $count)
                                <li class="fi-su-funnel-item">
                                    <span class="fi-su-funnel-label">{{ $label !== '' ? $label : __('filament-short-url::resources/short-url.stats.unknown') }}</span>
                                    <span class="fi-su-funnel-count">{{ $count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
