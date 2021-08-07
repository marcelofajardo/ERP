<?php

namespace App\Console\Commands;

use App\User;
use App\UserRate;
use DB;
use Exception;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;

class ScrapLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraplogs:activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All scraplogs insert to the databases';

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

        $file_list = [];
        $searchVal = "";
        $dateVal = "";
        $file_list = [];
        // $files = \File::allFiles(env('SCRAP_LOGS_FOLDER'));
        $files = \File::allFiles(config('env.SCRAP_LOGS_FOLDER'));
        /*$date = empty($dateVal )? Carbon::now()->format('d') : sprintf("%02d", $dateVal);
        if($date == 01) 
        {
            $date = 32;
        }*/
        $yesterdayDate = date('j', strtotime("-1 days"));
        foreach ($files as $key => $val) {
            $day_of_file = explode('-', $val->getFilename());
            if(str_contains(end($day_of_file), $yesterdayDate) && (str_contains($val->getFilename(), $searchVal) || empty($searchVal))) {
                // $file_path_new = env('SCRAP_LOGS_FOLDER')."/".$val->getRelativepath()."/".$val->getFilename();
                $file_path_new = config('env.SCRAP_LOGS_FOLDER')."/".$val->getRelativepath()."/".$val->getFilename();

                $file = file($file_path_new);

                $log_msg = "";
                for ($i = max(0, count($file)-100); $i < count($file); $i++) {
                  $log_msg.=$file[$i];
                }
                if($log_msg == "")
                {
                    $log_msg = "Log data not found.";   
                }
                $file_path_info = pathinfo($val->getFilename());
                

                $search_scraper = substr($file_path_info['filename'], 0, -3);
                $search_scraper = str_replace("-", "_", $search_scraper);   
                $scrapers_info = DB::table('scrapers')
                    ->select('id')
                    ->where('scraper_name', 'like', $search_scraper)
                    ->get(); 
                
                if(count($scrapers_info) > 0)
                {
                    $scrap_logs_info = DB::table('scrap_logs')
                    ->select('id','scraper_id')
                    ->where('scraper_id', '=', $scrapers_info[0]->id)
                    ->get();
                    $scrapers_id = $scrapers_info[0]->id;
                }
                else
                {
                    $scrapers_id = 0;
                }
                    
                if(isset($scrap_logs_info) && count($scrap_logs_info) == 0)
                {
                    $file_list_data = array(
                        "scraper_id"=>$scrapers_id,
                        "folder_name"=>$val->getRelativepath(),
                        "file_name"=>$val->getFilename(),
                        "log_messages"=>$log_msg,
                        "created_at"=>date("Y-m-d H:i:s"),
                        "updated_at"=>date("Y-m-d H:i:s")
                    ); 
                    DB::table('scrap_logs')->insert($file_list_data);
                }
            }
        }
        //return  response()->json(["file_list" => $file_list]);
    }
}
