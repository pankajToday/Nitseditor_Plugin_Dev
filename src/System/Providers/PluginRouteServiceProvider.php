<?php

namespace Nitseditor\System\Providers;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class PluginRouteServiceProvider extends RouteServiceProvider
{
    protected $packages = [];

    protected $namespace;

    protected $app;

    private $path;

    public function __construct(Application $app, $packages)
    {
        $this->packages = $packages;
        $this->path = base_path();
        $this->directoryPath = '/plugins/';
        parent::__construct($app);
    }


    public function boot()
    {
        parent::boot();
    }


    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAppApiRoutes();
    }


    protected function mapApiRoutes()
    {
        foreach ($this->packages as $package){

            $packageName = Arr::get($package, 'name');
            $namespace = 'Noetic\Plugins\\'. $packageName .'\Controllers';

            Route::prefix($packageName .'/api')
                ->middleware('api')
                ->namespace($namespace)
                ->group($this->path . $this->directoryPath . $packageName . '/Routes/api.php');
        }

    }

    /**
     * Define the "App API " routes for the  Mobile Application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAppApiRoutes()
    {
        foreach ($this->packages as $package) {

            $packageName = Arr::get($package, 'name');
            $namespace = 'Noetic\Plugins\\' . $packageName . '\Controllers';

            Route::prefix($packageName .'/api')
                ->middleware('api')
                ->namespace($namespace)
                ->group($this->path . $this->directoryPath . $packageName . '/Routes/appApi.php');
        }
    }





    protected function mapWebRoutes()
    {
        foreach ($this->packages as $package) {

            $packageName = Arr::get($package, 'name');
            $namespace = 'Noetic\Plugins\\' . $packageName . '\Controllers';

            Route::prefix($packageName)
                ->middleware('web')
                ->namespace($namespace)
                ->group($this->path . $this->directoryPath . $packageName . '/Routes/web.php');
        }
    }
}