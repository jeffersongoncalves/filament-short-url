<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Endroid\QrCode\Builder\Builder as QrCodeBuilder;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rules\Unique;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components\RuleBuilder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components\SplitSlider;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Forms\Components\UtmBuilder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\CreateShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\EditShortUrl;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\ListShortUrls;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages\Statistics;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;
use JeffersonGoncalves\LaravelShortUrl\Models\Pixel;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;
use JeffersonGoncalves\LaravelShortUrl\Models\Tag;
use JeffersonGoncalves\LaravelShortUrl\Registries\PixelProviderRegistry;

class ShortUrlResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ShortUrl::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...static::essentials(),
            ...static::targeting(),
            ...static::security(),
            ...static::tracking(),
            ...static::utm(),
            ...static::pixels(),
        ]);
    }

    /**
     * @return array<int, Step>
     */
    public static function steps(): array
    {
        $steps = [
            Step::make('essentials')
                ->label(__('filament-short-url::resources/short-url.wizard.essentials'))
                ->schema(static::essentials()),
        ];

        $targeting = static::targeting();

        if ($targeting !== []) {
            $steps[] = Step::make('targeting')
                ->label(__('filament-short-url::resources/short-url.wizard.targeting'))
                ->schema($targeting);
        }

        $securityTracking = [...static::security(), ...static::tracking()];

        if ($securityTracking !== []) {
            $steps[] = Step::make('security')
                ->label(__('filament-short-url::resources/short-url.wizard.security_tracking'))
                ->schema($securityTracking);
        }

        $utmPixels = [...static::utm(), ...static::pixels()];

        if ($utmPixels !== []) {
            $steps[] = Step::make('utm')
                ->label(__('filament-short-url::resources/short-url.wizard.utm_pixels'))
                ->schema($utmPixels);
        }

        return $steps;
    }

    /**
     * @return array<int, Component>
     */
    protected static function essentials(): array
    {
        return [
            TextInput::make('destination_url')
                ->label(__('filament-short-url::resources/short-url.fields.destination_url'))
                ->required()
                ->url()
                ->maxLength(65535)
                ->live(onBlur: true)
                ->afterStateUpdated(function (?string $state, Set $set): void {
                    if (! $state || ! filter_var($state, FILTER_VALIDATE_URL)) {
                        return;
                    }

                    parse_str(parse_url($state, PHP_URL_QUERY) ?? '', $query);

                    foreach (['source', 'medium', 'campaign', 'term', 'content'] as $field) {
                        if (isset($query["utm_{$field}"])) {
                            $set("utm_{$field}", $query["utm_{$field}"]);
                        }
                    }
                })
                ->columnSpanFull(),

            TextInput::make('url_key')
                ->label(__('filament-short-url::resources/short-url.fields.url_key'))
                ->helperText(fn (?ShortUrl $record): string => $record?->exists
                    ? __('filament-short-url::resources/short-url.fields.url_key_locked_helper')
                    : __('filament-short-url::resources/short-url.fields.url_key_helper'))
                ->nullable()
                ->alphaDash()
                ->maxLength(64)
                ->disabled(fn (?ShortUrl $record): bool => $record !== null && $record->exists)
                ->dehydrated(fn (?ShortUrl $record): bool => $record === null || ! $record->exists)
                ->unique(
                    table: (new ShortUrl)->getTable(),
                    column: 'url_key',
                    ignoreRecord: true,
                    modifyRuleUsing: fn (Unique $rule): Unique => $rule->whereNull('custom_domain_id'),
                ),

            TextInput::make('title')
                ->label(__('filament-short-url::resources/short-url.fields.title'))
                ->maxLength(255),

            Textarea::make('notes')
                ->label(__('filament-short-url::resources/short-url.fields.notes'))
                ->rows(3)
                ->columnSpanFull(),

            Toggle::make('is_enabled')
                ->label(__('filament-short-url::resources/short-url.fields.is_enabled'))
                ->default(true),

            Toggle::make('single_use')
                ->label(__('filament-short-url::resources/short-url.fields.single_use'))
                ->default(false),

            Toggle::make('forward_query_params')
                ->label(__('filament-short-url::resources/short-url.fields.forward_query_params'))
                ->default(true),

            Select::make('redirect_status_code')
                ->label(__('filament-short-url::resources/short-url.fields.redirect_status_code'))
                ->options([
                    301 => '301',
                    302 => '302',
                    307 => '307',
                    308 => '308',
                ])
                ->default(302)
                ->required(),

            TextInput::make('max_visits')
                ->label(__('filament-short-url::resources/short-url.fields.max_visits'))
                ->numeric()
                ->nullable(),

            DateTimePicker::make('expires_at')
                ->label(__('filament-short-url::resources/short-url.fields.expires_at'))
                ->nullable(),
        ];
    }

    /**
     * @return array<int, Component>
     */
    protected static function targeting(): array
    {
        if (FilamentShortUrlPlugin::get()->isTargetingHidden()) {
            return [];
        }

        return [
            Select::make('destination_type')
                ->label(__('filament-short-url::resources/short-url.fields.destination_type'))
                ->options([
                    'single' => __('filament-short-url::resources/short-url.fields.destination_type_single'),
                    'rules' => __('filament-short-url::resources/short-url.fields.destination_type_rules'),
                    'split' => __('filament-short-url::resources/short-url.fields.destination_type_split'),
                ])
                ->default('single')
                ->live()
                ->required()
                ->columnSpanFull(),

            RuleBuilder::make('targeting_rules')
                ->visible(fn (Get $get): bool => $get('destination_type') === 'rules')
                ->columnSpanFull(),

            SplitSlider::make('rotation_variants')
                ->visible(fn (Get $get): bool => $get('destination_type') === 'split')
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<int, Component>
     */
    protected static function security(): array
    {
        if (FilamentShortUrlPlugin::get()->isSecurityHidden()) {
            return [];
        }

        return [
            Section::make(__('filament-short-url::resources/short-url.security.section'))
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('password')
                        ->label(__('filament-short-url::resources/short-url.security.password'))
                        ->password()
                        ->revealable()
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText(__('filament-short-url::resources/short-url.security.password_helper')),

                    TextInput::make('password_hint')
                        ->label(__('filament-short-url::resources/short-url.security.password_hint'))
                        ->maxLength(255),

                    Toggle::make('show_warning_page')
                        ->label(__('filament-short-url::resources/short-url.security.show_warning_page'))
                        ->live(),

                    Textarea::make('warning_message')
                        ->label(__('filament-short-url::resources/short-url.security.warning_message'))
                        ->rows(2)
                        ->visible(fn (Get $get): bool => (bool) $get('show_warning_page')),

                    Select::make('safe_browsing_status')
                        ->label(__('filament-short-url::resources/short-url.security.safe_browsing_status'))
                        ->options([
                            'safe' => __('filament-short-url::resources/short-url.security.safe_browsing_safe'),
                            'unsafe' => __('filament-short-url::resources/short-url.security.safe_browsing_unsafe'),
                            'unknown' => __('filament-short-url::resources/short-url.security.safe_browsing_unknown'),
                        ])
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn (?ShortUrl $record): bool => $record?->exists && $record->safe_browsing_status !== null),
                ]),
        ];
    }

    /**
     * @return array<int, Component>
     */
    protected static function tracking(): array
    {
        return [
            Section::make(__('filament-short-url::resources/short-url.tracking.section'))
                ->columnSpanFull()
                ->columns(3)
                ->schema([
                    Toggle::make('track_visits')->label(__('filament-short-url::resources/short-url.tracking.track_visits'))->default(true),
                    Toggle::make('track_ip_address')->label(__('filament-short-url::resources/short-url.tracking.track_ip_address'))->default(true),
                    Toggle::make('track_browser')->label(__('filament-short-url::resources/short-url.tracking.track_browser'))->default(true),
                    Toggle::make('track_browser_version')->label(__('filament-short-url::resources/short-url.tracking.track_browser_version'))->default(true),
                    Toggle::make('track_operating_system')->label(__('filament-short-url::resources/short-url.tracking.track_operating_system'))->default(true),
                    Toggle::make('track_operating_system_version')->label(__('filament-short-url::resources/short-url.tracking.track_operating_system_version'))->default(true),
                    Toggle::make('track_device_type')->label(__('filament-short-url::resources/short-url.tracking.track_device_type'))->default(true),
                    Toggle::make('track_referer_url')->label(__('filament-short-url::resources/short-url.tracking.track_referer_url'))->default(true),
                    Toggle::make('track_browser_language')->label(__('filament-short-url::resources/short-url.tracking.track_browser_language'))->default(true),
                ]),
        ];
    }

    /**
     * @return array<int, Component>
     */
    protected static function utm(): array
    {
        if (FilamentShortUrlPlugin::get()->isUtmHidden()) {
            return [];
        }

        return [
            Section::make(__('filament-short-url::resources/short-url.utm.section'))
                ->columnSpanFull()
                ->collapsed()
                ->schema([
                    UtmBuilder::make(),
                ]),
        ];
    }

    /**
     * @return array<int, Component>
     */
    protected static function pixels(): array
    {
        if (FilamentShortUrlPlugin::get()->isPixelsHidden()) {
            return [];
        }

        return [
            Section::make(__('filament-short-url::resources/short-url.pixels.section'))
                ->columnSpanFull()
                ->schema([
                    Select::make('pixels')
                        ->label(__('filament-short-url::resources/short-url.pixels.field'))
                        ->relationship('pixels', 'name')
                        ->multiple()
                        ->preload()
                        ->createOptionForm(PixelResource::fields())
                        ->createOptionUsing(function (array $data): int {
                            $provider = app(PixelProviderRegistry::class)->get($data['provider_key'] ?? '');
                            $config = [];

                            foreach ($provider === null ? [] : $provider->configFields as $index => $field) {
                                $config[$field['key']] = $data["config_field_{$index}"] ?? null;
                                unset($data["config_field_{$index}"]);
                            }

                            $data['config'] = $config;

                            return (int) Pixel::query()->create($data)->getKey();
                        }),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        $statisticsHidden = FilamentShortUrlPlugin::get()->isStatisticsHidden();
        $securityHidden = FilamentShortUrlPlugin::get()->isSecurityHidden();
        $foldersHidden = FilamentShortUrlPlugin::get()->isFoldersHidden();
        $tagsHidden = FilamentShortUrlPlugin::get()->isTagsHidden();

        return $table
            ->when(
                ! $statisticsHidden,
                fn (Table $table): Table => $table->recordUrl(
                    fn (ShortUrl $record): string => static::getUrl('statistics', ['record' => $record]),
                ),
            )
            ->columns([
                TextColumn::make('short_url')
                    ->label(__('filament-short-url::resources/short-url.fields.short_url'))
                    ->state(fn (ShortUrl $record): string => $record->fullUrl())
                    ->limit(24)
                    ->tooltip(fn (ShortUrl $record): string => $record->fullUrl())
                    ->copyable()
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
                    ->form([
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
            ->bulkActions([
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
                        ->form([
                            Select::make('folder_id')
                                ->label(__('filament-short-url::resources/folder.fields.parent'))
                                ->options(fn (): array => Folder::query()->pluck('name', 'id')->all())
                                ->searchable()
                                ->createOptionForm(FolderResource::fields())
                                ->createOptionUsing(fn (array $data): int => (int) Folder::query()->create($data)->getKey()),
                        ])
                        ->action(fn (Collection $records, array $data) => $records->toQuery()->update(['folder_id' => $data['folder_id']]))
                        ->visible(! $foldersHidden)
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('apply_tags')
                        ->label(__('filament-short-url::resources/short-url.bulk.apply_tags'))
                        ->icon('heroicon-o-tag')
                        ->form([
                            Select::make('tag_ids')
                                ->label(__('filament-short-url::resources/tag.fields.name'))
                                ->options(fn (): array => Tag::query()->pluck('name', 'id')->all())
                                ->multiple()
                                ->searchable()
                                ->createOptionForm(TagResource::fields())
                                ->createOptionUsing(fn (array $data): int => (int) Tag::query()->create($data)->getKey()),
                        ])
                        ->action(static::applyTagsToRecords(...))
                        ->visible(! $tagsHidden)
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->actions([
                Action::make('qr_code')
                    ->label(__('filament-short-url::resources/short-url.actions.qr_code'))
                    ->icon('heroicon-o-qr-code')
                    ->color('gray')
                    ->visible(fn (): bool => class_exists(QrCodeBuilder::class))
                    ->modalHeading(__('filament-short-url::resources/short-url.actions.qr_code'))
                    ->modalContent(fn (ShortUrl $record): View => view('filament-short-url::actions.qr-code-modal', [
                        'dataUri' => $record->qrCode()->dataUri(),
                    ]))
                    ->modalSubmitAction(false),
                ActionGroup::make([
                    Action::make('statistics')
                        ->label(__('filament-short-url::resources/short-url.actions.statistics'))
                        ->icon('heroicon-o-chart-bar')
                        ->visible(! $statisticsHidden)
                        ->url(fn (ShortUrl $record): string => static::getUrl('statistics', ['record' => $record])),
                    Action::make('copy')
                        ->label(__('filament-short-url::resources/short-url.actions.copy'))
                        ->icon('heroicon-o-clipboard-document')
                        ->extraAttributes(fn (ShortUrl $record): array => [
                            'x-on:click' => 'window.navigator.clipboard.writeText('.static::jsQuote($record->fullUrl()).');'
                                .'$tooltip('.static::jsQuote(__('filament-short-url::resources/short-url.actions.copied')).', { theme: $store.theme, timeout: 2000 })',
                        ]),
                    EditAction::make()
                        ->label(__('filament-short-url::resources/short-url.actions.edit')),
                    DeleteAction::make()
                        ->label(__('filament-short-url::resources/short-url.actions.delete')),
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

    public static function setNavigationLabel(?string $label): void
    {
        static::$navigationLabel = $label;
    }

    public static function setNavigationIcon(?string $icon): void
    {
        static::$navigationIcon = $icon;
    }

    public static function setNavigationSort(?int $sort): void
    {
        static::$navigationSort = $sort;
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
        $pages = [
            'index' => ListShortUrls::route('/'),
            'create' => CreateShortUrl::route('/create'),
            'edit' => EditShortUrl::route('/{record}/edit'),
        ];

        if (! FilamentShortUrlPlugin::get()->isStatisticsHidden()) {
            $pages['statistics'] = Statistics::route('/{record}/statistics');
        }

        return $pages;
    }
}
