<div x-data="{ registrar: 'cloudflare' }" class="space-y-4">
    <div class="space-y-2">
        <p class="text-sm font-medium text-gray-950 dark:text-white">
            {{ __('filament-short-url::resources/custom-domain.dns.option_txt') }}
        </p>
        <div class="grid grid-cols-3 gap-2 rounded-lg bg-gray-50 p-3 text-sm dark:bg-gray-800">
            <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('filament-short-url::resources/custom-domain.dns.type') }}</span>
            <span class="col-span-2 font-mono">TXT</span>
            <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('filament-short-url::resources/custom-domain.dns.host') }}</span>
            <span class="col-span-2 break-all font-mono">{{ $txtHost }}</span>
            <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('filament-short-url::resources/custom-domain.dns.value') }}</span>
            <span class="col-span-2 break-all font-mono" x-data x-on:click="navigator.clipboard.writeText('{{ $txtValue }}')" title="{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}">
                {{ $txtValue }}
            </span>
        </div>
    </div>

    <div class="space-y-2">
        <p class="text-sm font-medium text-gray-950 dark:text-white">
            {{ __('filament-short-url::resources/custom-domain.dns.option_cname') }}
        </p>
        <div class="grid grid-cols-3 gap-2 rounded-lg bg-gray-50 p-3 text-sm dark:bg-gray-800">
            <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('filament-short-url::resources/custom-domain.dns.type') }}</span>
            <span class="col-span-2 font-mono">CNAME</span>
            <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('filament-short-url::resources/custom-domain.dns.host') }}</span>
            <span class="col-span-2 break-all font-mono">{{ $domain->domain }}</span>
            <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('filament-short-url::resources/custom-domain.dns.value') }}</span>
            <span class="col-span-2 break-all font-mono" x-data x-on:click="navigator.clipboard.writeText('{{ $cnameTarget }}')" title="{{ __('filament-short-url::resources/custom-domain.dns.click_to_copy') }}">
                {{ $cnameTarget }}
            </span>
        </div>
    </div>

    <div class="space-y-2">
        <div class="flex gap-2 border-b border-gray-200 dark:border-gray-700">
            @foreach ($registrars as $key => $label)
                <button
                    type="button"
                    x-on:click="registrar = '{{ $key }}'"
                    :class="registrar === '{{ $key }}' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                    class="border-b-2 px-3 py-1.5 text-sm font-medium"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        @foreach ($registrars as $key => $label)
            <p x-show="registrar === '{{ $key }}'" x-cloak class="text-sm text-gray-600 dark:text-gray-300">
                {{ __("filament-short-url::resources/custom-domain.dns.registrar_hint_{$key}") }}
            </p>
        @endforeach
    </div>
</div>
