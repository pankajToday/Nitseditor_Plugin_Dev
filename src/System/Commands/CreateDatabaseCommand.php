<?php

namespace Nitseditor\System\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateDatabaseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nitsPlugin:createDatabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for creation of Nitseditor Plugin\'s Database.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nitsPlugin:createDatabase {databaseName}';

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
        $this->directoryPath = $this->basePath . '\vendor\noeticitservices\plugindev\src\Plugins';
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
            $path = $this->directoryPath .'\\'. $pluginName .'\config.php';
            if(!File::exists($path))
            {
                $this->info('Plugin does not exists');
            }
            else
            {
                $dbPath = $this->directoryPath .'\\'. $pluginName . '\Databases\\' . $dbName . 'Table.php';
                File::put($dbPath, $this->makeDatabaseContent($dbName, $pluginName));
            }
        }
        else
        {
            foreach($plugins as $plugin)
            {
                $dbPath = $plugin . '\Databases\\' . $dbName . 'Table.php';
                $pluginName = str_replace($this->directoryPath, '', $plugin);
                File::put($dbPath, $this->makeDatabaseContent($dbName, $pluginName));
            }
        }

    }

    public function getPlugins()
    {
        $list = File::directories($this->directoryPath);
        return $list;
    }

    public function makeDatabaseContent($dbName, $pluginName)
    {
        return '<?php

namespace Nitseditor\Plugins'. $pluginName .'\Databases;        

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Nitseditor\System\Database\Migrations;

class '. $dbName .'Table extends Migrations
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public static function up()
    {
        Schema::create(\''. $dbName .'\', function (Blueprint $table) {
        
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public static function down()
    {
        Schema::dropIfExists(\''. $dbName .'\');
    }
}
    ';
    }
}