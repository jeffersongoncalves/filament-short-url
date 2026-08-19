<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\BioPageResource\Widgets\BlockAnalytics;
use JeffersonGoncalves\LaravelShortUrl\Models\BioPage;

class EditBioPage extends EditRecord
{
    protected static string $resource = BioPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label(__('filament-short-url::resources/bio-page.actions.preview'))
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (BioPage $record): string => BioPageResource::previewUrl($record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            BlockAnalytics::class,
        ];
    }
}
