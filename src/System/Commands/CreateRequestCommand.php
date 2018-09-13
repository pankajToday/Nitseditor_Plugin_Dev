<?php

namespace Nitseditor\System\Commands;


use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateRequestCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nitsPlugin:createRequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for creation of Nitseditor Plugin\'s Request.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nitsPlugin:createRequest {requestName}';

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
        $requestName = $this->argument('requestName');
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
                $reqPath = $this->directoryPath .'/'. $pluginName . '/Requests/'.$requestName . '.php';
                File::put($reqPath, $this->makeRequestContent($requestName, $pluginName ));
            }
        }
        else
        {
            foreach($plugins as $plugin)
            {
                $reqPath = $plugin .'/Requests/' . $requestName . '.php';
                $pluginName = str_replace($this->directoryPath, '', $plugin);
                File::put($reqPath, $this->makeRequestContent($requestName, $pluginName));
            }
        }

    }

    public function getPlugins()
    {
        $list = File::directories($this->directoryPath);
        return $list;
    }

    public function makeRequestContent($requestName, $pluginName)
    {
        return '<?php

namespace Noetic\Plugins' . $pluginName . '\Requests;

use Illuminate\Foundation\Http\FormRequest;

class '. $requestName .' extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

    ';
    }
}