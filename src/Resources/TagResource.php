<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Pages\CreateTag;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Pages\EditTag;
use JeffersonGoncalves\Filament\ShortUrl\Resources\TagResource\Pages\ListTags;
use JeffersonGoncalves\LaravelShortUrl\Models\Tag;

class TagResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form->schema(static::fields());
    }

    /**
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament-short-url::resources/tag.fields.name'))
                ->required()
                ->maxLength(255),

            ColorPicker::make('color')
                ->label(__('filament-short-url::resources/tag.fields.color')),
        ];
    }

    public static function table(Table $table): Table
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
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
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
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }
}
