<?php

namespace JeffersonGoncalves\Filament\ShortUrl;

use BackedEnum;
use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\Filament\ShortUrl\Pages\ImportPage;
use JeffersonGoncalves\Filament\ShortUrl\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ShortUrl\Pages\SettingsPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource;

class FilamentShortUrlPlugin implements Plugin
{
    /** @var array<class-string> */
    protected array $resources = [
        ShortUrlResource::class,
        CustomDomainResource::class,
        ApiKeyResource::class,
        WebhookResource::class,
        PixelResource::class,
        FolderResource::class,
        TagResource::class,
        BioPageResource::class,
    ];

    protected ?string $navigationGroup = null;

    protected ?string $navigationLabel = null;

    protected string|BackedEnum|null $navigationIcon = null;

    protected ?int $navigationSort = null;

    protected ?Closure $authorizeUsing = null;

    protected ?Closure $authorizeSettingsUsing = null;

    protected bool $statisticsHidden = false;

    protected bool $bioPagesHidden = false;

    public function getId(): string
    {
        return 'filament-short-url';
    }

    public function register(Panel $panel): void
    {
        if ($this->navigationLabel !== null) {
            ShortUrlResource::setNavigationLabel($this->navigationLabel);
        }

        if ($this->navigationIcon !== null) {
            ShortUrlResource::setNavigationIcon($this->navigationIcon);
        }

        if ($this->navigationSort !== null) {
            ShortUrlResource::setNavigationSort($this->navigationSort);
        }

        $resources = array_values(array_filter(
            $this->resources,
            fn (string $resource): bool => match ($resource) {
                CustomDomainResource::class => (bool) config('short-url.domains.enabled', false),
                ApiKeyResource::class => (bool) config('short-url.api.enabled', false),
                BioPageResource::class => (bool) config('short-url.bio.enabled', false) && ! $this->bioPagesHidden,
                default => true,
            },
        ));

        $pages = [SettingsPage::class, ImportPage::class];

        if (! $this->statisticsHidden) {
            $pages[] = MetricsPage::class;
        }

        $panel->resources($resources);
        $panel->pages($pages);
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

    /**
     * @param  array<class-string>  $resources
     */
    public function resources(array $resources): static
    {
        $this->resources = $resources;

        return $this;
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup ?? __('filament-short-url::resources/short-url.navigation.group');
    }

    public function navigationLabel(?string $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function navigationIcon(string|BackedEnum|null $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function navigationSort(?int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function authorizeUsing(?Closure $callback): static
    {
        $this->authorizeUsing = $callback;

        return $this;
    }

    public function getAuthorizeUsing(): ?Closure
    {
        return $this->authorizeUsing;
    }

    public function authorizeSettingsUsing(?Closure $callback): static
    {
        $this->authorizeSettingsUsing = $callback;

        return $this;
    }

    public function getAuthorizeSettingsUsing(): ?Closure
    {
        return $this->authorizeSettingsUsing;
    }

    public function hideStatistics(bool $hidden = true): static
    {
        $this->statisticsHidden = $hidden;

        return $this;
    }

    public function isStatisticsHidden(): bool
    {
        return $this->statisticsHidden;
    }

    public function hideBioPages(bool $hidden = true): static
    {
        $this->bioPagesHidden = $hidden;

        return $this;
    }

    public function isBioPagesHidden(): bool
    {
        return $this->bioPagesHidden;
    }
}
