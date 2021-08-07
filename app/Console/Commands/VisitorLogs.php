<?php

namespace App\Console\Commands;

use App\VisitorLog;
use Illuminate\Console\Command;

class VisitorLogs extends Command
{
    const LIVE_CHAT_CREDNTIAL = "NmY0M2ZkZDUtOTkwMC00OWY4LWI4M2ItZThkYzg2ZmU3ODcyOmRhbDp0UkFQdWZUclFlLVRkQUI4Y2pFajNn";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitor:logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the visitor Log From The Live Chat and save it , this api hit should be continous like in 5 mins to get accurate data from LiveChat';

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
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://api.livechatinc.com/v2/visitors",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => array(
                    "Authorization: Basic ".self::LIVE_CHAT_CREDNTIAL,
                ),
            ));

            $response = curl_exec($curl);
            $err      = curl_error($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {

                $logs = json_decode($response);
                if (count($logs) != 0) {
                    foreach ($logs as $log) {

                        $logExist = VisitorLog::where('ip', $log->ip)->whereDate('last_visit', '<=', $log->last_visit)->first();
                        if ($logExist == null) {
                            $logSave           = new VisitorLog();
                            $logSave->ip       = $log->ip;
                            $logSave->browser  = $log->browser;
                            $logSave->location = $log->city . ' ' . $log->region . ' ' . $log->country . ' ' . $log->country_code;
                            foreach ($log->visit_path as $path) {
                                $pathArray[] = $path->page;
                            }
                            $logSave->page          = json_encode($pathArray);
                            $logSave->visits        = $log->visits;
                            $logSave->last_visit    = $log->last_visit;
                            $logSave->page_current  = $log->page_current;
                            $logSave->chats         = $log->chats;
                            $logSave->customer_name = $log->name;
                            $logSave->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
