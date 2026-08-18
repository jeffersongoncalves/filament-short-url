<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ApiKeyResource\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ApiKeyForm
{
    /**
     * @var array<string, string>
     */
    public const ABILITIES = [
        '*' => 'Full access',
        'links:read' => 'links:read',
        'links:write' => 'links:write',
        'conversions:write' => 'conversions:write',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label(__('filament-short-url::resources/api-key.fields.name'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            CheckboxList::make('abilities')
                ->label(__('filament-short-url::resources/api-key.fields.abilities'))
                ->options(static::ABILITIES)
                ->required()
                ->columnSpanFull(),

            DateTimePicker::make('expires_at')
                ->label(__('filament-short-url::resources/api-key.fields.expires_at'))
                ->nullable(),
        ]);
    }
}
