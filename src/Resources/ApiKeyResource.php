<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\CreateApiKey;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\EditApiKey;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Pages\ListApiKeys;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Schemas\ApiKeyForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Tables\ApiKeysTable;
use JeffersonGoncalves\LaravelShortUrl\Models\ApiKey;

class ApiKeyResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ApiKey::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    public static function form(Schema $schema): Schema
    {
        return ApiKeyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiKeysTable::configure($table);
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
            'index' => ListApiKeys::route('/'),
            'create' => CreateApiKey::route('/create'),
            'edit' => EditApiKey::route('/{record}/edit'),
        ];
    }
}
