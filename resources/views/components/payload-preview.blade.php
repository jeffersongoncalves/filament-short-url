@php
    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<div
    x-data="{
        copied: false,
        copy() {
            navigator.clipboard.writeText(@js($json));
            this.copied = true;
            setTimeout(() => this.copied = false, 1500);
        },
        highlight(json) {
            return json
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/(&quot;.*?&quot;)(\s*:)?|\b(true|false|null)\b|(-?\d+(?:\.\d*)?(?:[eE][+-]?\d+)?)/g, (match, str, colon, bool, num) => {
                    if (str) return `<span class=&quot;${colon ? 'text-sky-600 dark:text-sky-400' : 'text-emerald-600 dark:text-emerald-400'}&quot;>${str}</span>${colon ?? ''}`
                    if (bool) return `<span class=&quot;text-amber-600 dark:text-amber-400&quot;>${bool}</span>`
                    if (num) return `<span class=&quot;text-fuchsia-600 dark:text-fuchsia-400&quot;>${num}</span>`
                    return match
                })
        },
    }"
    class="relative"
>
    <button
        type="button"
        x-on:click="copy()"
        class="absolute right-2 top-2 rounded-md bg-gray-200 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
    >
        <span x-show="! copied">{{ __('filament-short-url::resources/webhook.deliveries.copy') }}</span>
        <span x-show="copied" x-cloak>{{ __('filament-short-url::resources/webhook.deliveries.copied') }}</span>
    </button>

    <pre
        class="max-h-96 overflow-auto rounded-lg bg-gray-50 p-4 text-xs leading-5 dark:bg-gray-900"
        x-html="highlight(@js($json))"
    ></pre>
</div>
