<x-filament-widgets::widget>
    <x-filament::section :heading="__('filament-short-url::resources/short-url.security.audit_log')">
        @if ($logs->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('filament-short-url::resources/short-url.security.audit_log_empty') }}
            </p>
        @else
            <ul class="space-y-3">
                @foreach ($logs as $log)
                    <li class="flex items-start gap-3 text-sm">
                        <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-primary-500"></span>
                        <div>
                            <p class="font-medium text-gray-950 dark:text-white">{{ $log->event }}</p>
                            <p class="text-gray-500 dark:text-gray-400">
                                {{ $log->created_at?->toDayDateTimeString() }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
