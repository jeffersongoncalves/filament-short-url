<div class="filament-hidden">

![Filament Short URL](https://raw.githubusercontent.com/jeffersongoncalves/filament-short-url/1.x/art/jeffersongoncalves-filament-short-url.png)

</div>

# Filament Short URL

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-short-url.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-short-url)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-short-url/tests.yml?branch=1.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-short-url/actions?query=workflow%3Atests+branch%3A1.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-short-url/fix-php-code-style-issues.yml?branch=1.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-short-url/actions?query=workflow%3A%22Fix+PHP+code+style+issues%22+branch%3A1.x)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-short-url.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-short-url)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-short-url.svg?style=flat-square)](LICENSE.md)

A complete Filament v3 admin layer for
[`jeffersongoncalves/laravel-short-url`](https://github.com/jeffersongoncalves/laravel-short-url) — the headless
core package that owns the models, migrations, redirect pipeline, tracking and every business rule. This package
duplicates none of that: it's the presentation layer (Resources, Pages and Filament components) built on top of
the core's Facade and contracts.

## Compatibility

| `filament-short-url` | `laravel-short-url` | Filament |
| --- | --- | --- |
| `1.x` | `^2.0` | `v3` |
| `2.x` | `^2.0` | `v4` |
| `3.x` | `^2.0` | `v5` |

## What's included

- **Short URLs** — full CRUD, rule-based targeting (a Rule Builder driven dynamically by the core's
  `FilterTypeRegistry`), A/B split, password/warning-page/Safe Browsing, granular tracking toggles, a
  bidirectional UTM Builder (template application + per-field requirement via `short-url.utm.required`), pixels.
- **Custom Domains** — DNS verification (TXT/CNAME), per-registrar instructions.
- **Folders**, **Tags**, **Pixels** — organization and integrations, generated dynamically from the core's own
  registries (`PixelProviderRegistry`, etc.) — registering something new in the core makes it show up here
  without touching this package.
- **Import** — CSV and Bitly drivers (from the core), with a dry-run preview before importing.
- **Metrics** — a dedicated page with totals, plan usage vs. limit, and a medium/source/campaign breakdown,
  rendering the core's `StatsPayload`/`StatsAggregator` exclusively (no stats math happens in this package).
- **Per-link Statistics** — a detail page (`ShortUrlResource`'s `statistics` action/route) with a date-range
  filter and widgets for hourly traffic, devices, browsers, operating systems, countries, cities, referrer
  types, languages, the UTM funnel and A/B variant performance. Hidden together with the Metrics page by
  `hideStatistics()`.
- **Settings** — a page whose tabs are generated dynamically from the core's `SettingsRepository::schema()`.
- Every Resource and Page shares the same navigation group (configurable, with a translated fallback).
- pt_BR, en and es translations.

## Screenshots

<!-- SCREENSHOTS -->
| Screenshot | Light | Dark |
|---|---|---|
| Customdomain list | ![customdomain-list](screenshots/light/customdomain-list.png) | ![customdomain-list](screenshots/dark/customdomain-list.png) |
| Customdomain create | ![customdomain-create](screenshots/light/customdomain-create.png) | ![customdomain-create](screenshots/dark/customdomain-create.png) |
| Customdomain edit | ![customdomain-edit](screenshots/light/customdomain-edit.png) | ![customdomain-edit](screenshots/dark/customdomain-edit.png) |
| Folder list | ![folder-list](screenshots/light/folder-list.png) | ![folder-list](screenshots/dark/folder-list.png) |
| Folder create | ![folder-create](screenshots/light/folder-create.png) | ![folder-create](screenshots/dark/folder-create.png) |
| Folder edit | ![folder-edit](screenshots/light/folder-edit.png) | ![folder-edit](screenshots/dark/folder-edit.png) |
| Pixel list | ![pixel-list](screenshots/light/pixel-list.png) | ![pixel-list](screenshots/dark/pixel-list.png) |
| Pixel create | ![pixel-create](screenshots/light/pixel-create.png) | ![pixel-create](screenshots/dark/pixel-create.png) |
| Pixel edit | ![pixel-edit](screenshots/light/pixel-edit.png) | ![pixel-edit](screenshots/dark/pixel-edit.png) |
| Shorturl list | ![shorturl-list](screenshots/light/shorturl-list.png) | ![shorturl-list](screenshots/dark/shorturl-list.png) |
| Shorturl create | ![shorturl-create](screenshots/light/shorturl-create.png) | ![shorturl-create](screenshots/dark/shorturl-create.png) |
| Shorturl edit | ![shorturl-edit](screenshots/light/shorturl-edit.png) | ![shorturl-edit](screenshots/dark/shorturl-edit.png) |
| Tag list | ![tag-list](screenshots/light/tag-list.png) | ![tag-list](screenshots/dark/tag-list.png) |
| Tag create | ![tag-create](screenshots/light/tag-create.png) | ![tag-create](screenshots/dark/tag-create.png) |
| Tag edit | ![tag-edit](screenshots/light/tag-edit.png) | ![tag-edit](screenshots/dark/tag-edit.png) |
| Import page | ![import-page](screenshots/light/import-page.png) | ![import-page](screenshots/dark/import-page.png) |
<!-- SCREENSHOTS -->

## Installation

### 1. Install the package

```bash
composer require jeffersongoncalves/filament-short-url:"^1.0"
```

This pulls in `jeffersongoncalves/laravel-short-url` (`^2.0`) as a dependency.

### 2. (Optional) Install dependencies for optional core features

| Feature | Dependency |
| --- | --- |
| Automatic multi-tenancy | `composer require stancl/tenancy` |
| GeoIP via MaxMind | `composer require geoip2/geoip2` |

Without them, the corresponding features degrade gracefully — none of these are ever required.

### 3. Publish and run the core's migrations

> `laravel-short-url`'s migrations ship as `.php.stub` files inside the package — they are **not** auto-loaded,
> they need to be published first.

```bash
php artisan vendor:publish --tag="short-url-config"
php artisan vendor:publish --tag="short-url-migrations"
php artisan migrate
```

(The tag is `short-url-*`, not `laravel-short-url-*` — the core package strips the `laravel-` prefix from its
own name when Spatie Package Tools registers publish tags.)

To publish everything at once (config, migrations, views, translations):

```bash
php artisan vendor:publish --provider="JeffersonGoncalves\LaravelShortUrl\LaravelShortUrlServiceProvider"
```

### 4. Register the plugin in your PanelProvider

```php
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentShortUrlPlugin::make()
                ->navigationGroup('Marketing')           // optional — defaults to a translated "Short URL"
                ->navigationLabel('Short Links')          // label for ShortUrlResource specifically
                ->navigationIcon('heroicon-o-link')
                ->navigationSort(50)
                ->wizardForm()                            // opt-in: Create AND Edit pages become a skippable step wizard
                ->hideSecurity()                          // drop the Security section (password/warning page)
                ->hideUtm()                                // drop the UTM Parameters section
                ->hidePixels()                             // drop the Pixels section
                ->hideTargeting()                          // drop rule-based/A-B split targeting — links become single-destination only
                ->hideFolders()                            // removes FolderResource + the folder filter/bulk action
                ->hideTags()                                // removes TagResource + the tags filter/bulk action
                ->authorizeUsing(fn () => auth()->user()->hasRole('admin'))
                ->authorizeSettingsUsing(fn () => auth()->user()->hasRole('admin'))
                ->resources([
                    \JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource::class,
                    // ...enable only the ones you want; see the full list below
                ])
                ->hideStatistics(),  // removes the Metrics page and the statistics action/column
        ]);
}
```

For installs that only need "shorten a link" — no security, UTM, pixels or targeting — chain
`->simpleMode()` instead of the individual `hideX()` calls above; it turns all of them on at once (pass `false` to turn
them all back off).

### 5. Publish Filament's assets

```bash
php artisan filament:assets
```

### 6. (Optional) Turn on optional core features via `.env`

Custom domains are a core feature that is off by default — the corresponding Resource (`CustomDomainResource`)
only shows up in navigation once the core has it enabled:

```env
SHORT_URL_DOMAINS_ENABLED=true
```

To require UTM parameters on every created link (e.g. track whether a link was shared by SMS, email, an
agent, ...):

```env
SHORT_URL_REQUIRED_UTM=utm_medium
```

This makes the field required in the Filament form itself, and is also enforced by `ShortUrlManager` on
create/update.

## Available Resources and Pages

| Class | What it is | Shown when |
| --- | --- | --- |
| `ShortUrlResource` | Short links | always |
| `CustomDomainResource` | Custom domains | `short-url.domains.enabled` |
| `PixelResource` | Conversion pixels | always |
| `FolderResource` / `TagResource` | Link organization | always |
| `SettingsPage` | Settings (core's schema) | always |
| `ImportPage` | CSV/Bitly import | always |
| `MetricsPage` | Global metrics | always, unless `hideStatistics()` |

## Authorization

Access to each Resource follows the same three-level chain:

1. **Plugin closure** — `authorizeUsing()`. If set, it decides on its own.
2. **Policy** registered for the corresponding model (via `Gate::policy()`), if one exists.
3. **Default `canViewAny()`** for the Resource (permissive by default).

`authorizeSettingsUsing()` follows the same fallback for `SettingsPage`, with an extra middle step: a `ShortUrl`
model Policy with a `manageSettings` method, if one is registered.

## Testing

```bash
composer test        # Pest
composer analyse      # PHPStan (Larastan)
composer format        # Pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
