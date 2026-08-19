<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
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
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\CreateFolder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\EditFolder;
use JeffersonGoncalves\Filament\ShortUrl\Resources\FolderResource\Pages\ListFolders;
use JeffersonGoncalves\LaravelShortUrl\Models\Folder;

class FolderResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = Folder::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

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

    public static function table(Table $table): Table
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
            ->actions([
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
            'index' => ListFolders::route('/'),
            'create' => CreateFolder::route('/create'),
            'edit' => EditFolder::route('/{record}/edit'),
        ];
    }
}
