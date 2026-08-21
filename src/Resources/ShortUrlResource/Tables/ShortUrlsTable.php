<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Schemas\FolderForm;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Schemas\TagForm;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\Models\Tag;

class ShortUrlsTable
{
    public static function configure(Table $table): Table
    {
        $statisticsHidden = FilamentShortUrlPlugin::get()->isStatisticsHidden();
        $securityHidden = FilamentShortUrlPlugin::get()->isSecurityHidden();
        $foldersHidden = FilamentShortUrlPlugin::get()->isFoldersHidden();
        $tagsHidden = FilamentShortUrlPlugin::get()->isTagsHidden();

        return $table
            ->when(
                ! $statisticsHidden,
                fn (Table $table): Table => $table->recordUrl(
                    fn (ShortUrl $record): string => ShortUrlResource::getUrl('statistics', ['record' => $record]),
                ),
            )
            ->columns([
                TextColumn::make('short_url')
                    ->label(__('filament-short-url::resources/short-url.fields.short_url'))
                    ->state(fn (ShortUrl $record): string => $record->fullUrl())
                    ->limit(24)
                    ->tooltip(fn (ShortUrl $record): string => $record->fullUrl())
                    ->copyable()
                    ->copyableState(fn (ShortUrl $record): string => $record->fullUrl())
                    ->copyMessage(__('filament-short-url::resources/short-url.actions.copied')),

                TextColumn::make('url_key')
                    ->label(__('filament-short-url::resources/short-url.fields.url_key'))
                    ->copyable()
                    ->copyableState(fn (ShortUrl $record): string => $record->fullUrl())
                    ->copyMessage(__('filament-short-url::resources/short-url.actions.copied'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('destination_url')
                    ->label(__('filament-short-url::resources/short-url.fields.destination_url'))
                    ->limit(24)
                    ->tooltip(fn (ShortUrl $record): string => $record->destination_url)
                    ->searchable(),

                TextColumn::make('title')
                    ->label(__('filament-short-url::resources/short-url.fields.title'))
                    ->searchable(),

                ToggleColumn::make('is_enabled')
                    ->label(__('filament-short-url::resources/short-url.fields.is_enabled')),

                IconColumn::make('is_protected')
                    ->label(__('filament-short-url::resources/short-url.security.password'))
                    ->state(fn (ShortUrl $record): bool => filled($record->password_hash))
                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open')
                    ->color(fn (bool $state): string => $state ? 'warning' : 'gray')
                    ->visible(! $securityHidden),

                TextColumn::make('total_visits')
                    ->label(__('filament-short-url::resources/short-url.fields.total_visits'))
                    ->badge(),

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

                SelectFilter::make('folder_id')
                    ->label(__('filament-short-url::resources/short-url.filters.folder'))
                    ->options(fn (): array => Folder::query()->pluck('name', 'id')->all())
                    ->visible(! $foldersHidden),

                SelectFilter::make('tags')
                    ->label(__('filament-short-url::resources/short-url.filters.tags'))
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->visible(! $tagsHidden),

                TernaryFilter::make('archived')
                    ->label(__('filament-short-url::resources/short-url.filters.archived'))
                    ->queries(
                        true: static::archivedQuery(...),
                        false: static::notArchivedQuery(...),
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('enable')
                        ->label(__('filament-short-url::resources/short-url.bulk.enable'))
                        ->icon('heroicon-o-check-circle')
                        ->action(fn (Collection $records) => $records->toQuery()->update(['is_enabled' => true]))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('disable')
                        ->label(__('filament-short-url::resources/short-url.bulk.disable'))
                        ->icon('heroicon-o-x-circle')
                        ->action(fn (Collection $records) => $records->toQuery()->update(['is_enabled' => false]))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('archive')
                        ->label(__('filament-short-url::resources/short-url.bulk.archive'))
                        ->icon('heroicon-o-archive-box')
                        ->action(fn (Collection $records) => $records->toQuery()->update(['archived_at' => now()]))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('unarchive')
                        ->label(__('filament-short-url::resources/short-url.bulk.unarchive'))
                        ->icon('heroicon-o-archive-box-x-mark')
                        ->action(fn (Collection $records) => $records->toQuery()->update(['archived_at' => null]))
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('move_to_folder')
                        ->label(__('filament-short-url::resources/short-url.bulk.move_to_folder'))
                        ->icon('heroicon-o-folder-arrow-down')
                        ->schema([
                            Select::make('folder_id')
                                ->label(__('filament-short-url::resources/folder.fields.parent'))
                                ->options(fn (): array => Folder::query()->pluck('name', 'id')->all())
                                ->searchable()
                                ->createOptionForm(FolderForm::fields())
                                ->createOptionUsing(fn (array $data): int => (int) Folder::query()->create($data)->getKey()),
                        ])
                        ->action(fn (Collection $records, array $data) => $records->toQuery()->update(['folder_id' => $data['folder_id']]))
                        ->visible(! $foldersHidden)
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('apply_tags')
                        ->label(__('filament-short-url::resources/short-url.bulk.apply_tags'))
                        ->icon('heroicon-o-tag')
                        ->schema([
                            Select::make('tag_ids')
                                ->label(__('filament-short-url::resources/tag.fields.name'))
                                ->options(fn (): array => Tag::query()->pluck('name', 'id')->all())
                                ->multiple()
                                ->searchable()
                                ->createOptionForm(TagForm::fields())
                                ->createOptionUsing(fn (array $data): int => (int) Tag::query()->create($data)->getKey()),
                        ])
                        ->action(static::applyTagsToRecords(...))
                        ->visible(! $tagsHidden)
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('statistics')
                        ->label(__('filament-short-url::resources/short-url.actions.statistics').' (S)')
                        ->icon('heroicon-o-chart-bar')
                        ->visible(! $statisticsHidden)
                        ->keyBindings(['s'])
                        ->url(fn (ShortUrl $record): string => ShortUrlResource::getUrl('statistics', ['record' => $record])),
                    Action::make('copy')
                        ->label(__('filament-short-url::resources/short-url.actions.copy').' (I)')
                        ->icon('heroicon-o-clipboard-document')
                        ->keyBindings(['i'])
                        ->alpineClickHandler(fn (ShortUrl $record): string => 'window.navigator.clipboard.writeText('.static::jsQuote($record->fullUrl()).');'
                            .'$tooltip('.static::jsQuote(__('filament-short-url::resources/short-url.actions.copied')).', { theme: $store.theme, timeout: 2000 })'),
                    EditAction::make()
                        ->label(__('filament-short-url::resources/short-url.actions.edit').' (E)')
                        ->keyBindings(['e']),
                    DeleteAction::make()
                        ->label(__('filament-short-url::resources/short-url.actions.delete').' (X)')
                        ->keyBindings(['x']),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray'),
            ]);
    }

    /**
     * @param  Builder<ShortUrl>  $query
     * @return Builder<ShortUrl>
     */
    protected static function archivedQuery(Builder $query): Builder
    {
        return $query->archived();
    }

    /**
     * @param  Builder<ShortUrl>  $query
     * @return Builder<ShortUrl>
     */
    protected static function notArchivedQuery(Builder $query): Builder
    {
        return $query->notArchived();
    }

    /**
     * @param  Collection<int, ShortUrl>  $records
     * @param  array<string, mixed>  $data
     */
    protected static function applyTagsToRecords(Collection $records, array $data): void
    {
        foreach ($records as $record) {
            $record->tags()->syncWithoutDetaching($data['tag_ids'] ?? []);
        }
    }

    /**
     * Single-quoted JS string literal for embedding in an `x-on:click`
     * attribute. Filament's own attribute rendering escapes `"` to `\"`
     * (correct for HTML, but the browser never un-escapes that back to a
     * bare quote for JS) — so a double-quoted `json_encode()` string always
     * breaks. Single quotes sidestep it entirely since only `"` is escaped.
     */
    protected static function jsQuote(string $value): string
    {
        return "'".str_replace(['\\', "'"], ['\\\\', "\\'"], $value)."'";
    }
}
