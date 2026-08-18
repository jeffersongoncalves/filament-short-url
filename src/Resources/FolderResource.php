<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\CreateFolder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\EditFolder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\ListFolders;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Schemas\FolderForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Tables\FoldersTable;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;

class FolderResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = Folder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    public static function form(Schema $schema): Schema
    {
        return FolderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FoldersTable::configure($table);
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
            'index' => ListFolders::route('/'),
            'create' => CreateFolder::route('/create'),
            'edit' => EditFolder::route('/{record}/edit'),
        ];
    }
}
