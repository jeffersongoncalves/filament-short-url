<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;

class FolderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema(static::fields());
    }

    /**
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament-short-url::resources/folder.fields.name'))
                ->required()
                ->maxLength(255),

            ColorPicker::make('color')
                ->label(__('filament-short-url::resources/folder.fields.color')),

            Select::make('parent_id')
                ->label(__('filament-short-url::resources/folder.fields.parent'))
                ->options(fn (?Folder $record): array => Folder::query()
                    ->when($record, fn ($query) => $query->whereKeyNot($record->id))
                    ->pluck('name', 'id')
                    ->all())
                ->searchable(),
        ];
    }
}
