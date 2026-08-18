<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class ShortUrlsTable
{
    public static function configure(Table $table): Table
    {
        $statisticsHidden = FilamentShortUrlPlugin::get()->isStatisticsHidden();

        return $table
            ->when(
                ! $statisticsHidden,
                fn (Table $table): Table => $table->recordUrl(
                    fn (ShortUrl $record): string => ShortUrlResource::getUrl('statistics', ['record' => $record]),
                ),
            )
            ->columns([
                TextColumn::make('url_key')
                    ->label(__('filament-short-url::resources/short-url.fields.url_key'))
                    ->copyable()
                    ->searchable(),

                TextColumn::make('destination_url')
                    ->label(__('filament-short-url::resources/short-url.fields.destination_url'))
                    ->limit(40)
                    ->tooltip(fn (ShortUrl $record): string => $record->destination_url)
                    ->searchable(),

                TextColumn::make('title')
                    ->label(__('filament-short-url::resources/short-url.fields.title'))
                    ->searchable(),

                ToggleColumn::make('is_enabled')
                    ->label(__('filament-short-url::resources/short-url.fields.is_enabled')),

                TextColumn::make('total_visits')
                    ->label(__('filament-short-url::resources/short-url.fields.total_visits'))
                    ->badge(),

                TextColumn::make('qr_scans')
                    ->label(__('filament-short-url::resources/short-url.fields.qr_scans'))
                    ->badge()
                    ->color('gray'),

                ViewColumn::make('last_visited_at')
                    ->label(__('filament-short-url::resources/short-url.fields.last_visited_at'))
                    ->view('filament-short-url::columns.relative-time-badge'),

                TextColumn::make('expires_at')
                    ->label(__('filament-short-url::resources/short-url.fields.expires_at'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament-short-url::resources/short-url.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_enabled')
                    ->label(__('filament-short-url::resources/short-url.fields.is_enabled')),

                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label(__('filament-short-url::resources/short-url.filters.created_from')),
                        DatePicker::make('created_until')
                            ->label(__('filament-short-url::resources/short-url.filters.created_until')),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['created_from'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'] ?? null, fn (Builder $q, string $date): Builder => $q->whereDate('created_at', '<=', $date))),
            ])
            ->recordActions([
                Action::make('statistics')
                    ->label(__('filament-short-url::resources/short-url.actions.statistics'))
                    ->icon('heroicon-o-chart-bar')
                    ->color('gray')
                    ->visible(! $statisticsHidden)
                    ->keyBindings(['s'])
                    ->url(fn (ShortUrl $record): string => ShortUrlResource::getUrl('statistics', ['record' => $record])),
                EditAction::make()
                    ->keyBindings(['e']),
                DeleteAction::make(),
            ]);
    }
}
