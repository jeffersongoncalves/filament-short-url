<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Tests\Panel;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->plugin(FilamentShortUrlPlugin::make())
            ->middleware([
                DispatchServingFilamentEvent::class,
                DisableBladeIconComponents::class,
            ]);
    }
}
