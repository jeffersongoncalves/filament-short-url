<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\CreateBioPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\EditBioPage;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages\ListBioPages;
use JeffersonGoncalves\LaravelShortUrl\Models\BioPage;

class BioPageResource extends Resource
{
    use HasPluginNavigationGroup;

    /**
     * Block types the core's public bio-page.blade.php actually knows how
     * to render (text/image/video get their own markup; anything else,
     * including the default "link", falls back to a plain link block) —
     * intentionally not offering types (social icons, forms) the core has
     * no rendering support for.
     */
    public const BLOCK_TYPES = ['link', 'text', 'image', 'video'];

    protected static ?string $model = BioPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('handle')
                ->label(__('filament-short-url::resources/bio-page.fields.handle'))
                ->required()
                ->alphaDash()
                ->unique(ignoreRecord: true)
                ->helperText(__('filament-short-url::resources/bio-page.fields.handle_helper')),

            TextInput::make('title')
                ->label(__('filament-short-url::resources/bio-page.fields.title'))
                ->maxLength(255),

            Textarea::make('bio')
                ->label(__('filament-short-url::resources/bio-page.fields.bio'))
                ->rows(2)
                ->columnSpanFull(),

            FileUpload::make('avatar_path')
                ->label(__('filament-short-url::resources/bio-page.fields.avatar'))
                ->image()
                ->directory('short-url-bio-avatars'),

            Select::make('theme')
                ->label(__('filament-short-url::resources/bio-page.fields.theme'))
                ->options([
                    'default' => __('filament-short-url::resources/bio-page.themes.default'),
                    'light' => __('filament-short-url::resources/bio-page.themes.light'),
                    'sunset' => __('filament-short-url::resources/bio-page.themes.sunset'),
                    'ocean' => __('filament-short-url::resources/bio-page.themes.ocean'),
                ])
                ->default('default')
                ->helperText(__('filament-short-url::resources/bio-page.fields.theme_helper')),

            Toggle::make('is_published')
                ->label(__('filament-short-url::resources/bio-page.fields.is_published'))
                ->default(false),

            Section::make(__('filament-short-url::resources/bio-page.fields.seo_section'))
                ->columnSpanFull()
                ->collapsed()
                ->schema([
                    TextInput::make('og_title')
                        ->label(__('filament-short-url::resources/bio-page.fields.og_title')),
                    Textarea::make('og_description')
                        ->label(__('filament-short-url::resources/bio-page.fields.og_description'))
                        ->rows(2),
                    FileUpload::make('og_image_path')
                        ->label(__('filament-short-url::resources/bio-page.fields.og_image'))
                        ->image()
                        ->directory('short-url-bio-og'),
                ]),

            Section::make(__('filament-short-url::resources/bio-page.fields.blocks_section'))
                ->columnSpanFull()
                ->schema([
                    Repeater::make('links')
                        ->label(__('filament-short-url::resources/bio-page.fields.blocks'))
                        ->relationship()
                        ->orderColumn('position')
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                        ->schema([
                            Select::make('type')
                                ->label(__('filament-short-url::resources/bio-page.fields.block_type'))
                                ->options(collect(self::BLOCK_TYPES)->mapWithKeys(fn (string $type): array => [
                                    $type => __("filament-short-url::resources/bio-page.block_types.{$type}"),
                                ])->all())
                                ->default('link')
                                ->live()
                                ->required(),

                            TextInput::make('label')
                                ->label(__('filament-short-url::resources/bio-page.fields.block_label'))
                                ->visible(fn (Get $get): bool => $get('type') === 'link'),

                            TextInput::make('content.url')
                                ->label(__('filament-short-url::resources/bio-page.fields.block_url'))
                                ->url()
                                ->visible(fn (Get $get): bool => in_array($get('type'), ['link', 'image', 'video'], true)),

                            Textarea::make('content.body')
                                ->label(__('filament-short-url::resources/bio-page.fields.block_body'))
                                ->rows(2)
                                ->visible(fn (Get $get): bool => $get('type') === 'text'),

                            Toggle::make('is_enabled')
                                ->label(__('filament-short-url::resources/bio-page.fields.block_enabled'))
                                ->default(true),
                        ])
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
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
            ->actions([
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
        return [
            'index' => ListBioPages::route('/'),
            'create' => CreateBioPage::route('/create'),
            'edit' => EditBioPage::route('/{record}/edit'),
        ];
    }
}
