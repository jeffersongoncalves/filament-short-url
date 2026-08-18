<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use JeffersonGoncalves\LaravelShortUrl\Registries\PixelProviderRegistry;

class PixelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label(__('filament-short-url::resources/pixel.fields.name'))
                ->required()
                ->maxLength(255),

            Select::make('provider_key')
                ->label(__('filament-short-url::resources/pixel.fields.provider'))
                ->options(fn (): array => collect(app(PixelProviderRegistry::class)->all())
                    ->mapWithKeys(fn ($provider, $key) => [$key => $provider->label])
                    ->all())
                ->live()
                ->required(),

            ...static::configFields(),
        ]);
    }

    /**
     * @return array<int, TextInput>
     */
    protected static function configFields(): array
    {
        $maxFields = 6;

        return collect(range(0, $maxFields - 1))
            ->map(fn (int $index): TextInput => TextInput::make("config_field_{$index}")
                ->label(fn (Get $get): string => static::fieldAt($get('provider_key'), $index)['label'] ?? '')
                ->visible(fn (Get $get): bool => static::fieldAt($get('provider_key'), $index) !== null)
                ->dehydrated(fn (Get $get): bool => static::fieldAt($get('provider_key'), $index) !== null))
            ->all();
    }

    /**
     * @return array{key: string, label: string, type: string}|null
     */
    protected static function fieldAt(?string $providerKey, int $index): ?array
    {
        if (! $providerKey) {
            return null;
        }

        $provider = app(PixelProviderRegistry::class)->get($providerKey);

        return $provider?->configFields[$index] ?? null;
    }
}
