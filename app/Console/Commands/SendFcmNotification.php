<?php

namespace App\Console\Commands;

use App\PushFcmNotification;
use FCM;
use Illuminate\Console\Command;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class SendFcmNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command For push notification';

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
        $fromdate      = date('Y-m-d H:i');
        $newtimestamp  = strtotime($fromdate . ' + 4 minute');
        $todate        = date('Y-m-d H:i', $newtimestamp);
        echo $fromdate."#".$todate;
        echo PHP_EOL;
        \Log::info("fcm:send was started to run");
        $Notifications = PushFcmNotification::select('sw.push_web_key', 'sw.push_web_id', 'ft.token', 'push_fcm_notifications.*')
            ->leftJoin('fcm_tokens as ft', 'ft.store_website_id', '=', 'push_fcm_notifications.store_website_id')
            ->leftJoin('store_websites as sw', 'sw.id', '=', 'push_fcm_notifications.store_website_id')
            ->where('ft.token', '!=', '')
            ->where('sw.push_web_key', '!=', '')
            ->where('sw.push_web_id', '!=', '')
            ->whereBetween('push_fcm_notifications.sent_at', [$fromdate, $todate])
            ->get();
        \Log::info("fcm:send query was finished");    
        if (!$Notifications->isEmpty()) {
            \Log::info("fcm:send record was found");    
            foreach ($Notifications as $Notification) {
                $errorMessage = "";
                $token = "";
                try{
                    
                    config(['fcm.http.sender_id' => $Notification['push_web_id']]);
                    config(['fcm.http.server_key' => $Notification['push_web_key']]);
                    \Log::info("fcm:send sender_id was ".$Notification['push_web_id']." found with key ".$Notification['push_web_key']);

                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60 * 20);

                    $notificationBuilder = new PayloadNotificationBuilder($Notification->title);
                    $notificationBuilder->setBody($Notification->body)
                        ->setSound('default');

                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['icon' => $Notification->icon, 'url', $Notification->url]);

                    $option       = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data         = $dataBuilder->build();

                    $token = $Notification->token;

                    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

                    $success = false;
                    if ($downstreamResponse->numberSuccess()) {
                        //PushFcmNotification::where('id', $Notification->id)->update(['sent_on' => date('Y-m-d H:i')]);
                        $this->info('Message Sent Succesfully');
                        \Log::info("fcm:send Message Sent Succesfully");
                        $success = true;
                    } elseif ($downstreamResponse->numberFailure()) {
                        $this->info(json_encode($downstreamResponse->tokensWithError()));
                        $errorMessage = json_encode($downstreamResponse->tokensWithError());
                        \Log::info("fcm:send Message Error message =>".$errorMessage);
                    }

                }catch(\Exception $e){
                    $success = false;
                    $errorMessage = $e->getMessage();
                    \Log::info("fcm:send Exception Error message =>".$errorMessage);
                }

                $Notification->sent_on = date('Y-m-d H:i');
                $Notification->save();

                \App\PushFcmNotificationHistory::create([
                    "token"           => $token,
                    "notification_id" => $Notification->id,
                    "success"         => $success,
                    "error_message"   => $errorMessage,
                ]);

            }
        } else {
            \Log::info("fcm:send Exception No notification available for sending at the moment");
            $this->info('No notification available for sending at the moment');
        }
    }
}
