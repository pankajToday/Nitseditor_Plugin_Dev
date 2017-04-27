<?php

namespace Nitseditor\System\Providers;


use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class NitsRoutesServiceProvider extends RouteServiceProvider
{
    protected $namespace='Nitseditor\System\Controllers';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }


    protected function mapApiRoutes()
    {
        Route::prefix('Nitseditor\api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__ . '\..\Routes\api.php');
    }

    protected function mapWebRoutes()
    {
        Route::prefix('Nitseditor')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '\..\Routes\web.php');
    }
}