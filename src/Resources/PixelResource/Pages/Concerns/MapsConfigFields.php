<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\Concerns;

use JeffersonGoncalves\LaravelShortUrl\Registries\PixelProviderRegistry;

trait MapsConfigFields
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function packConfigFields(array $data): array
    {
        $provider = app(PixelProviderRegistry::class)->get($data['provider_key'] ?? '');
        $config = [];

        foreach ($provider === null ? [] : $provider->configFields as $index => $field) {
            $config[$field['key']] = $data["config_field_{$index}"] ?? null;
            unset($data["config_field_{$index}"]);
        }

        $data['config'] = $config;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function unpackConfigFields(array $data): array
    {
        $provider = app(PixelProviderRegistry::class)->get($data['provider_key'] ?? '');
        $config = $data['config'] ?? [];

        foreach ($provider === null ? [] : $provider->configFields as $index => $field) {
            $data["config_field_{$index}"] = $config[$field['key']] ?? null;
        }

        return $data;
    }
}
