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
use JeffersonGoncalves\Filament\ShortUrl\Resources\ShortUrlResource\Widgets\VariantsChart;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

class Statistics extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ShortUrlResource::class;

    protected static string $view = 'filament-short-url::pages.statistics';

    public ?string $from = null;

    public ?string $to = null;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->from = now()->subDays(30)->toDateString();
        $this->to = now()->toDateString();
    }

    public function getTitle(): string
    {
        $record = $this->getRecordForTitle();

        return filled($record->title) ? $record->title : $record->fullUrl();
    }

    protected function getRecordForTitle(): ShortUrl
    {
        /** @var ShortUrl $record */
        $record = $this->getRecord();

        return $record;
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->label(__('filament-short-url::resources/short-url.actions.edit'))
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->url(fn (): string => ShortUrlResource::getUrl('edit', ['record' => $this->getRecord()])),

            Action::make('copy')
                ->label(__('filament-short-url::resources/short-url.actions.copy'))
                ->icon('heroicon-o-clipboard-document')
                ->color('gray')
                ->extraAttributes(fn (): array => [
                    'x-on:click' => 'window.navigator.clipboard.writeText('.static::jsQuote($this->getRecordForTitle()->fullUrl()).');'
                        .'$tooltip('.static::jsQuote(__('filament-short-url::resources/short-url.actions.copied')).', { theme: $store.theme, timeout: 2000 })',
                ]),

            Action::make('period')
                ->label(__('filament-short-url::resources/short-url.stats.period'))
                ->icon('heroicon-o-calendar')
                ->form([
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
            VariantsChart::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 2;
    }
}
