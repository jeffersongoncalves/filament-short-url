<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Concerns;

use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;

/**
 * Every resource/page this plugin registers shares one navigation group so
 * they cluster together in the sidebar instead of scattering across the
 * host panel's other groups. FilamentShortUrlPlugin::navigationGroup()
 * overrides it panel-wide; unset, it falls back to a translated default.
 */
trait HasPluginNavigationGroup
{
    public static function getNavigationGroup(): ?string
    {
        return FilamentShortUrlPlugin::get()->getNavigationGroup();
    }
}
