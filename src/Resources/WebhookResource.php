<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\CreateWebhook;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\EditWebhook;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Pages\ListWebhooks;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Schemas\WebhookForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\WebhookResource\Tables\WebhooksTable;
use JeffersonGoncalves\LaravelShortUrl\Models\Webhook;

class WebhookResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = Webhook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    public static function form(Schema $schema): Schema
    {
        return WebhookForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WebhooksTable::configure($table);
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
            'index' => ListWebhooks::route('/'),
            'create' => CreateWebhook::route('/create'),
            'edit' => EditWebhook::route('/{record}/edit'),
        ];
    }
}
