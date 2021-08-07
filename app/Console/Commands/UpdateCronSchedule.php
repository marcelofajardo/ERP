<?php

namespace App\Console\Commands;

use App\ErpEvents;
use Cron\CronExpression;
use Illuminate\Console\Command;

class UpdateCronSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronschedule:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Cron Schedule';

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
        // disable all events which is past if still active
        try {
            $dateToday = date("Y-m-d H:i:s");
            \DB::table('erp_events')->where('end_date', '<=', $dateToday)->where("is_closed", 0)->update(array('is_closed' => 1));

            $events = \App\ErpEvents::where("is_closed", 0)->get();

            if (!$events->isEmpty()) {
                foreach ($events as $event) {
                    try {
                        $cron = CronExpression::factory("$event->minute $event->hour $event->day_of_month $event->month $event->day_of_week");
                        if ($cron->isDue()) {
                            $event->next_run_date = $cron->getNextRunDate()->format('Y-m-d H:i:s');
                        } else {
                            $event->is_closed = 1;
                        }
                    } catch (\Exception $e) {
                        $event->is_closed = 1;
                    }

                    $event->save();
                }
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
