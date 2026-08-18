<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label(__('filament-short-url::resources/tag.fields.name'))
                ->required()
                ->maxLength(255),

            ColorPicker::make('color')
                ->label(__('filament-short-url::resources/tag.fields.color')),
        ]);
    }
}
