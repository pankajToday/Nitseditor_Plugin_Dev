<?php

namespace Nitseditor\System\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Nitseditor\System\Commands\CreateDatabaseCommand;
use Nitseditor\System\Commands\CreatePluginCommand;
use Nitseditor\System\Commands\MakeControllerCommand;
use Nitseditor\System\Commands\MakeModelCommand;
use Nitseditor\System\Commands\MigrateDatabaseCommand;

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

        $this->loadViewsFrom(__DIR__ . '/../Views', 'NitsEditor');

        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'NitsEditor');
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

        $config = require __DIR__ . '/../config.php';
        $packages = Arr::get($config, 'packages', []);

        $routeDir = new PluginRouteServiceProvider($this->app, $packages);
        $this->app->register($routeDir);

        foreach ($packages as $package) {

            $packageName = Arr::get($package, 'name');

            $this->loadViewsFrom(__DIR__ . '/../../Plugins/'. $packageName .'/Views', $packageName);

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
        $this->registerCreatePluginCommands();
        $this->registerMakeModelCommands();
        $this->registerMakeControllerCommands();
        $this->registerCreateDatabaseCommands();
        $this->registerMigrateDatabaseCommands();
    }

    /**
     * Register the nitsPlugin:install Command.
     *
     * @return void
     */
    public function registerCreatePluginCommands()
    {
        $this->commands('command.nitsPlugin.createPlugin');
        $this->app->singleton('command.nitsPlugin.createPlugin', function ($app) {
            return new CreatePluginCommand();
        });
    }

    /**
     * Register the nitsPlugin:makeModel Command.
     *
     * @return void
     */
    public function registerMakeModelCommands()
    {
        $this->commands('command.nitsPlugin.makeModel');
        $this->app->singleton('command.nitsPlugin.makeModel', function ($app) {
            return new MakeModelCommand();
        });
    }

    /**
     * Register the nitsPlugin:makeController Command.
     *
     * @return void
     */
    public function registerMakeControllerCommands()
    {
        $this->commands('command.nitsPlugin.makeController');
        $this->app->singleton('command.nitsPlugin.makeController', function ($app) {
            return new MakeControllerCommand();
        });
    }

    /**
     * Register the nitsPlugin:createDatabase Command.
     *
     * @return void
     */
    public function registerCreateDatabaseCommands()
    {
        $this->commands('command.nitsPlugin.createDatabase');
        $this->app->singleton('command.nitsPlugin.createDatabase', function ($app) {
            return new CreateDatabaseCommand();
        });
    }

    /**
     * Register the nitsPlugin:migrateDatabase Command.
     *
     * @return void
     */
    public function registerMigrateDatabaseCommands()
    {
        $this->commands('command.nitsPlugin.migrateDatabase');
        $this->app->singleton('command.nitsPlugin.migrateDatabase', function ($app) {
            return new MigrateDatabaseCommand();
        });
    }

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return [
            'command.nitsPlugin.createPlugin',
            'command.nitsPlugin.makeModel',
            'command.nitsPlugin.makeController',
            'command.nitsPlugin.createDatabase',
            'command.nitsPlugin.migrateDatabase'
        ];
    }
}