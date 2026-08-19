<div x-data="{ registrar: 'cloudflare' }" class="fi-su-dns">
    <div class="fi-su-dns-group">
        <p class="fi-su-dns-group-title">
            {{ __('filament-short-url::resources/custom-domain.dns.option_txt') }}
        </p>
        <div class="fi-su-dns-table">
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.type') }}</span>
            <span class="fi-su-dns-value">TXT</span>
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.host') }}</span>
            <span class="fi-su-dns-value">{{ $txtHost }}</span>
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.value') }}</span>
            <span class="fi-su-dns-value fi-su-dns-value--copyable" x-data x-on:click="navigator.clipboard.writeText('{{ $txtValue }}')" title="{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}">
                {{ $txtValue }}
            </span>
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
            <span class="fi-su-dns-value">{{ $domain->domain }}</span>
            <span class="fi-su-dns-key">{{ __('filament-short-url::resources/custom-domain.dns.value') }}</span>
            <span class="fi-su-dns-value fi-su-dns-value--copyable" x-data x-on:click="navigator.clipboard.writeText('{{ $cnameTarget }}')" title="{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}">
                {{ $cnameTarget }}
            </span>
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
