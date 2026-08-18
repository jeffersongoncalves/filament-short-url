<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;

class FoldersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withCount('shortUrls')->with('parent'))
            ->defaultSort('parent_id')
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-short-url::resources/folder.fields.name'))
                    ->formatStateUsing(fn (Folder $record, string $state): string => str_repeat('— ', static::depth($record)).$state)
                    ->searchable(),

                ColorColumn::make('color')
                    ->label(__('filament-short-url::resources/folder.fields.color')),

                TextColumn::make('parent.name')
                    ->label(__('filament-short-url::resources/folder.fields.parent'))
                    ->placeholder('—'),

                TextColumn::make('short_urls_count')
                    ->label(__('filament-short-url::resources/folder.fields.links_count'))
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    protected static function depth(Folder $folder): int
    {
        $depth = 0;
        $current = $folder;

        while ($current->parent_id !== null && $depth < 10) {
            $current = $current->parent ?? Folder::query()->find($current->parent_id);

            if (! $current) {
                break;
            }

            $depth++;
        }

        return $depth;
    }
}
