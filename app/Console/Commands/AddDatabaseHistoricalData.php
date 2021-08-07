<?php

namespace App\Console\Commands;

use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\DatabaseHistoricalRecord;
use App\DatabaseTableHistoricalRecord;
use App\ChatMessage;
use Illuminate\Support\Facades\DB;

class AddDatabaseHistoricalData extends Command
{

    CONST MAX_REACH_LIMIT = 100;
    CONST MAX_REACH_TOTAL_LIMIT = 4096;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:historical-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert historical data';

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
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // get the historical data and store into the new table
            $db = \DB::select('SELECT table_schema as "db_name",Round(Sum(data_length + index_length) / 1024 / 1024, 1) as "db_size"
                FROM information_schema.tables  where table_schema = "'.env('DB_DATABASE', 'solo').'" GROUP  BY table_schema'
            );

            $lastDb = DatabaseHistoricalRecord::where("database_name",env('DB_DATABASE', 'solo'))->latest()->first();

            if(!empty($db)) {
                foreach($db as $d) {

                    // check the last db size and current size and manage with it 
                    if($lastDb) {
                        if($lastDb->database_name == $d->db_name) {
                            if(($d->db_size - $lastDb->size) >= self::MAX_REACH_LIMIT) {
                                \App\CronJob::insertLastError($this->signature,
                                    "Database is reached to the max limit : ".self::MAX_REACH_LIMIT. " MB"
                                );
                            }else if($d->db_size > self::MAX_REACH_TOTAL_LIMIT) {
                                \App\CronJob::insertLastError($this->signature,
                                    "Database is reached to the max total limit : ".self::MAX_REACH_TOTAL_LIMIT. " MB"
                                );
                            }   
                        }
                    }

                    $database_recent_entry = DatabaseHistoricalRecord::create([
                        "database_name" => $d->db_name,
                        "size" => $d->db_size,
                    ]);
                    $db_table = \DB::select('SELECT TABLE_NAME as "db_table_name", Round(Sum(data_length + index_length) / 1024, 1) as "db_size" FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = "BASE TABLE" AND TABLE_SCHEMA="'.$d->db_name.'" GROUP  BY TABLE_NAME'
                    );
                    foreach($db_table as $d_table) {
                        $databaseTableHistoricalRecord = DatabaseTableHistoricalRecord::where("database_name",$d_table->db_table_name)->where("database_id", $database_recent_entry->id)->orderBy('created_at','ASC')->first();
                        if($databaseTableHistoricalRecord){
                            $v1 = $databaseTableHistoricalRecord->size;
                            $v2 = $d_table->db_size;
                            $differance = (($v1 - $v2)/(($v1+$v2)/2))*100;
                            if($differance > 10){
                                $user_id = 6;
                                $message = $d->db_name.".".$d_table->db_table_name." Database table increased size more than 10%.";
                                $params = [];
                                $params['message'] = $message;
                                $params['erp_user'] = $user_id;
                                $params['user_id'] = $user_id;
                                $params['approved'] = 1;
                                $params['status'] = 2;
                                $params['message_application_id'] = 10001;
                                $chat_message = ChatMessage::create($params);

                                $requestData = new Request();
                                $requestData->setMethod('POST');
                                $requestData->request->add(['user_id' => $user_id, 'message' => $message, 'status' => 1]);
                                app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'user');
                            }
                        }
                        DatabaseTableHistoricalRecord::create([
                            "database_name" => $d_table->db_table_name,
                            "size" => $d_table->db_size,
                            "database_id" => $database_recent_entry->id,
                        ]);
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
