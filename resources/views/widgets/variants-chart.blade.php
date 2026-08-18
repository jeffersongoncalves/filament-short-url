<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        @if ($variants === [])
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('filament-short-url::resources/short-url.stats.no_data') }}
            </p>
        @else
            <div class="space-y-3">
                @foreach ($variants as $label => $count)
                    @php
                        $clickShare = (int) round($count / $total * 100);
                        $weight = $weights[$label] ?? null;
                        $z = $significance[$label] ?? null;
                    @endphp
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm">
                            <span class="font-medium text-gray-950 dark:text-white">{{ $label !== '' ? $label : __('filament-short-url::resources/short-url.stats.unknown') }}</span>
                            <span class="text-gray-500 dark:text-gray-400">
                                {{ __('filament-short-url::resources/short-url.stats.click_share') }}: {{ $clickShare }}%
                                @if ($weight !== null)
                                    · {{ __('filament-short-url::resources/short-url.stats.weight') }}: {{ $weight }}%
                                @endif
                                @if ($z !== null)
                                    · z={{ number_format($z, 2) }}
                                    @if (abs($z) >= 1.96)
                                        <span class="ml-1 rounded bg-success-100 px-1.5 py-0.5 text-xs font-medium text-success-700 dark:bg-success-500/20 dark:text-success-400">
                                            {{ __('filament-short-url::resources/short-url.stats.significant') }}
                                        </span>
                                    @endif
                                @endif
                            </span>
                        </div>
                        <div class="relative h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                            <span class="absolute inset-y-0 left-0 block rounded-full bg-primary-500" style="width: {{ max(2, $clickShare) }}%"></span>
                            @if ($weight !== null)
                                <span class="absolute inset-y-0 border-l-2 border-gray-400 dark:border-gray-300" style="left: {{ $weight }}%"></span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
