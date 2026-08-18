<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\EditShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Statistics;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Schemas\ShortUrlForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Tables\ShortUrlsTable;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class ShortUrlResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ShortUrl::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    public static function form(Schema $schema): Schema
    {
        return ShortUrlForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShortUrlsTable::configure($table);
    }

    public static function setNavigationLabel(?string $label): void
    {
        static::$navigationLabel = $label;
    }

    public static function setNavigationIcon(string|BackedEnum|null $icon): void
    {
        static::$navigationIcon = $icon;
    }

    public static function setNavigationSort(?int $sort): void
    {
        static::$navigationSort = $sort;
    }

    public static function canViewAny(): bool
    {
        $callback = FilamentShortUrlPlugin::get()->getAuthorizeUsing();

        if ($callback !== null) {
            return (bool) $callback();
        }

        return parent::canViewAny();
    }

    public static function getPages(): array
    {
        $pages = [
            'index' => ListShortUrls::route('/'),
            'create' => CreateShortUrl::route('/create'),
            'edit' => EditShortUrl::route('/{record}/edit'),
        ];

        if (! FilamentShortUrlPlugin::get()->isStatisticsHidden()) {
            $pages['statistics'] = Statistics::route('/{record}/statistics');
        }

        return $pages;
    }
}
