<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\PixelResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\LaravelShortUrl\Models\Pixel;
use JeffersonGoncalves\LaravelShortUrl\Registries\PixelProviderRegistry;

class PixelsTable
{
    public static function configure(Table $table): Table
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
            ->recordActions([
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
}
