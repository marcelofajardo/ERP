<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

use App\User;
use App\HubstaffActivityByPaymentFrequency;
use App\Http\Controllers\HubstaffActivitiesController;
use App\Mails\Manual\HubstuffActivitySendMail;

use Carbon\Carbon;
use Mail;
use Auth;
use Illuminate\Support\Facades\Log;



class HubstuffActivityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HubstuffActivity:Command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Log::channel('hubstaff_activity_command')->info('HubstuffActivityCommand() command started');
        $tasks_controller = new HubstaffActivitiesController;
       
        $users = User::where('payment_frequency', '!=' ,'')->get();
        $today = Carbon::now()->toDateTimeString();
        Log::channel('hubstaff_activity_command')->info('users => ' . \json_encode($users));
        
        foreach ($users as $key => $user) {
            
            $payment_frequency = $user->payment_frequency;
            $last_mail_sent = $user->last_mail_sent_payment;

            $to = Carbon::now()->startOfMonth();
            Log::channel('hubstaff_activity_command')->info('payment_frequency => ' .json_encode( $payment_frequency ). ' last_mail_sent => ' . json_encode($last_mail_sent));

            if ($last_mail_sent) {
                $to = Carbon::createFromFormat('Y-m-d H:s:i', $last_mail_sent);
            }
            $from = Carbon::createFromFormat('Y-m-d H:s:i', $today);
            $diff_in_days = $to->diffInDays($from);

            $req = new Request;  
            $req->request->add(["activity_command" => true]);
            $req->request->add(["user" => $user]);
            $req->request->add(["user_id" => $user->id]);
            $req->request->add(["developer_task_id" => null]);
            $req->request->add(["task_id" => null]);
            $req->request->add(["task_status" => null]);
            $req->request->add(["start_date" => $to]);
            $req->request->add(["end_date" => $from]);
            $req->request->add(["status" => null]);
            $req->request->add(["submit" => "report_download"]);

            Log::channel('hubstaff_activity_command')->info('before call get activity users ');

            $res = $tasks_controller->getActivityUsers($req, $req);

            Log::channel('hubstaff_activity_command')->info('get activity users '.json_encode($res));



            $path = null;

            $data["email"] = $user->email;
            $data["title"] = "Hubstuff Activities Report";

            if($payment_frequency == "weekly"){
                Log::channel('hubstaff_activity_command')->info('check; payment frequecy is weekly');

                if ($diff_in_days == 7) {
                    Log::channel('hubstaff_activity_command')->info('check; difff in day is 7');

                    $res = $tasks_controller->getActivityUsers($req, $req);
                    Log::channel('hubstaff_activity_command')->info('get activit users' .json_encode($res));

                    $z = (array) $res;

                    foreach($z as $zz){
                        Log::channel('hubstaff_activity_command')->info('convert res in array and foreach condition' .json_encode($zz));
                        if($path == null){
                            Log::channel('hubstaff_activity_command')->info('path is not null' .json_encode($path));

                            $path = $zz->getRealPath();
                            Log::channel('hubstaff_activity_command')->info('get real path' .json_encode($path));

                        }
                    }
                }
            }

            if($payment_frequency == "biweekkly"){
                Log::channel('hubstaff_activity_command')->info('check; payment frequecy is biweekkly');

                if ($diff_in_days == 14) {
                    Log::channel('hubstaff_activity_command')->info('check; difff in day is 14');

                    $res = $tasks_controller->getActivityUsers($req, $req);
                    Log::channel('hubstaff_activity_command')->info('get activit users' .json_encode($res));

                    $z = (array) $res;

                    foreach($z as $zz){
                        Log::channel('hubstaff_activity_command')->info('convert res in array and foreach condition' .json_encode($zz));

                        if($path == null){
                            Log::channel('hubstaff_activity_command')->info('path is not null' .json_encode($path));

                            $path = $zz->getRealPath();
                            Log::channel('hubstaff_activity_command')->info('get real path' .json_encode($path));                        }
                    }
                }
            }

            if($payment_frequency == "fornightly"){
                Log::channel('hubstaff_activity_command')->info('check; payment frequecy is fornightly');

                if ($diff_in_days == 15) {
                    Log::channel('hubstaff_activity_command')->info('check; difff in day is 15');

                    $res = $tasks_controller->getActivityUsers($req, $req);
                    Log::channel('hubstaff_activity_command')->info('get activit users' .json_encode($res));

                    $z = (array) $res;

                    foreach($z as $zz){
                        Log::channel('hubstaff_activity_command')->info('convert res in array and foreach condition' .json_encode($zz));

                        if($path == null){
                            Log::channel('hubstaff_activity_command')->info('path is not null' .json_encode($path));

                            $path = $zz->getRealPath();
                            Log::channel('hubstaff_activity_command')->info('get real path' .json_encode($path));                        }
                    }
                }
            }

            if($payment_frequency == "monthly"){
                Log::channel('hubstaff_activity_command')->info('check; payment frequecy is monthly');

                if ($diff_in_days == 30) {
                    Log::channel('hubstaff_activity_command')->info('check; difff in day is 30');

                    $res = $tasks_controller->getActivityUsers($req, $req);
                    Log::channel('hubstaff_activity_command')->info('get activit users' .json_encode($res));

                    $z = (array) $res;

                    foreach($z as $zz){
                        Log::channel('hubstaff_activity_command')->info('convert res in array and foreach condition' .json_encode($zz));

                        if($path == null){
                            Log::channel('hubstaff_activity_command')->info('path is not null' .json_encode($path));

                            $path = $zz->getRealPath();
                            Log::channel('hubstaff_activity_command')->info('get real path' .json_encode($path));                        }
                    }
                }
            }

            if ($path) {
                Log::channel('hubstaff_activity_command')->info('if path found than logout user');                        

                Auth::logout($user);
                Log::channel('hubstaff_activity_command')->info(' after logging out user');                        

                Log::channel('hubstaff_activity_command')->info(' before send mail');                        

                Mail::send('hubstaff.hubstaff-activities-mail', $data, function($message)use($data, $path) {
                    $message->to($data["email"], $data["email"])
                            ->subject($data["title"])->attach($path);  
                });
                Log::channel('hubstaff_activity_command')->info(' after sending mail');                        

                $user->last_mail_sent_payment = $today;
                $user->save();
                Log::channel('hubstaff_activity_command')->info(' user saved' .json_encode($user));                        

                $storage_path = substr($path, strpos($path, 'framework'));
                    
                $hubstaff_activity = new HubstaffActivityByPaymentFrequency;
                $hubstaff_activity->user_id = $user->id;
                $hubstaff_activity->activity_excel_file = $storage_path;
                $hubstaff_activity->save();
                Log::channel('hubstaff_activity_command')->info('store in hubstaff activity' .json_encode($hubstaff_activity));                        

            }
            Log::channel('hubstaff_activity_command')->info('end for each');                        

        }
        dd('complete');
    }
}
