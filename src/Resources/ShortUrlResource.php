<?php

namespace JeffersonGoncalves\FilamentShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages\EditShortUrl;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Schemas\ShortUrlForm;
use JeffersonGoncalves\FilamentShortUrl\Resources\ShortUrlResource\Tables\ShortUrlsTable;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class ShortUrlResource extends Resource
{
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

    public static function setNavigationGroup(?string $group): void
    {
        static::$navigationGroup = $group;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShortUrls::route('/'),
            'create' => CreateShortUrl::route('/create'),
            'edit' => EditShortUrl::route('/{record}/edit'),
        ];
    }
}
