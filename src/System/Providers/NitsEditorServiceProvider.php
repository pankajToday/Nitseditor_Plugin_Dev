<?php

namespace Nitseditor\System\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Nitseditor\System\Commands\CreateDatabaseCommand;
use Nitseditor\System\Commands\CreatePluginCommand;
use Nitseditor\System\Commands\MakeControllerCommand;
use Nitseditor\System\Commands\MakeModelCommand;

class NitsEditorServiceProvider extends ServiceProvider
{
    /**
     *   Bootstrapping the application services
     *
     *  @return void
     *
     */
    public function boot()
    {
        $this->app->register('Nitseditor\System\Providers\NitsRoutesServiceProvider');

        $this->publishes([
            __DIR__.'/../nitseditor.php' => config_path('nitseditor.php'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/../Views', 'NitsEditor');

    }

    /**
     *  Register application services
     *
     * @throws \Exception
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

        $config = config('nitseditor');
        $packages = Arr::get($config, 'packages', []);

        $routeDir = new PluginRouteServiceProvider($this->app, $packages);
        $this->app->register($routeDir);

        foreach ($packages as $package) {

            $packageName = Arr::get($package, 'name');

            $this->loadViewsFrom(base_path().'/plugins/'. $packageName .'/Views', $packageName);

            $this->loadMigrationsFrom(base_path().'/plugins/'. $packageName .'/Databases');

        }

        $this->app->singleton('nitseditor', function ($app)
        {
           return new NitsEditor;
        });
    }

    /**
     * Register Commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePluginCommand::class,
                MakeModelCommand::class,
                MakeControllerCommand::class,
                CreateDatabaseCommand::class,
            ]);
        }
    }
}