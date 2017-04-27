<?php

namespace Nitseditor\System\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


class CreatePluginCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nitsPlugin:createPlugin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for creation of Nitseditor Plugin.';

    /**
     * Create a new command instance.
     *
     */

    private $basePath;


    public function __construct()
    {
        parent::__construct();
        $this->basePath = base_path();
        $this->directoryPath = $this->basePath. '\vendor\noeticitservices\plugindev\src\Plugins\\';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Create plugins for NitsEditor');
        $pluginName = $this->ask('Tell us your plugin name:');

        $path = $this->directoryPath . $pluginName .'\config.php';
        if(!File::exists($path))
        {
            if(!File::isDirectory($this->directoryPath))
            {
                File::makeDirectory($this->directoryPath);
            }
            $folderStatus = $this->makeFolders($pluginName);
            $fileStatus = $this->makeFiles($pluginName);

            if($fileStatus == true && $folderStatus == true)
            {
                $this->info('Your plugin is created, and default files has also being created');
            }
            else
            {
                $this->info('There is some error in making plugin, delete your plugin folder and try again');
            }

        }
        else
        {
            $this->info('Error this plugin already exists');
        }
    }

    public function makeFolders($pluginName)
    {
        File::makeDirectory($this->directoryPath . $pluginName);
        File::makeDirectory($this->directoryPath . $pluginName .'\Controllers');
        File::makeDirectory($this->directoryPath . $pluginName .'\Controllers\Middlewares');
        File::makeDirectory($this->directoryPath . $pluginName .'\Routes');
        File::makeDirectory($this->directoryPath . $pluginName .'\Views');
        File::makeDirectory($this->directoryPath . $pluginName .'\Models');
        File::makeDirectory($this->directoryPath . $pluginName .'\Databases');
        File::makeDirectory($this->directoryPath . $pluginName .'\Events');
        File::makeDirectory($this->directoryPath . $pluginName .'\Exceptions');
        File::makeDirectory($this->directoryPath . $pluginName .'\Listeners');
        File::makeDirectory($this->directoryPath . $pluginName .'\Providers');
        return true;
    }

    public function makeFiles($pluginName)
    {
        File::put($this->directoryPath . $pluginName .'\Routes\web.php', $this->makeWebRoutesContent($pluginName));
        File::put($this->directoryPath . $pluginName .'\Routes\api.php', $this->makeWebRoutesContent($pluginName));
        File::put($this->directoryPath . $pluginName .'\config.php', 'Hello');
        File::put($this->directoryPath . $pluginName .'\Views\home.blade.php', $this->makeViewsContent($pluginName));
        File::put($this->directoryPath . $pluginName .'\Controllers\HomeController.php', $this->makeControllerContents($pluginName));
        return true;
    }

    public function makeWebRoutesContent($pluginName)
    {
        return '<?php

Route::get(\'/\', [\'as\' => \'' . $pluginName. '\', \'uses\' => \'HomeController@index\']);';

    }

    public function makeViewsContent($pluginName)
    {
        return '<html>
    <h1>Hello I\'m coming from Nitseditor ' . $pluginName . ' plugins folder</h1>
</html>';
    }

    public function makeControllerContents($pluginName)
    {
        return '<?php

namespace Nitseditor\Plugins\\' . $pluginName . '\Controllers;
        
              
use App\Http\Controllers\Controller;
        
class HomeController extends Controller
{
    public function index()
    {
        return view(\'' . $pluginName . '::home\');
    }
}';
    }
}