<?php

namespace App\Console\Commands;

use App\ProjectFileManager;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class ProjectDirectory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project_directory:manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It go through with all directories and update to db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*$cc = app()->make('App\Http\Controllers\ProjectFileManagerController');
        app()->call([$cc, 'listTree'], []);*/
        // start throgh tree program
        $output = shell_exec("tree --du -h . -f -L 6 --sort=size|grep 'M]\|G]' | egrep -v '[0-9]*\.[0-9]M'");
        if (!empty($output)) {
            $lastFolder = null;
            $this->info("Output : " . $output);
            foreach (explode(PHP_EOL, $output) as $o) {
                $directory = explode("]", $o);
                if (isset($directory[0]) && isset($directory[1])) {
                    $directoryStr = trim($directory[1]);
                    $parent       = null;
                    if (substr($directoryStr, 0, strlen($lastFolder)) == $lastFolder) {
                        $parent = $lastFolder;
                    }

                    $size = explode(']', (explode('[', $o)[1]))[0];

                    $projectManager = ProjectFileManager::where("name",$directoryStr)->first();
                    if($projectManager) {
                        $projectManager->size = trim($size);
                        $projectManager->save();
                        if(str_replace("M","",$projectManager->size) > str_replace("M","",$projectManager->notification_at) && !empty($projectManager->notification_at)) {
                            $requestData = new Request(); 
                            $requestData->setMethod('POST'); 
                            $requestData->request->add([ 'priority' => 1, 'issue' => "Error With folder size {$directoryStr} which is more then {$projectManager->size} and expected size is {$projectManager->notification_at}", 'status' => "Planned", 'module' => "cron", 'subject' => "Error With folder size {$directoryStr}", 'assigned_to' => 6 ]); 
                            app('App\Http\Controllers\DevelopmentController')->issueStore($requestData, 'issue');
                        }
                    }else{
                        $ProjectFileManager = ProjectFileManager::create(['name' => $directoryStr, "project_name" => "erp", "size" => trim($size), "parent" => $parent]);
                    }
                    $lastFolder = $directoryStr;
                }
            }
        }

    }
}
