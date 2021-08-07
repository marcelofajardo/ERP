<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChatMessage;
use App\CronJobReport;
use App\DailyActivity;
use App\DailyActivitiesHistories;
use App\UserEvent\UserEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendDailyPlannerNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-daily-planner-notification';

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // get the events which has 30  OR 05 Min left
            // $events = UserEvent::havingRaw("TIMESTAMPDIFF(MINUTE,now() , start) >= 30 AND TIMESTAMPDIFF(MINUTE, now(), start) <= 35 OR TIMESTAMPDIFF(MINUTE, now(), start) = 05 ")->get();
            $events = UserEvent::havingRaw("TIMESTAMPDIFF(MINUTE,now() , start) = 30 OR TIMESTAMPDIFF(MINUTE, now(), start) = 05 ")->get();

            $userWise           = [];
            $vendorParticipants = [];

            if (!$events->isEmpty()) {
                foreach ($events as $event) {
                    $userWise[$event->user_id][] = $event;
                    $participants                = $event->attendees;
                    if (!$participants->isEmpty()) {
                        foreach ($participants as $participant) {
                            if ($participant->object == \App\Vendor::class) {
                                $vendorParticipants[$participant->object_id] = $event;
                            }
                        }
                    }
                }
            }

            if (!empty($userWise)) {
                foreach ($userWise as $id => $events) {
                    // find user into database
                    $user = \App\User::find($id);
                    // if user exist
                    if (!empty($user)) {
                        $notification   = [];
                        $notification[] = "Following Event Schedule on within the next 30 min";
                        $no             = 1;

                        foreach ($events as $event) {
                            $dailyActivities = DailyActivity::where('id', $event->daily_activity_id)->first();
                            $notification[] = $no . ") [" . changeTimeZone( $dailyActivities->for_datetime, null ,$dailyActivities->timezone ) . "] => " . $event->subject;
                            $no++;

                            $history = [
                                'daily_activities_id' => $event->daily_activity_id,
                                'title'               => 'Sent notification',
                                'description'         => "To ".$user->name,
                            ];
                            DailyActivitiesHistories::insert( $history );
                        }

                        $params['user_id'] = $user->id;
                        $params['message'] = implode("\n", $notification);
                        // send chat message
                        $chat_message = \App\ChatMessage::create($params);
                        // send
                        app('App\Http\Controllers\WhatsAppController')
                            ->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message'], false, $chat_message->id);
                        
                       

                    }
                }
            }

            if (!empty($vendorParticipants)) {
                foreach ($vendorParticipants as $id => $vendorParticipant) {
                    $vendor = \App\Vendor::find($id);
                    if (!empty($vendor)) {
                        $notification   = [];
                        $notification[] = "Following Event Schedule on within the next 30 min";
                        $no             = 1;
                        foreach ($events as $event) {
                            $dailyActivities = DailyActivity::where('id', $event->daily_activity_id)->first();
                            
                            $notification[] = $no . ") [" . changeTimeZone( $dailyActivities->for_datetime, null ,$dailyActivities->timezone ) . "] => " . $event->subject;
                            $no++;
                            $history = [
                                'daily_activities_id' => $event->daily_activity_id,
                                'title'               => 'Sent notification',
                                'description'         => "To ".$vendor->name,
                            ];
                            DailyActivitiesHistories::insert( $history );
                        }

                        $params['vendor_id'] = $vendor->id;
                        $params['message']   = implode("\n", $notification);
                        // send chat message
                        $chat_message = \App\ChatMessage::create($params);
                        // send
                        app('App\Http\Controllers\WhatsAppController')
                            ->sendWithThirdApi($vendor->phone, $vendor->whatsapp_number, $params['message'], false, $chat_message->id);
                    }
                }
            }


            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
