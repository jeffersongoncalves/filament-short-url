<?php

namespace JeffersonGoncalves\Filament\ShortUrl\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use JeffersonGoncalves\Filament\ShortUrl\FilamentShortUrlServiceProvider;
use JeffersonGoncalves\Filament\ShortUrl\Tests\Panel\AdminPanelProvider;
use JeffersonGoncalves\LaravelShortUrl\LaravelShortUrlServiceProvider;
use Livewire\LivewireServiceProvider;
use Livewire\Mechanisms\DataStore;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->shareEmptyErrorBag();

        // ShortUrl (from jeffersongoncalves/laravel-short-url) doesn't live under
        // this package's own Tests\Factories namespace, so it needs its own guess.
        Factory::guessFactoryNamesUsing(fn (string $modelName) => str_starts_with($modelName, 'JeffersonGoncalves\\LaravelShortUrl\\')
            ? 'JeffersonGoncalves\\LaravelShortUrl\\Database\\Factories\\'.class_basename($modelName).'Factory'
            : 'JeffersonGoncalves\\Filament\\ShortUrl\\Tests\\Factories\\'.class_basename($modelName).'Factory');
    }

    /**
     * Pre-populate the shared error bag so Livewire's SupportValidation hook
     * doesn't crash when running components outside the HTTP `web` middleware
     * stack (where ShareErrorsFromSession would normally do this).
     */
    protected function shareEmptyErrorBag(): void
    {
        $errors = tap(new ViewErrorBag)->put('default', new MessageBag);

        $this->app['session']->put('errors', $errors);
        $this->app['view']->share('errors', $errors);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            LaravelShortUrlServiceProvider::class,
            FilamentShortUrlServiceProvider::class,
            AdminPanelProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Livewire 4 + Testbench 10 bug: Mechanism::register() calls app()->instance()
        // too early, and the binding is overwritten before the singleton is needed.
        // Pin DataStore as a singleton so it persists across calls within a test.
        $app->singleton(DataStore::class);

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('session.driver', 'array');

        $app['config']->set('short-url.route.domain', 'short.test');
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $stubsPath = __DIR__.'/../vendor/jeffersongoncalves/laravel-short-url/database/migrations';
        $tempPath = sys_get_temp_dir().'/filament-short-url-migrations';

        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        foreach (glob($stubsPath.'/*.php.stub') as $stub) {
            copy($stub, $tempPath.'/'.basename(str_replace('.php.stub', '.php', $stub)));
        }

        $this->loadMigrationsFrom($tempPath);
    }
}
