<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\CreateBioPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\EditBioPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\ListBioPages;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Schemas\BioPageForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Tables\BioPagesTable;
use JeffersonGoncalves\LaravelShortUrl\Models\BioPage;

class BioPageResource extends Resource
{
    protected static ?string $model = BioPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    public static function form(Schema $schema): Schema
    {
        return BioPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BioPagesTable::configure($table);
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
            'index' => ListBioPages::route('/'),
            'create' => CreateBioPage::route('/create'),
            'edit' => EditBioPage::route('/{record}/edit'),
        ];
    }
}
