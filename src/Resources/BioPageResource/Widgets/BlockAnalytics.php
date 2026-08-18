<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use JeffersonGoncalves\LaravelShortUrl\Models\BioLink;
use JeffersonGoncalves\LaravelShortUrl\Models\BioPage;

class BlockAnalytics extends TableWidget
{
    public ?BioPage $record = null;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('filament-short-url::resources/bio-page.analytics.heading'))
            ->query(BioLink::query()->where('bio_page_id', $this->record?->id)->orderByDesc('click_count'))
            ->paginated(false)
            ->columns([
                TextColumn::make('label')
                    ->label(__('filament-short-url::resources/bio-page.fields.block_label'))
                    ->placeholder(fn (BioLink $record): string => ucfirst($record->type)),

                TextColumn::make('type')
                    ->label(__('filament-short-url::resources/bio-page.fields.block_type'))
                    ->badge(),

                TextColumn::make('click_count')
                    ->label(__('filament-short-url::resources/bio-page.analytics.clicks'))
                    ->badge()
                    ->sortable(),
            ]);
    }
}
