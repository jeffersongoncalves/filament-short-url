<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        @if ($stats === [])
            <p class="fi-su-empty-text">
                {{ __('filament-short-url::resources/short-url.stats.no_data') }}
            </p>
        @else
            <ul class="fi-su-ranked-list">
                @foreach ($stats as $label => $count)
                    <li class="fi-su-ranked-item">
                        <span class="fi-su-ranked-label">
                            @if (! empty($flags[$label] ?? null))
                                <span>{{ $flags[$label] }}</span>
                            @endif
                            {{ $label !== '' ? $label : __('filament-short-url::resources/short-url.stats.unknown') }}
                        </span>
                        <span class="fi-su-ranked-bar-track">
                            <span
                                class="fi-su-ranked-bar-fill"
                                style="width: {{ $max > 0 ? max(4, (int) round($count / $max * 100)) : 0 }}%"
                            ></span>
                        </span>
                        <span class="fi-su-ranked-count">
                            {{ $count }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
