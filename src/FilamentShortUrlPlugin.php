<?php

namespace JeffersonGoncalves\Filament\ShortUrl;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use JeffersonGoncalves\Filament\ShortUrl\Pages\ImportPage;
use JeffersonGoncalves\Filament\ShortUrl\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ShortUrl\Pages\SettingsPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource;

class FilamentShortUrlPlugin implements Plugin
{
    /** @var array<class-string> */
    protected array $resources = [
        ShortUrlResource::class,
        CustomDomainResource::class,
        PixelResource::class,
        FolderResource::class,
        TagResource::class,
    ];

    protected ?string $navigationGroup = null;

    protected ?string $navigationLabel = null;

    protected ?string $navigationIcon = null;

    protected ?int $navigationSort = null;

    protected ?Closure $authorizeUsing = null;

    protected ?Closure $authorizeSettingsUsing = null;

    protected bool $statisticsHidden = false;

    protected bool $wizardForm = false;

    protected bool $securityHidden = false;

    protected bool $utmHidden = false;

    protected bool $pixelsHidden = false;

    protected bool $targetingHidden = false;

    protected bool $foldersHidden = false;

    protected bool $tagsHidden = false;

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
                FolderResource::class => ! $this->foldersHidden,
                TagResource::class => ! $this->tagsHidden,
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
        $id = app(static::class)->getId();

        // Filament v3's global `filament()`/`getPlugin()` helper only looks at
        // the "current" panel, which isn't set yet while a panel is still
        // registering itself (eg. Resource::getPages() runs eagerly during
        // Panel::register(), before Filament::setCurrentPanel() is called).
        // Falling back to the default panel mirrors what later Filament
        // versions do natively via getCurrentOrDefaultPanel().
        $panel = Filament::getCurrentPanel() ?? Filament::getDefaultPanel();

        /** @var static $plugin */
        $plugin = $panel->getPlugin($id);

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

    public function navigationIcon(?string $icon): static
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

    public function wizardForm(bool $enabled = true): static
    {
        $this->wizardForm = $enabled;

        return $this;
    }

    public function isWizardFormEnabled(): bool
    {
        return $this->wizardForm;
    }

    public function hideSecurity(bool $hidden = true): static
    {
        $this->securityHidden = $hidden;

        return $this;
    }

    public function isSecurityHidden(): bool
    {
        return $this->securityHidden;
    }

    /**
     * Hides the UTM Parameters form section. If `short-url.utm.required`
     * lists required UTM fields, keep this off — the field would still be
     * enforced server-side by ShortUrlManager with no way to fill it in.
     */
    public function hideUtm(bool $hidden = true): static
    {
        $this->utmHidden = $hidden;

        return $this;
    }

    public function isUtmHidden(): bool
    {
        return $this->utmHidden;
    }

    public function hidePixels(bool $hidden = true): static
    {
        $this->pixelsHidden = $hidden;

        return $this;
    }

    public function isPixelsHidden(): bool
    {
        return $this->pixelsHidden;
    }

    /**
     * Hides rule-based/A-B split targeting (destination_type, targeting_rules,
     * rotation_variants) — every link is a plain single-destination redirect.
     */
    public function hideTargeting(bool $hidden = true): static
    {
        $this->targetingHidden = $hidden;

        return $this;
    }

    public function isTargetingHidden(): bool
    {
        return $this->targetingHidden;
    }

    public function hideFolders(bool $hidden = true): static
    {
        $this->foldersHidden = $hidden;

        return $this;
    }

    public function isFoldersHidden(): bool
    {
        return $this->foldersHidden;
    }

    public function hideTags(bool $hidden = true): static
    {
        $this->tagsHidden = $hidden;

        return $this;
    }

    public function isTagsHidden(): bool
    {
        return $this->tagsHidden;
    }

    /**
     * Convenience toggle: hides every optional/advanced form section
     * (security, UTM, pixels, rule/split targeting) in one call, leaving
     * just the essentials and tracking toggles — for installs that only
     * need "shorten a link".
     */
    public function simpleMode(bool $enabled = true): static
    {
        $this->hideSecurity($enabled);
        $this->hideUtm($enabled);
        $this->hidePixels($enabled);
        $this->hideTargeting($enabled);

        return $this;
    }
}
