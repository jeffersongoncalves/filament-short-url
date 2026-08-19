<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions as ActionsComponent;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use JeffersonGoncalves\Filament\ShortUrl\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlPlugin;
use JeffersonGoncalves\LaravelShortUrl\Contracts\SettingsRepository;
use JeffersonGoncalves\LaravelShortUrl\Models\ShortUrl;

/**
 * Settings tabs are generated entirely from SettingsRepository::schema() —
 * one tab per distinct "group", one field per key. Registering a new
 * setting in the core makes it appear here without touching this class.
 */
class SettingsPage extends Page
{
    use HasPluginNavigationGroup;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament-short-url::pages.settings';

    public array $data = [];

    public function mount(): void
    {
        $repository = app(SettingsRepository::class);

        foreach ($repository->schema() as $key => $definition) {
            Arr::set($this->data, $key, $repository->get($key, $definition['default']));
        }
    }

    public static function canAccess(): bool
    {
        $callback = FilamentShortUrlPlugin::get()->getAuthorizeSettingsUsing();

        if ($callback !== null) {
            return (bool) $callback();
        }

        $policy = Gate::getPolicyFor(ShortUrl::class);

        if ($policy && method_exists($policy, 'manageSettings')) {
            return Gate::forUser(auth()->user())->allows('manageSettings', ShortUrl::class);
        }

        return parent::canAccess();
    }

    public function getTitle(): string
    {
        return __('filament-short-url::resources/settings.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-short-url::resources/settings.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Tabs::make('settings')
                    ->tabs($this->buildTabs()),

                ActionsComponent::make([
                    Action::make('save')
                        ->label(__('filament-short-url::resources/settings.save'))
                        ->color('primary')
                        ->action('save'),
                ]),
            ]);
    }

    /**
     * @return array<int, Tab>
     */
    protected function buildTabs(): array
    {
        $schema = app(SettingsRepository::class)->schema();

        $groups = collect($schema)
            ->map(fn (array $definition, string $key) => [...$definition, 'key' => $key])
            ->groupBy('group');

        return $groups->map(fn ($definitions, string $group): Tab => Tab::make(Str::of($group)->headline()->toString())
            ->schema($definitions->map($this->buildField(...))->all()))->values()->all();
    }

    protected function buildField(array $definition): Component
    {
        $key = $definition['key'];
        $rules = $definition['rules'] ?? [];

        return match ($definition['type']) {
            'boolean' => Toggle::make($key)
                ->label($definition['label'])
                ->rules($rules),
            'integer' => TextInput::make($key)
                ->label($definition['label'])
                ->numeric()
                ->rules($rules),
            default => TextInput::make($key)
                ->label($definition['label'])
                ->rules($rules),
        };
    }

    public function save(): void
    {
        $flat = Arr::dot($this->data);
        $repository = app(SettingsRepository::class);

        foreach ($repository->schema() as $key => $definition) {
            $repository->set($key, $flat[$key] ?? $definition['default']);
        }

        Notification::make()
            ->title(__('filament-short-url::resources/settings.saved'))
            ->success()
            ->send();
    }
}
