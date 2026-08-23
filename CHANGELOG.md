# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased](https://github.com/jeffersongoncalves/filament-short-url/compare/3.2.1...HEAD)

### Added

- `ShortUrlResource` (Filament v5) for `jeffersongoncalves/laravel-short-url`: list, create and edit pages covering the F1 field set.
- `FilamentShortUrlPlugin` with resource override and navigation group configuration.

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
