<?php

namespace App\Console\Commands;

use App\CronJob;
use App\CronJobReport;
use App\MessageQueue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MonitorCronJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:cron-jobs';

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
            $cron_job_report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $now                  = Carbon::now();
            $cron_jobs            = CronJob::all();
            $message_queues_count = MessageQueue::where('sent', 0)->where('status', '!=', 1)->where('sending_time', '<', $now)->count();

            foreach ($cron_jobs as $cron_job) {
                $now    = Carbon::now();
                $report = CronJobReport::where('signature', $cron_job->signature)->latest()->first();

                if ($report != null) {
                    dump($now->diffInMinutes($report->start_time));

                    if (($report->end_time == '' && ($now->diffInMinutes($report->start_time) > (int) $cron_job->schedule)) || ($report->end_time != '' && $now->diffInMinutes($report->end_time) > (int) $cron_job->schedule)) {
                        if ($cron_job->error_count <= 5) {
                            dump('cron job error');

                            // app('App\Http\Controllers\NotificationQueueController')->createNewNotification([
                            //   'message' => "Broadcast Cron Job Error - Pending messages: $message_queues_count",
                            //   'timestamps' => ['+0 minutes'],
                            //   'model_type' => 'MasterControl',
                            //   'model_id' =>  1,
                            //   'user_id' => 3,
                            //   'sent_to' => 6,
                            //   'role' => '',
                            // ]);
                            //
                            // app('App\Http\Controllers\NotificationQueueController')->createNewNotification([
                            //   'message' => "Broadcast Cron Job Error - Pending messages: $message_queues_count",
                            //   'timestamps' => ['+0 minutes'],
                            //   'model_type' => 'MasterControl',
                            //   'model_id' =>  1,
                            //   'user_id' => 3,
                            //   'sent_to' => 56,
                            //   'role' => '',
                            // ]);
                            //
                            // app('App\Http\Controllers\NotificationQueueController')->createNewNotification([
                            //   'message' => "Broadcast Cron Job Error - Pending messages: $message_queues_count",
                            //   'timestamps' => ['+0 minutes'],
                            //   'model_type' => 'MasterControl',
                            //   'model_id' =>  1,
                            //   'user_id' => 3,
                            //   'sent_to' => 7,
                            //   'role' => '',
                            // ]);

                            $cron_job->last_status = 'error';
                            $cron_job->error_count += 1;
                            $cron_job->save();
                        }
                    } else {
                        $cron_job->last_status = '';
                        $cron_job->error_count = 0;
                        $cron_job->save();
                    }
                }
            }

            $cron_job_report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
