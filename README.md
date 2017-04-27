# Nitseditor-Plugin-Environment

Environment for Nitseditor Plugin development

This package needs laravel installation, First install Laravel in your system, Now go to your installed directory and type:

    $ composer require noeticitservices/plugindev
    
Now add the following service provider in `config/app.php` file

    Nitseditor\System\Providers\NitsEditorServiceProvider::class,
    
You can check your installation by typing:
    
    http://localhost/your_project_folder/Nitseditor

You will get home page of nits editor.
    
Now there are few commands which you can utilise to create your plugins for example suppose you want to create blogs plugin then you can type:

    $ php artisan nitsPlugin:createPlugin
    
It will ask for the plugin name which needs to be implemented while entering it, this will create plugin folder inside the the package and create necessary files to run plugins.
For example we name our plugin as Blog
To make your plugins work you need to go to the package folder inside the `System/config.php`, and you need to define your plugin something like this:

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
    
For controllers you can type:

    $ php artisan nitsPlugin:makeController Blog
    
By default it will create BlogController inside `Plugins/Blogs/Controllers/BlogController`
