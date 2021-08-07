<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\DailyActivity;
use App\Mails\Manual\SendDailyActivityReport;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyPlannerReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:daily-planner-report';

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

            $users_array   = [6, 7, 56];
            $planned_tasks = Task::whereNotNull('time_slot')->where('planned_at', Carbon::now()->format('Y-m-d'))->whereNull('is_completed')->whereIn('assign_to', $users_array)->orderBy('time_slot', 'ASC')->get()->groupBy(['assign_to', 'time_slot']);

            $statutory = Task::where('is_statutory', 1)->whereNull('is_verified')->whereIn('assign_to', $users_array)->get()->groupBy('assign_to');

            $daily_activities = DailyActivity::where('for_date', Carbon::now()->format('Y-m-d'))->whereIn('user_id', $users_array)->get()->groupBy(['user_id', 'time_slot']);

            // dd($daily_activities);

            // $time_slots = [
            //   '08:00am - 10:00am' => [],
            //   '10:00am - 12:00pm' => [],
            //   '12:00pm - 02:00pm' => [],
            //   '02:00pm - 04:00pm' => [],
            //   '04:00pm - 06:00pm' => [],
            //   '06:00pm - 08:00pm' => [],
            //   '08:00pm - 10:00pm' => [],
            // ];

            $time_slots = [];

            foreach ($statutory as $user_id => $tasks) {
                foreach ($tasks as $task) {
                    $time_slots[$user_id]['08:00am - 10:00am'][] = [
                        'activity'     => '',
                        'task_subject' => $task->task_subject,
                        'task_details' => $task->task_details,
                        'pending_for'  => $task->pending_for,
                        'is_completed' => $task->is_completed,
                    ];
                }
            }

            foreach ($planned_tasks as $user_id => $data) {
                foreach ($data as $time_slot => $items) {
                    foreach ($items as $task) {
                        $time_slots[$user_id][$time_slot][] = [
                            'activity'     => '',
                            'task_subject' => $task->task_subject,
                            'task_details' => $task->task_details,
                            'pending_for'  => $task->pending_for,
                            'is_completed' => $task->is_completed,
                        ];
                    }
                }
            }

            foreach ($daily_activities as $user_id => $data) {
                foreach ($data as $time_slot => $items) {
                    foreach ($items as $task) {
                        $time_slots[$user_id][$time_slot][] = [
                            'activity'     => $task->activity,
                            'task_subject' => '',
                            'task_details' => '',
                            'pending_for'  => $task->pending_for,
                            'is_completed' => $task->is_completed,
                        ];
                    }
                }
            }

            foreach ($time_slots as $user_id => $data) {
                if ($user = User::find($user_id)) {
                    Mail::to('yogeshmordani@icloud.com')->send(new SendDailyActivityReport($user, $data));
                    // Mail::to('vysniukass@gmail.com')->send(new SendDailyActivityReport($user, $data));
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
