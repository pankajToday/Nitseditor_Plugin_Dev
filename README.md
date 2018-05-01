# Nitseditor-Plugin-Environment

[![Latest Stable Version](https://poser.pugx.org/noeticitservices/plugindev/v/stable)](https://packagist.org/packages/noeticitservices/plugindev)
[![Total Downloads](https://poser.pugx.org/noeticitservices/plugindev/downloads)](https://packagist.org/packages/noeticitservices/plugindev)
[![License](https://poser.pugx.org/noeticitservices/plugindev/license)](https://packagist.org/packages/noeticitservices/plugindev)
    
Environment for Nitseditor Plugin development

This package needs laravel installation, First install Laravel in your system, Now go to your installed directory and type:

    $ composer require noeticitservices/plugindev
    
Now add the following service provider in `config/app.php` file

    Nitseditor\System\Providers\NitsEditorServiceProvider::class,
    
Now publish the configuration files in `config` folder

    php artisan vendor:publish

# Configure passport setup, 

Configure your model with:

    <?php
    
    namespace Noetic\Plugins\blog\Models;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Laravel\Passport\HasApiTokens;
    
    class User extends Authenticatable
    {
       use Notifiable, HasApiTokens; 

In you config\auth.php

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],

        'nitseditor_blog' => [
            'driver' => 'passport',
            'provider' => 'blogUsers',
        ],
    ],
    
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    
        'blogUsers' => [
            'driver' => 'eloquent',
            'model' => Noetic\Plugins\blog\Models\User::class,
        ]
    ],

Register the middleware `AddCustomProvider` and `ConfigAccessTokenCustomProvider` on `app/Http/Kernel` `$middlewareGroups` attribute.

    'api' => [
            'throttle:60,1',
            'bindings',
            'nits-provider'
        ],

        'nits-provider' => [
            \Nitseditor\System\Middlewares\AddCustomProvider::class,
            \Nitseditor\System\Middlewares\ConfigAccessTokenCustomProvider::class,
        ]
    ];
    
Encapsulate the passport routes for access token with the registered middleware in `AuthServiceProvider`:

    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        // Middleware `api` that contains the `custom-provider` middleware group defined on $middlewareGroups above
        Route::group(['middleware' => 'api'], function () {
            Passport::routes(function ($router) {
                return $router->forAccessTokens();
            });
        });
    }

Now configure your `composer.json` file, you need define in psr-4 loader attributes, it should look something like this:

    "autoload": {
        "classmap": [
            "database"
        ],
        "psr-4": {
            "App\\": "app/",
            "Noetic\\Plugins\\": "plugins/"
        }
    },

You can check your installation by refreshing the home page, you will get home page of nits editor.
    
# Usage

Add the provider parameter in your request at /oauth/token:
    
    POST /oauth/token HTTP/1.1
    Host: localhost
    Accept: application/json, text/plain, */*
    Content-Type: application/json;charset=UTF-8
    Cache-Control: no-cache

    {
        "username":"user@domain.com",
        "password":"password",
        "grant_type" : "password",
        "client_id": "client-id",
        "client_secret" : "client-secret",
        "provider" : "blogUsers"
    }    

You can pass your guards on auth middleware as you wish. Example:

    Route::group(['middleware' => ['api', 'auth:nitseditor_blog']], function () {
        Route::get('/user', function ($request) {
            // Get the logged blog user instance
            return $request->user(); // You can use too `$request->user('nitseditor_blog')` passing the guard.
        });
    });    
    
Do migration of passport by command `php artisan migrate` and then write `php artisan passport:install` to do the initial installation

To seed plugins database you can do command `php artisan db:seed --class="Noetic\Plugins\blog\Databases\seeds\InstallDatabase"` and to uninstall you can do `php artisan db:seed --class="Noetic\Plugins\blog\Databases\seeds\InstallDatabase` where `blog` written in the class name is your plugin name. 
   
# php artisan nitsPlugin Commands:

There are few commands which you can utilise to create your plugins for example suppose you want to create blog plugin then you can type:

    $ php artisan nitsPlugin:createPlugin
    
It will ask for the plugin name which needs to be implemented while entering it, this will create plugin folder inside the the package and create necessary files to run plugins.
For example we name our plugin as `Blog`
To make your plugins work you need to go to the `config` folder and `nitseditor.php` file, and you need to define your plugin something like this:

    'packages' => [
        'blog'         => [
            'name'             => 'Blog', //This is the folder name which will be created, this should be exaclty same as name defined while creating plugin
            'description'      => 'NitsEditor Blog for Laravel 5.4', //A small description of project or plugin.
        ],
    ],
    
Now once the setup is done you can cross check the functionality by typing your plugin name as prefix to home router. for example in this case you can see

    http://localhost/your_project_folder/Blog/
    
You can see the page appearing and describing it is coming from the plugins folder.

Similarly you can have models by this command:

    $ php artisan nitsPlugin:makeModel Blog
    
You can check your file inside `Plugins/Blog/Models/` folder.    
    
For controllers you can type:

    $ php artisan nitsPlugin:makeController Blog
    
By default it will create `BlogController` class inside `Plugins/Blog/Controllers/BlogController` folder

You can create databases with tables by this command:

    $ php artisan nitsPlugin:createDatabase Blog table=blogs
    
By default it will make database with class `BlogTable` and table name as blog inside `Plugins/Blog/Databases/`
    
For migrating the database:
    
    $ php artisan migrate

This will by default take the up function inside `BlogTable` class and create the tables in the database.     
