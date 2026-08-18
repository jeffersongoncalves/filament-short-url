<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\CreatePixel;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\EditPixel;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\ListPixels;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Schemas\PixelForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Tables\PixelsTable;
use JeffersonGoncalves\LaravelShortUrl\Models\Pixel;

class PixelResource extends Resource
{
    protected static ?string $model = Pixel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewfinderCircle;

    public static function form(Schema $schema): Schema
    {
        return PixelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PixelsTable::configure($table);
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
        return [
            'index' => ListPixels::route('/'),
            'create' => CreatePixel::route('/create'),
            'edit' => EditPixel::route('/{record}/edit'),
        ];
    }
}
