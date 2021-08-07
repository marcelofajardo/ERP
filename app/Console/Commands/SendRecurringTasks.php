<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\CronJobReport;
use App\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:recurring-tasks';

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

            $now           = Carbon::now();
            $today_date    = Carbon::now()->format('Y-m-d');
            $today_time    = Carbon::now()->format('H:i');
            $today_weekday = strtoupper(Carbon::now()->format('l'));
            $today_day     = Carbon::now()->format('d');
            $today_month   = Carbon::now()->format('m');

            $tasks = Task::where('is_statutory', 1)->whereNull('is_completed')->whereNotNull('recurring_type')->get();

            $params = [
                'user_id'  => 6,
                'number'   => null,
                'approved' => 0,
                'status'   => 1,
            ];

            foreach ($tasks as $task) {
                $selected_time = $task->sending_time ?? $task->created_at;
                $sending_date  = Carbon::parse($selected_time)->format('Y-m-d');
                $sending_time  = Carbon::create($now->year, $now->month, $now->day, Carbon::parse($selected_time)->format('H'), Carbon::parse($selected_time)->format('i'), 0);
                // $sending_time = Carbon::parse($selected_time);
                $sending_weekday = strtoupper(Carbon::parse($selected_time)->format('l'));
                $sending_day     = Carbon::parse($selected_time)->format('d');
                $sending_month   = Carbon::parse($selected_time)->format('m');

                $params['message'] = $task->task_subject . ". " . $task->task_details;
                $params['task_id'] = $task->id;
                $params['user_id'] = $task->assign_from;

                dump($today_time);
                dump($sending_time->format('H:i'));
                dump($sending_time->diffInMinutes($now));

                $can_send_message = false;

                switch ($task->recurring_type) {
                    case 'EveryHour':
                        $hourBefore = $task->updated_at->format('H');
                        $hourNow    = Carbon::now()->hour;

                        if ($hourBefore != $hourNow) {
                            $can_send_message = true;
                            $task->touch();
                        }

                        break;
                    case "EveryDay":
                        if ($today_date >= $sending_date && $now > $sending_time && $sending_time->diffInMinutes($now) <= 13) {
                            dump('Send Recurring Task Daily');

                            $can_send_message = true;
                        }

                        break;
                    case "EveryWeek":
                        if ($today_date >= $sending_date && $today_weekday == $sending_weekday && $now > $sending_time && $sending_time->diffInMinutes($now) <= 13) {
                            dump('Send Recurring Task Weekly');

                            $can_send_message = true;
                        }

                        break;
                    case "EveryMonth":
                        if ($today_day == $sending_day && $now > $sending_time && $sending_time->diffInMinutes($now) <= 13) {
                            dump('Send Recurring Task Monthly');

                            $can_send_message = true;
                        }

                        break;
                    case "EveryYear":
                        if ($today_day == $sending_day && $today_month == $sending_month && $now > $sending_time && $sending_time->diffInMinutes($now) <= 13) {
                            dump('Send Recurring Task Yearly');

                            $can_send_message = true;
                        }

                        break;
                    default:

                        break;
                }

                if ($can_send_message) {
                    dump("Sending a message");
                    if (count($task->users) > 0) {
                        foreach ($task->users as $key => $user) {
                            if ($key == 0) {
                                $params['erp_user'] = $user->id;
                            } else {
                                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $params['message']);
                            }
                        }
                    }

                    if (count($task->contacts) > 0) {
                        foreach ($task->contacts as $key => $contact) {
                            if ($key == 0) {
                                $params['contact_id'] = $task->assign_to;
                            } else {
                                app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($contact->phone, null, $params['message']);
                            }
                        }
                    }

                    $chat_message = ChatMessage::create($params);

                    $myRequest = new Request();
                    $myRequest->setMethod('POST');
                    $myRequest->request->add(['messageId' => $chat_message->id]);

                    app('App\Http\Controllers\WhatsAppController')->approveMessage('task', $myRequest);
                } else {
                    dump('No message to send');
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
