<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\BrowsersChart;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\CitiesList;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\CountriesList;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\DevicesChart;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\HourlyChart;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\LanguagesList;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\OperatingSystemsChart;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\ReferrerTypesChart;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\StatsOverview;
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\UtmFunnel;

class Statistics extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ShortUrlResource::class;

    public ?string $from = null;

    public ?string $to = null;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->from = now()->subDays(30)->toDateString();
        $this->to = now()->toDateString();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('period')
                ->label(__('filament-short-url::resources/short-url.stats.period'))
                ->icon('heroicon-o-calendar')
                ->schema([
                    DatePicker::make('from')
                        ->label(__('filament-short-url::resources/short-url.stats.from'))
                        ->maxDate(fn (): Carbon => now())
                        ->default(fn (): string => $this->from),
                    DatePicker::make('to')
                        ->label(__('filament-short-url::resources/short-url.stats.to'))
                        ->maxDate(fn (): Carbon => now())
                        ->default(fn (): string => $this->to),
                ])
                ->action(function (array $data): void {
                    $this->from = $data['from'];
                    $this->to = $data['to'];
                }),
        ];
    }

    public function getWidgetData(): array
    {
        return [
            'record' => $this->getRecord(),
            'from' => $this->from,
            'to' => $this->to,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            HourlyChart::class,
            DevicesChart::class,
            BrowsersChart::class,
            OperatingSystemsChart::class,
            CountriesList::class,
            CitiesList::class,
            ReferrerTypesChart::class,
            LanguagesList::class,
            UtmFunnel::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 2;
    }
}
