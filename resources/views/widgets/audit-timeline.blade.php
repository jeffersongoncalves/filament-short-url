<x-filament-widgets::widget>
    <x-filament::section :heading="__('filament-short-url::resources/short-url.security.audit_log')">
        @if ($logs->isEmpty())
            <p class="fi-su-audit-empty">
                {{ __('filament-short-url::resources/short-url.security.audit_log_empty') }}
            </p>
        @else
            <ul class="fi-su-audit-list">
                @foreach ($logs as $log)
                    <li class="fi-su-audit-item">
                        <span class="fi-su-audit-dot"></span>
                        <div>
                            <p class="fi-su-audit-event">{{ $log->event }}</p>
                            <p class="fi-su-audit-time">
                                {{ $log->created_at?->toDayDateTimeString() }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
