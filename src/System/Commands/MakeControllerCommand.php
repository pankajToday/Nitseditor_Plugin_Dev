<?php

namespace Nitseditor\System\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeControllerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nitsPlugin:makeController';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for creation of Nitseditor Plugin\'s Controller.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nitsPlugin:makeController {controllerName}';

    /**
     * Create a new command instance.
     *
     */

    private $basePath;

    public function __construct()
    {
        parent::__construct();
        $this->basePath = base_path();
        $this->directoryPath = $this->basePath. '/plugins/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $controllerName = $this->argument('controllerName');
        $plugins = $this->getPlugins();
        if(count($plugins) > 1)
        {
            $this->info('You have multiple plugins installed');
            $pluginName = $this->ask('Enter the plugin name');
            $path = $this->directoryPath . $pluginName .'/nitseditor.php';
            if(!File::exists($path))
            {
                $this->info('Plugin does not exists');
            }
            else
            {
                $controllerPath = $this->directoryPath . $pluginName . '/Controllers/' . $controllerName . 'Controller.php';
                File::put($controllerPath, $this->makeControllerContent($controllerName, $pluginName));
            }
        }
        else
        {
            foreach($plugins as $plugin)
            {
                $controllerPath = $plugin . '/Controllers/' . $controllerName . 'Controller.php';
                $pluginName = str_replace($this->directoryPath, '', $plugin);
                File::put($controllerPath, $this->makeControllerContent($controllerName, $pluginName));
            }
        }

    }

    public function getPlugins()
    {
        $list = File::directories($this->directoryPath);
        return $list;
    }

    public function makeControllerContent($controllerName, $pluginName)
    {
        return '<?php

namespace Noetic\Plugins' . $pluginName . '\Controllers;
        
              
use App\Http\Controllers\Controller;
        
class '. $controllerName .'Controller extends Controller
{
    
}';
    }
}