<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\LaravelShortUrl\Models\BioPage;

class BioPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withCount('links'))
            ->columns([
                TextColumn::make('handle')
                    ->label(__('filament-short-url::resources/bio-page.fields.handle'))
                    ->searchable()
                    ->copyable(),

                TextColumn::make('title')
                    ->label(__('filament-short-url::resources/bio-page.fields.title')),

                IconColumn::make('is_published')
                    ->label(__('filament-short-url::resources/bio-page.fields.is_published'))
                    ->boolean(),

                TextColumn::make('links_count')
                    ->label(__('filament-short-url::resources/bio-page.fields.blocks'))
                    ->badge(),

                TextColumn::make('total_views')
                    ->label(__('filament-short-url::resources/bio-page.fields.total_views'))
                    ->badge()
                    ->color('gray'),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label(__('filament-short-url::resources/bio-page.actions.preview'))
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (BioPage $record): string => static::previewUrl($record))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function previewUrl(BioPage $record): string
    {
        return url(trim((string) config('short-url.bio.prefix', 'bio'), '/').'/'.$record->handle);
    }
}
