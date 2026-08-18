<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withCount('shortUrls'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-short-url::resources/tag.fields.name'))
                    ->searchable(),

                ColorColumn::make('color')
                    ->label(__('filament-short-url::resources/tag.fields.color')),

                TextColumn::make('short_urls_count')
                    ->label(__('filament-short-url::resources/tag.fields.links_count'))
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
