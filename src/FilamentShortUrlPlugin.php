<?php

namespace JeffersonGoncalves\FilamentShortUrl;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource;

class FilamentShortUrlPlugin implements Plugin
{
    protected string $resource = ShortUrlResource::class;

    protected ?string $navigationGroup = null;

    public function getId(): string
    {
        return 'filament-short-url';
    }

    public function register(Panel $panel): void
    {
        if ($this->navigationGroup !== null) {
            ShortUrlResource::setNavigationGroup($this->navigationGroup);
        }

        $panel->resources([
            $this->resource,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function resource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }
}
