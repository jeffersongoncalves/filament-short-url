<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\CreatePixel;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\EditPixel;
use JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Pages\ListPixels;
use JeffersonGoncalves\LaravelShortUrl\Models\Pixel;
use JeffersonGoncalves\LaravelShortUrl\Registries\PixelProviderRegistry;

class PixelResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = Pixel::class;

    protected static ?string $navigationIcon = 'heroicon-o-viewfinder-circle';

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
                ->label(__('filament-short-url::resources/pixel.fields.name'))
                ->required()
                ->maxLength(255),

            Select::make('provider_key')
                ->label(__('filament-short-url::resources/pixel.fields.provider'))
                ->options(fn (): array => collect(app(PixelProviderRegistry::class)->all())
                    ->mapWithKeys(fn ($provider, $key) => [$key => $provider->label])
                    ->all())
                ->live()
                ->required(),

            ...static::configFields(),
        ];
    }

    /**
     * @return array<int, TextInput>
     */
    protected static function configFields(): array
    {
        $maxFields = 6;

        return collect(range(0, $maxFields - 1))
            ->map(fn (int $index): TextInput => TextInput::make("config_field_{$index}")
                ->label(fn (Get $get): string => static::fieldAt($get('provider_key'), $index)['label'] ?? '')
                ->visible(fn (Get $get): bool => static::fieldAt($get('provider_key'), $index) !== null)
                ->dehydrated(fn (Get $get): bool => static::fieldAt($get('provider_key'), $index) !== null))
            ->all();
    }

    /**
     * @return array{key: string, label: string, type: string}|null
     */
    protected static function fieldAt(?string $providerKey, int $index): ?array
    {
        if (! $providerKey) {
            return null;
        }

        $provider = app(PixelProviderRegistry::class)->get($providerKey);

        return $provider?->configFields[$index] ?? null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-short-url::resources/pixel.fields.name'))
                    ->searchable(),

                TextColumn::make('provider_key')
                    ->label(__('filament-short-url::resources/pixel.fields.provider'))
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        $provider = app(PixelProviderRegistry::class)->get($state);

                        return $provider === null ? $state : $provider->label;
                    }),
            ])
            ->actions([
                Action::make('test')
                    ->label(__('filament-short-url::resources/pixel.actions.test'))
                    ->icon('heroicon-o-play')
                    ->color('gray')
                    ->modalHeading(__('filament-short-url::resources/pixel.actions.test'))
                    ->modalContent(fn (Pixel $record) => view('filament-short-url::components.payload-preview', [
                        'payload' => ['script' => app(PixelProviderRegistry::class)->get($record->provider_key)?->render($record->config)],
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel(__('filament-short-url::resources/custom-domain.actions.close')),
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
            'index' => ListPixels::route('/'),
            'create' => CreatePixel::route('/create'),
            'edit' => EditPixel::route('/{record}/edit'),
        ];
    }
}
