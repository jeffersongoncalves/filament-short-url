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
                    if (str) return `<span class=&quot;${colon ? 'fi-su-json-key' : 'fi-su-json-string'}&quot;>${str}</span>${colon ?? ''}`
                    if (bool) return `<span class=&quot;fi-su-json-bool&quot;>${bool}</span>`
                    if (num) return `<span class=&quot;fi-su-json-num&quot;>${num}</span>`
                    return match
                })
        },
    }"
    class="fi-su-payload"
>
    <button
        type="button"
        x-on:click="copy()"
        class="fi-su-payload-copy-btn"
    >
        <span x-show="! copied">{{ __('filament-short-url::resources/webhook.deliveries.copy') }}</span>
        <span x-show="copied" x-cloak>{{ __('filament-short-url::resources/webhook.deliveries.copied') }}</span>
    </button>

    <pre
        class="fi-su-payload-pre"
        x-html="highlight(@js($json))"
    ></pre>
</div>
