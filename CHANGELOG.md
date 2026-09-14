# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.10...HEAD)

### Added

- `ShortUrlResource` (Filament v5) for `jeffersongoncalves/laravel-short-url`: list, create and edit pages covering the F1 field set.
- `FilamentShortUrlPlugin` with resource override and navigation group configuration.

## [3.5.10](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.9...3.5.10) - 2026-09-14

Make DNS instructions Host column copyable (#22).

## [3.5.9](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.8...3.5.9) - 2026-09-12

chore: require jeffersongoncalves/laravel-short-url ^5.1

## [3.5.8](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.7...3.5.8) - 2026-09-12

fix: dependabot npm ecosystem coverage for bun.lock

## [3.5.7](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.6...3.5.7) - 2026-09-11

**Full Changelog**: https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.6...3.5.7

## [3.5.6](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.5...3.5.6) - 2026-09-11

**Full Changelog**: https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.5...3.5.6

## [3.5.5](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.4...3.5.5) - 2026-09-11

**Full Changelog**: https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.4...3.5.5

## [3.5.4](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.3...3.5.4) - 2026-09-11

Fixed: MetricsPage now has an explicit slug (short-url-metrics) instead of Filament's default (metrics-page) — collided with jeffersongoncalves/filament-page-visits' own MetricsPage in a host panel installing both.

## [3.5.3](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.2...3.5.3) - 2026-09-10

fix: use StatsAggregator's real no-scope path for global stats; require jeffersongoncalves/laravel-short-url ^4.4.5 — closes #17

## [3.5.2](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.1...3.5.2) - 2026-09-10

fix: disable Filament's default 5s polling on stats/dashboard widgets — closes #16

## [3.5.1](https://github.com/jeffersongoncalves/filament-short-url/compare/3.5.0...3.5.1) - 2026-09-10

fix: memoize StatsPayload per request to avoid duplicate aggregation across widgets; require jeffersongoncalves/laravel-short-url ^4.4.2 (fixes duplicate today aggregation) — closes #15

## [3.5.0](https://github.com/jeffersongoncalves/filament-short-url/compare/3.4.0...3.5.0) - 2026-09-04

### What's Changed

* feat: add QR code table action for short urls by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-short-url/pull/5
* fix: guard QR code modal against a missing endroid/qr-code or GD by @jeffersongoncalves in https://github.com/jeffersongoncalves/filament-short-url/pull/8

**Full Changelog**: https://github.com/jeffersongoncalves/filament-short-url/compare/3.4.0...3.5.0

## [3.4.0](https://github.com/jeffersongoncalves/filament-short-url/compare/3.3.0...3.4.0) - 2026-08-23

### Changed

- Requires `jeffersongoncalves/laravel-short-url` ^4.0, which resolves [#3](https://github.com/jeffersongoncalves/filament-short-url/issues/3) at the core level: `Contracts\TenantResolver`/`Contracts\PlanResolver` replace the old `short-url.tenancy.plan_resolver` config Closure (incompatible with `config:cache`), so multi-tenant setups not using stancl/tenancy can bind their own resolver. No code changes needed in this package — `UsageOverview` only calls `PlanLimits::limit()`/`currentPlan()`, whose signatures are unchanged.

## [3.3.0](https://github.com/jeffersongoncalves/filament-short-url/compare/3.2.1...3.3.0) - 2026-08-23

### Changed

- Requires `jeffersongoncalves/laravel-short-url` ^3.0. That release changes `route.fallback`'s default to `true` and registers the redirect route from an `app()->booted()` callback, so it no longer shadows the host app's own single-segment routes (e.g. `/about`). No code changes needed in this package.

## [3.2.1](https://github.com/jeffersongoncalves/filament-short-url/compare/3.2.0...3.2.1) - 2026-08-23

### Fixed

- Removed single-letter row-action keybindings (S/I/E/X on Statistics/Copy/Edit/Delete) from the Short URLs table. Filament renders keyBindings() as global shortcuts that fire even while a text input has focus, so those letters were unusable in the table's own search box.

## [3.2.0](https://github.com/jeffersongoncalves/filament-short-url/compare/3.1.0...3.2.0) - 2026-08-23

### Added

- `GlobalCountryBreakdown`/`GlobalCityBreakdown` widgets on the global Metrics dashboard, showing country/city traffic across every link (previously geo breakdown was only on each link's own Statistics page).

## [3.1.0](https://github.com/jeffersongoncalves/filament-short-url/compare/3.0.0...3.1.0) - 2026-08-23

### Added

- `hideImport()`/`isImportHidden()` on `FilamentShortUrlPlugin` to remove the Import Links page from the panel.
- Hiding Pixels via `hidePixels()` now also removes the Pixels resource from the sidebar (previously it only hid the form section).

## [3.0.0](https://github.com/jeffersongoncalves/filament-short-url/compare/3.0.0...3.0.0) - 2026-08-22

### What's Changed

* build(deps): bump the all-actions group across 1 directory with 2 updates by @dependabot[bot] in https://github.com/jeffersongoncalves/filament-short-url/pull/1

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/jeffersongoncalves/filament-short-url/pull/1

**Full Changelog**: https://github.com/jeffersongoncalves/filament-short-url/commits/3.0.0
