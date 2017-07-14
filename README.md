# Nitseditor-Plugin-Environment

[![Build Status](https://travis-ci.org/noeticit/Nitseditor_Plugin_Dev.svg?branch=master)](https://travis-ci.org/noeticit/Nitseditor_Plugin_Dev)
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

You can check your installation by refreshing the home page, you will get home page of nits editor.
    
# php artisan nitsPlugin Commands:

There are few commands which you can utilise to create your plugins for example suppose you want to create blogs plugin then you can type:

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

    $ php artisan nitsPlguin:makeModel Blog
    
You can check your file inside `Plugins/Blog/Models/` folder.    
    
For controllers you can type:

    $ php artisan nitsPlugin:makeController Blog
    
By default it will create `BlogController` class inside `Plugins/Blog/Controllers/BlogController` folder

You can create databases by this command:

    $ php artisan nitsPlugin:createDatabase Blog
    
By default it will make database with class `BlogTable` inside `Plugins/Blog/Databases/`
    
For migrating the database:
    
    $ php artisan nitsPlugin:migrateDatabase Blog

This will by default take the up function inside `BlogTable` class and create the tables in the database.     
