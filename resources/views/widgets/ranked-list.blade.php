<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        @if ($stats === [])
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('filament-short-url::resources/short-url.stats.no_data') }}
            </p>
        @else
            <ul class="space-y-2">
                @foreach ($stats as $label => $count)
                    <li class="flex items-center gap-3">
                        <span class="w-28 shrink-0 truncate text-sm text-gray-700 dark:text-gray-200">
                            @if (! empty($flags[$label] ?? null))
                                <span>{{ $flags[$label] }}</span>
                            @endif
                            {{ $label !== '' ? $label : __('filament-short-url::resources/short-url.stats.unknown') }}
                        </span>
                        <span class="h-2 flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                            <span
                                class="block h-full rounded-full bg-primary-500"
                                style="width: {{ $max > 0 ? max(4, (int) round($count / $max * 100)) : 0 }}%"
                            ></span>
                        </span>
                        <span class="w-12 shrink-0 text-right text-sm font-medium text-gray-950 dark:text-white">
                            {{ $count }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
