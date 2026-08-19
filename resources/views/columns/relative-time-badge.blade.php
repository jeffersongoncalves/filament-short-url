@php
    $value = $getState();
    $carbon = $value ? \Illuminate\Support\Carbon::parse($value) : null;
@endphp

@if (! $carbon)
    <span class="fi-su-time-badge-empty">—</span>
@else
    <div
        x-data="{ open: false }"
        class="fi-su-time-badge-wrap"
    >
        <button
            type="button"
            x-on:mouseenter="open = true"
            x-on:mouseleave="open = false"
            x-on:click="open = ! open"
            class="fi-su-time-badge"
        >
            {{ $carbon->diffForHumans(short: true) }}
        </button>

        <div
            x-show="open"
            x-cloak
            x-transition
            class="fi-su-time-badge-tooltip"
        >
            {{ $carbon->toDayDateTimeString() }} ({{ $carbon->timezoneName }})
        </div>
    </div>
@endif
