<?php

namespace App\Console\Commands;

use App\LaravelLog;
use File;
use Illuminate\Console\Command;

class ParseLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Laravel Log';

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
        try {
            $path = storage_path('logs');
            $logs = File::allfiles($path);
            foreach ($logs as $log) {
                $filename = $log->getFilename();
                //Getting Only Laravel FIle from Log Table
                if (strpos($filename, 'laravel') !== false) {

                } else {
                    continue;
                }
                $content = File::get($log);

                preg_match_all("/\[(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})\](.*)/", $content, $match);

                foreach ($match[0] as $value) {
                    preg_match_all("/\[([^\]]*)\]/", $value, $datetime);
                    $value = str_replace($datetime[1][0], '', $value);
                    $value = str_replace('[]', '', $value);

                    $dateTime = $datetime[1][0];

                    $alreadyLogged = LaravelLog::where('log_created', $dateTime)->first();

                    if ($alreadyLogged != null && $alreadyLogged != '') {
                        continue;
                    }



                    if (strpos($value, 'local.ERROR') !== false) {

                        //Check if already exist and update the time
                        $loggedBefore = LaravelLog::where('log', $value)->first();
                        if(empty($loggedBefore)){
                            $log              = new LaravelLog();
                            $log->log_created = $dateTime;
                            $log->filename    = $filename;
                            $log->log         = $value;
                            $log->save();
                        }else{
                            $loggedBefore->touch();
                        }
                        
                    }

                }

            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
