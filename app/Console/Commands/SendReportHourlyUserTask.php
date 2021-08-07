<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\Helpers\hubstaffTrait;
use App\Library\Hubstaff\Src\Hubstaff;
use Carbon\Carbon;
use DB;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SendReportHourlyUserTask extends Command
{
    use hubstaffTrait;

    private $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubstaff:send_report_hourly_user_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends hubstaff report to whatsapp based every hour if user not select task ';

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

            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $users = DB::table('hubstaff_activities')
                    ->select('hubstaff_activities.user_id','hubstaff_members.hubstaff_user_id','users.*')
                    ->leftJoin('hubstaff_members','hubstaff_activities.user_id','hubstaff_members.hubstaff_user_id')
                    ->leftJoin('users','hubstaff_members.user_id','users.id')
                    ->where('task_id',0)
                    ->whereDate('starts_at',date('Y-m-d'))
                    ->groupBy('user_id')
                    ->orderBy('id','desc')->get();
            \Log::info('Hubstaff task not select Total user : '.sizeof($users));
            foreach ($users as $key => $user) {
                if( $user->whatsapp_number ){
                    app('App\Http\Controllers\WhatsAppController')->sendWithWhatsApp($user->phone, $user->whatsapp_number, 'Please select task on hubstaff', true);
                }
            }

            // ChatMessage::sendWithChatApi('971502609192', null, $message);

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \Log::error('Hubstaff task not select Total user : '.$e->getMessage());
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
    
}
