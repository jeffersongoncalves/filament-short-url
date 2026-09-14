<div x-data="{ registrar: 'cloudflare' }" class="fi-su-dns">
    <div class="fi-su-dns-group">
        <p class="fi-su-dns-group-title">
            {{ __('filament-short-url::resources/custom-domain.dns.option_txt') }}
        </p>
        <div class="fi-su-dns-table">
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.type') }}</span>
            <span class="fi-su-dns-value">TXT</span>
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.host') }}</span>
            <span
                class="fi-su-dns-value fi-su-dns-value--copyable"
                x-data="{ copied: false }"
                x-on:click="navigator.clipboard.writeText('{{ $txtHost }}'); copied = true; setTimeout(() => copied = false, 1500)"
                :title="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}'"
                x-text="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ $txtHost }}'"
            ></span>
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.value') }}</span>
            <span
                class="fi-su-dns-value fi-su-dns-value--copyable"
                x-data="{ copied: false }"
                x-on:click="navigator.clipboard.writeText('{{ $txtValue }}'); copied = true; setTimeout(() => copied = false, 1500)"
                :title="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}'"
                x-text="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ $txtValue }}'"
            ></span>
        </div>
    </div>

    <div class="fi-su-dns-group">
        <p class="fi-su-dns-group-title">
            {{ __('filament-short-url::resources/custom-domain.dns.option_cname') }}
        </p>
        <div class="fi-su-dns-table">
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.type') }}</span>
            <span class="fi-su-dns-value">CNAME</span>
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.host') }}</span>
            <span
                class="fi-su-dns-value fi-su-dns-value--copyable"
                x-data="{ copied: false }"
                x-on:click="navigator.clipboard.writeText('{{ $domain->domain }}'); copied = true; setTimeout(() => copied = false, 1500)"
                :title="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}'"
                x-text="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ $domain->domain }}'"
            ></span>
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.value') }}</span>
            <span
                class="fi-su-dns-value fi-su-dns-value--copyable"
                x-data="{ copied: false }"
                x-on:click="navigator.clipboard.writeText('{{ $cnameTarget }}'); copied = true; setTimeout(() => copied = false, 1500)"
                :title="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}'"
                x-text="copied ? '{{ __('filament-short-url::resources/custom-domain.dns.copied') }}' : '{{ $cnameTarget }}'"
            ></span>
        </div>
    </div>

    <div class="fi-su-dns-group">
        <div class="fi-su-dns-tabs">
            @foreach ($registrars as $key => $label)
                <button
                    type="button"
                    x-on:click="registrar = '{{ $key }}'"
                    :class="{ 'fi-active': registrar === '{{ $key }}' }"
                    class="fi-su-dns-tab"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        @foreach ($registrars as $key => $label)
            <p x-show="registrar === '{{ $key }}'" x-cloak class="fi-su-dns-hint">
                {{ __("filament-short-url::resources/custom-domain.dns.registrar_hint_{$key}") }}
            </p>
        @endforeach
    </div>
</div>
