<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\LaravelShortUrl\Exporters\CsvLinkExporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListShortUrls extends ListRecords
{
    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label(__('filament-short-url::resources/short-url.actions.export'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn (): StreamedResponse => response()->streamDownload(
                    fn () => print (app(CsvLinkExporter::class)->toCsvString()),
                    'short-urls.csv',
                    ['Content-Type' => 'text/csv'],
                )),

            CreateAction::make(),
        ];
    }
}
