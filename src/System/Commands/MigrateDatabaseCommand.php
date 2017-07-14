<?php

namespace Nitseditor\System\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateDatabaseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nitsPlugin:migrateDatabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for migration of Nitseditor Plugin\'s Database.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nitsPlugin:migrateDatabase {databaseName}';

    /**
     * Retrieving base path into variable
     *
     * @var string
     */
    private $basePath;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->basePath = base_path();
        $this->directoryPath = $this->basePath . '/plugins/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dbName = $this->argument('databaseName');
        $plugins = $this->getPlugins();
        if(count($plugins) > 1)
        {
            $this->info('You have multiple plugins installed');
            $pluginName = $this->ask('Enter the plugin name');
            $path = $this->directoryPath .'/'. $pluginName .'/nitseditor.php';
            if(!File::exists($path))
            {
                $this->info('Plugin does not exists');
            }
            else
            {
                call_user_func(array('Nitseditor\Plugins' . $pluginName.'\Databases\\' . $dbName . 'Table', 'up'));
            }
        }
        else
        {
            foreach($plugins as $plugin)
            {
                $pluginName = str_replace($this->directoryPath, '', $plugin);
                call_user_func(array('Nitseditor\Plugins' . $pluginName.'\Databases\\' . $dbName . 'Table', 'up'));
                $this->info('Migrated successfully');
            }
        }
    }

    public function getPlugins()
    {
        $list = File::directories($this->directoryPath);
        return $list;
    }
}