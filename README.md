<div class="filament-hidden">

![Filament Short URL](https://raw.githubusercontent.com/jeffersongoncalves/filament-short-url/3.x/art/jeffersongoncalves-filament-short-url.png)

</div>

# Filament Short URL

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-short-url.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-short-url)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-short-url/tests.yml?branch=3.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-short-url/actions?query=workflow%3Atests+branch%3A3.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-short-url/fix-php-code-style-issues.yml?branch=3.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-short-url/actions?query=workflow%3A%22Fix+PHP+code+style+issues%22+branch%3A3.x)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-short-url.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-short-url)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-short-url.svg?style=flat-square)](LICENSE.md)

A complete Filament v5 admin layer for
[`jeffersongoncalves/laravel-short-url`](https://github.com/jeffersongoncalves/laravel-short-url) — the headless
core package that owns the models, migrations, redirect pipeline, tracking and every business rule. This package
duplicates none of that: it's the presentation layer (Resources, Pages and Filament components) built on top of
the core's Facade and contracts.

## Compatibility

| `filament-short-url` | `laravel-short-url` | Filament |
| --- | --- | --- |
| `3.x` | `^1.2` | `v5` |

## What's included

- **Short URLs** — full CRUD, rule-based targeting (a Rule Builder driven dynamically by the core's
  `FilterTypeRegistry`), A/B split, password/warning-page/Safe Browsing, granular tracking toggles, a
  bidirectional UTM Builder (template application + per-field requirement via `short-url.utm.required`), QR
  Designer, Deep Link Preview, pixels.
- **Custom Domains** — DNS verification (TXT/CNAME), per-registrar instructions.
- **API Keys** and **Webhooks** — key generation/rotation, delivery history with replay.
- **Folders**, **Tags**, **Pixels** — organization and integrations, generated dynamically from the core's own
  registries (`PixelProviderRegistry`, etc.) — registering something new in the core makes it show up here
  without touching this package.
- **Bio Pages** — block builder (link/text/image/video) via `Repeater::relationship()`.
- **Import** — CSV and Bitly drivers (from the core), with a dry-run preview before importing.
- **Metrics** — a dedicated page with totals, plan usage vs. limit, and a medium/source/campaign breakdown,
  rendering the core's `StatsPayload`/`StatsAggregator` exclusively (no stats math happens in this package).
- **Settings** — a page whose tabs are generated dynamically from the core's `SettingsRepository::schema()`.
- Every Resource and Page shares the same navigation group (configurable, with a translated fallback).
- pt_BR, en and es translations.

## Screenshots

<!-- SCREENSHOTS -->
| Screenshot | Light | Dark |
|---|---|---|
| Metrics page | ![metrics-page](screenshots/light/metrics-page.png) | ![metrics-page](screenshots/dark/metrics-page.png) |
| Settings page | ![settings-page](screenshots/light/settings-page.png) | ![settings-page](screenshots/dark/settings-page.png) |
| Import page | ![import-page](screenshots/light/import-page.png) | ![import-page](screenshots/dark/import-page.png) |
| API key list | ![apikey-list](screenshots/light/apikey-list.png) | ![apikey-list](screenshots/dark/apikey-list.png) |
| API key create | ![apikey-create](screenshots/light/apikey-create.png) | ![apikey-create](screenshots/dark/apikey-create.png) |
| API key edit | ![apikey-edit](screenshots/light/apikey-edit.png) | ![apikey-edit](screenshots/dark/apikey-edit.png) |
| Bio page list | ![biopage-list](screenshots/light/biopage-list.png) | ![biopage-list](screenshots/dark/biopage-list.png) |
| Bio page create | ![biopage-create](screenshots/light/biopage-create.png) | ![biopage-create](screenshots/dark/biopage-create.png) |
| Bio page edit | ![biopage-edit](screenshots/light/biopage-edit.png) | ![biopage-edit](screenshots/dark/biopage-edit.png) |
| Custom domain list | ![customdomain-list](screenshots/light/customdomain-list.png) | ![customdomain-list](screenshots/dark/customdomain-list.png) |
| Custom domain create | ![customdomain-create](screenshots/light/customdomain-create.png) | ![customdomain-create](screenshots/dark/customdomain-create.png) |
| Custom domain edit | ![customdomain-edit](screenshots/light/customdomain-edit.png) | ![customdomain-edit](screenshots/dark/customdomain-edit.png) |
| Folder list | ![folder-list](screenshots/light/folder-list.png) | ![folder-list](screenshots/dark/folder-list.png) |
| Folder create | ![folder-create](screenshots/light/folder-create.png) | ![folder-create](screenshots/dark/folder-create.png) |
| Folder edit | ![folder-edit](screenshots/light/folder-edit.png) | ![folder-edit](screenshots/dark/folder-edit.png) |
| Pixel list | ![pixel-list](screenshots/light/pixel-list.png) | ![pixel-list](screenshots/dark/pixel-list.png) |
| Pixel create | ![pixel-create](screenshots/light/pixel-create.png) | ![pixel-create](screenshots/dark/pixel-create.png) |
| Pixel edit | ![pixel-edit](screenshots/light/pixel-edit.png) | ![pixel-edit](screenshots/dark/pixel-edit.png) |
| Short URL list | ![shorturl-list](screenshots/light/shorturl-list.png) | ![shorturl-list](screenshots/dark/shorturl-list.png) |
| Short URL create | ![shorturl-create](screenshots/light/shorturl-create.png) | ![shorturl-create](screenshots/dark/shorturl-create.png) |
| Short URL edit | ![shorturl-edit](screenshots/light/shorturl-edit.png) | ![shorturl-edit](screenshots/dark/shorturl-edit.png) |
| Tag list | ![tag-list](screenshots/light/tag-list.png) | ![tag-list](screenshots/dark/tag-list.png) |
| Tag create | ![tag-create](screenshots/light/tag-create.png) | ![tag-create](screenshots/dark/tag-create.png) |
| Tag edit | ![tag-edit](screenshots/light/tag-edit.png) | ![tag-edit](screenshots/dark/tag-edit.png) |
| Webhook list | ![webhook-list](screenshots/light/webhook-list.png) | ![webhook-list](screenshots/dark/webhook-list.png) |
| Webhook create | ![webhook-create](screenshots/light/webhook-create.png) | ![webhook-create](screenshots/dark/webhook-create.png) |
| Webhook edit | ![webhook-edit](screenshots/light/webhook-edit.png) | ![webhook-edit](screenshots/dark/webhook-edit.png) |
<!-- SCREENSHOTS -->

## Installation

### 1. Install the package

```bash
composer require jeffersongoncalves/filament-short-url:"^3.0"
```

This pulls in `jeffersongoncalves/laravel-short-url` (`^1.2`) as a dependency.

### 2. (Optional) Install dependencies for optional core features

| Feature | Dependency |
| --- | --- |
| QR codes (SVG/PNG) | `composer require endroid/qr-code` |
| QR codes (PDF/EPS) | on top of the above, `composer require setasign/fpdf` |
| Automatic multi-tenancy | `composer require stancl/tenancy` |
| GeoIP via MaxMind | `composer require geoip2/geoip2` |

Without them, the corresponding features degrade gracefully (e.g. the QR Designer shows a notice instead of
crashing) — none of these are ever required.

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
                ->hideQrDesigner()                        // drop the QR Code Design section from the form
                ->hideDeepLinking()                       // drop the Deep Linking section from the form
                ->hideSecurity()                          // drop the Security section (password/warning page)
                ->hideUtm()                                // drop the UTM Parameters section
                ->hidePixels()                             // drop the Pixels section
                ->hideTargeting()                          // drop rule-based/A-B split targeting — links become single-destination only
                ->hideWebhooks()                           // removes WebhookResource entirely
                ->hideFolders()                            // removes FolderResource + the folder filter/bulk action
                ->hideTags()                                // removes TagResource + the tags filter/bulk action
                ->authorizeUsing(fn () => auth()->user()->hasRole('admin'))
                ->authorizeSettingsUsing(fn () => auth()->user()->hasRole('admin'))
                ->resources([
                    \JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource::class,
                    // ...enable only the ones you want; see the full list below
                ])
                ->hideStatistics()   // removes the Metrics page and the statistics action/column
                ->hideBioPages(),    // removes BioPageResource even if short-url.bio.enabled is true
        ]);
}
```

For installs that only need "shorten a link" — no QR, deep linking, security, UTM, pixels, targeting or webhooks — chain
`->simpleMode()` instead of the individual `hideX()` calls above; it turns all of them on at once (pass `false` to turn
them all back off).

### 5. Publish Filament's assets

```bash
php artisan filament:assets
```

### 6. (Optional) Turn on optional core features via `.env`

Custom domains, the REST API and Bio Pages are core features that are off by default — the corresponding
Resources (`CustomDomainResource`, `ApiKeyResource`, `BioPageResource`) only show up in navigation once the
core has them enabled:

```env
SHORT_URL_DOMAINS_ENABLED=true
SHORT_URL_API_ENABLED=true
SHORT_URL_BIO_ENABLED=true
```

To require UTM parameters on every created link (e.g. track whether a link was shared by SMS, email, an
agent, ...):

```env
SHORT_URL_REQUIRED_UTM=utm_medium
```

This makes the field required in the Filament form itself, and is also enforced by `ShortUrlManager` on
create/update (including through the core's own REST API, if enabled).

## Available Resources and Pages

| Class | What it is | Shown when |
| --- | --- | --- |
| `ShortUrlResource` | Short links | always |
| `CustomDomainResource` | Custom domains | `short-url.domains.enabled` |
| `ApiKeyResource` | REST API keys | `short-url.api.enabled` |
| `WebhookResource` | Webhooks + delivery history | always, unless `hideWebhooks()` |
| `PixelResource` | Conversion pixels | always |
| `FolderResource` / `TagResource` | Link organization | always |
| `BioPageResource` | Link-in-bio | `short-url.bio.enabled` and `! hideBioPages()` |
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
