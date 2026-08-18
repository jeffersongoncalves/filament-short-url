<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\CreateCustomDomain;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\EditCustomDomain;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Pages\ListCustomDomains;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Schemas\CustomDomainForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\CustomDomainResource\Tables\CustomDomainsTable;
use JeffersonGoncalves\LaravelShortUrl\Models\CustomDomain;

class CustomDomainResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = CustomDomain::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    public static function form(Schema $schema): Schema
    {
        return CustomDomainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomDomainsTable::configure($table);
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
            'index' => ListCustomDomains::route('/'),
            'create' => CreateCustomDomain::route('/create'),
            'edit' => EditCustomDomain::route('/{record}/edit'),
        ];
    }
}
