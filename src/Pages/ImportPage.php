<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\LaravelShortUrl\Registries\ImporterDriverRegistry;

/**
 * Upload/source -> dry-run preview -> import -> report. Column mapping is
 * not exposed here: the core's ImporterDriver contract takes only a source
 * string (file path or API identifier) and decides its own column shape
 * internally (see CsvImporterDriver/BitlyImporterDriver) — there's no
 * mapping mechanism in the core to build a UI for.
 */
class ImportPage extends Page
{
    use HasPluginNavigationGroup;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    public array $data = [
        'driver' => null,
        'file' => null,
        'source' => null,
    ];

    /** @var array{totalRows: int, sampleRows: array<int, array<string, mixed>>, columns: array<int, string>, warnings: array<int, string>}|null */
    public ?array $preview = null;

    /** @var array{imported: int, skipped: int, failed: int, errors: array<int, string>}|null */
    public ?array $report = null;

    public function getTitle(): string
    {
        return __('filament-short-url::resources/import.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-short-url::resources/import.title');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Select::make('driver')
                    ->label(__('filament-short-url::resources/import.driver'))
                    ->options(fn (): array => collect(app(ImporterDriverRegistry::class)->names())
                        ->mapWithKeys(fn (string $name): array => [$name => ucfirst($name)])
                        ->all())
                    ->live()
                    ->required(),

                FileUpload::make('file')
                    ->label(__('filament-short-url::resources/import.file'))
                    ->disk('local')
                    ->directory('short-url-imports')
                    ->visibility('private')
                    ->acceptedFileTypes(['text/csv', 'text/plain'])
                    ->visible(fn (Get $get): bool => $get('driver') === 'csv')
                    ->required(fn (Get $get): bool => $get('driver') === 'csv'),

                TextInput::make('source')
                    ->label(__('filament-short-url::resources/import.source'))
                    ->helperText(__('filament-short-url::resources/import.source_helper'))
                    ->visible(fn (Get $get): bool => filled($get('driver')) && $get('driver') !== 'csv')
                    ->required(fn (Get $get): bool => filled($get('driver')) && $get('driver') !== 'csv'),

                View::make('filament-short-url::pages.import-results')
                    ->view('filament-short-url::pages.import-results', fn (): array => [
                        'preview' => $this->preview,
                        'report' => $this->report,
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label(__('filament-short-url::resources/import.preview'))
                ->action('runPreview'),

            Action::make('import')
                ->label(__('filament-short-url::resources/import.import'))
                ->color('primary')
                ->visible(fn (): bool => $this->preview !== null)
                ->action('runImport'),
        ];
    }

    public function runPreview(): void
    {
        $this->report = null;
        $preview = app(ImporterDriverRegistry::class)
            ->driver($this->data['driver'] ?? '')
            ?->preview($this->resolveSource());

        $this->preview = $preview === null ? null : [
            'totalRows' => $preview->totalRows,
            'sampleRows' => $preview->sampleRows,
            'columns' => $preview->columns,
            'warnings' => $preview->warnings,
        ];
    }

    public function runImport(): void
    {
        $report = app(ImporterDriverRegistry::class)
            ->driver($this->data['driver'] ?? '')
            ?->import($this->resolveSource());

        $this->report = $report === null ? null : [
            'imported' => $report->imported,
            'skipped' => $report->skipped,
            'failed' => $report->failed,
            'errors' => $report->errors,
        ];

        $this->preview = null;
    }

    protected function resolveSource(): string
    {
        if (($this->data['driver'] ?? null) === 'csv') {
            $path = $this->data['file'] ?? null;

            return $path ? Storage::disk('local')->path($path) : '';
        }

        return (string) ($this->data['source'] ?? '');
    }
}
