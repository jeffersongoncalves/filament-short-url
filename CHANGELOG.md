# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/jeffersongoncalves/filament-short-url/compare/1.4.0...HEAD)

### Added

- `ShortUrlResource` (Filament v5) for `jeffersongoncalves/laravel-short-url`: list, create and edit pages covering the F1 field set.
- `FilamentShortUrlPlugin` with resource override and navigation group configuration.

## [1.4.0](https://github.com/jeffersongoncalves/filament-short-url/compare/1.3.0...1.4.0) - 2026-08-23

### Changed

- Requires `jeffersongoncalves/laravel-short-url` ^4.0, which resolves [#3](https://github.com/jeffersongoncalves/filament-short-url/issues/3) at the core level: `Contracts\TenantResolver`/`Contracts\PlanResolver` replace the old `short-url.tenancy.plan_resolver` config Closure (incompatible with `config:cache`), so multi-tenant setups not using stancl/tenancy can bind their own resolver. No code changes needed in this package — `UsageOverview` only calls `PlanLimits::limit()`/`currentPlan()`, whose signatures are unchanged.

## [1.3.0](https://github.com/jeffersongoncalves/filament-short-url/compare/1.2.1...1.3.0) - 2026-08-23

### Changed

- Requires `jeffersongoncalves/laravel-short-url` ^3.0. That release changes `route.fallback`'s default to `true` and registers the redirect route from an `app()->booted()` callback, so it no longer shadows the host app's own single-segment routes (e.g. `/about`). No code changes needed in this package.

## [1.2.1](https://github.com/jeffersongoncalves/filament-short-url/compare/1.2.0...1.2.1) - 2026-08-23

### Fixed

- Removed single-letter row-action keybindings (S/I/E/X on Statistics/Copy/Edit/Delete) from the Short URLs table. Filament renders keyBindings() as global shortcuts that fire even while a text input has focus, so those letters were unusable in the table's own search box.

## [1.2.0](https://github.com/jeffersongoncalves/filament-short-url/compare/1.1.0...1.2.0) - 2026-08-23

### Added

- `GlobalCountryBreakdown`/`GlobalCityBreakdown` widgets on the global Metrics dashboard, showing country/city traffic across every link (previously geo breakdown was only on each link's own Statistics page).

## [1.1.0](https://github.com/jeffersongoncalves/filament-short-url/compare/1.0.0...1.1.0) - 2026-08-23

### Added

- `hideImport()`/`isImportHidden()` on `FilamentShortUrlPlugin` to remove the Import Links page from the panel.
- Hiding Pixels via `hidePixels()` now also removes the Pixels resource from the sidebar (previously it only hid the form section).

## [1.0.0](https://github.com/jeffersongoncalves/filament-short-url/compare/3.0.0...1.0.0) - 2026-08-22

**Full Changelog**: https://github.com/jeffersongoncalves/filament-short-url/commits/1.0.0
