@php
    $value = $getState();
    $carbon = $value ? \Illuminate\Support\Carbon::parse($value) : null;
@endphp

@if (! $carbon)
    <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
@else
    <div
        x-data="{ open: false }"
        class="relative inline-block"
    >
        <button
            type="button"
            x-on:mouseenter="open = true"
            x-on:mouseleave="open = false"
            x-on:click="open = ! open"
            class="rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200"
        >
            {{ $carbon->diffForHumans(short: true) }}
        </button>

        <div
            x-show="open"
            x-cloak
            x-transition
            class="absolute z-10 mt-1 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-xs text-white shadow-lg dark:bg-gray-700"
        >
            {{ $carbon->toDayDateTimeString() }} ({{ $carbon->timezoneName }})
        </div>
    </div>
@endif
