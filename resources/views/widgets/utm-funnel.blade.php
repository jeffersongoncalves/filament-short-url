<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach (['source' => $source, 'medium' => $medium, 'campaign' => $campaign] as $key => $stats)
                <div>
                    <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __("filament-short-url::resources/short-url.stats.utm_{$key}") }}
                    </h4>
                    @if ($stats === [])
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('filament-short-url::resources/short-url.stats.no_data') }}
                        </p>
                    @else
                        <ul class="space-y-1">
                            @foreach ($stats as $label => $count)
                                <li class="flex items-center justify-between text-sm">
                                    <span class="truncate text-gray-700 dark:text-gray-200">{{ $label !== '' ? $label : __('filament-short-url::resources/short-url.stats.unknown') }}</span>
                                    <span class="font-medium text-gray-950 dark:text-white">{{ $count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
