<?php

namespace Nitseditor\System\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModelCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nitsPlugin:makeModel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for creation of Nitseditor Plugin\'s Model.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nitsPlugin:makeModel {modelName}';

    /**
     * Create a new command instance.
     *
     */

    private $basePath;

    public function __construct()
    {
        parent::__construct();
        $this->basePath = base_path();
        $this->directoryPath = $this->basePath. '/vendor/noeticitservices/plugindev/src/Plugins/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modelName = $this->argument('modelName');
        $plugins = $this->getPlugins();
        if(count($plugins) > 1)
        {
            $this->info('You have multiple plugins installed');
            $pluginName = $this->ask('Enter the plugin name');
            $path = $this->directoryPath . $pluginName .'/config.php';
            if(!File::exists($path))
            {
                $this->info('Plugin does not exists');
            }
            else
            {
                $modelPath = $this->directoryPath . $pluginName . '/Models/' . $modelName . '.php';
                File::put($modelPath, $this->makeModelContent($modelName, $pluginName));
            }
        }
        else
        {
            foreach($plugins as $plugin)
            {
                $modelPath = $plugin . '/Models/' . $modelName . '.php';
                $pluginName = str_replace($this->directoryPath, '', $plugin);
                File::put($modelPath, $this->makeModelContent($modelName, $pluginName));
            }
        }

    }

    public function getPlugins()
    {
        $list = File::directories($this->directoryPath);
        return $list;
    }

    public function makeModelContent($modelName, $pluginName)
    {
        return '<?php
namespace Nitseditor\Plugins' . $pluginName . '\Models;

use Nitseditor\System\Models\AbstractModel;

class '. $modelName .' extends AbstractModel {

}';
    }
}