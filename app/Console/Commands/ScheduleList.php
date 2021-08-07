<?php

namespace App\Console\Commands;

use App\CronJob;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleList extends Command
{
    protected $signature   = 'schedule:list';
    protected $description = 'List when scheduled commands are executed.';

    /**
     * @var Schedule
     */
    protected $schedule;

    /**
     * ScheduleList constructor.
     *
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        parent::__construct();

        $this->schedule = $schedule;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $events = array_map(function ($event) {

                return [
                    'cron'    => $event->expression,
                    'command' => static::fixupCommand($event->command),
                ];
            }, $this->schedule->events());

            //Getting artisan
            foreach ($events as $event) {
                $schedule = $event['cron'];
                $command  = explode(' ', $event['command']);
                if (isset($command[1])) {
                    $signature = $command[1];
                    if ($signature != null) {

                        $detail = CronJob::where('signature', 'like', "%{$signature}%")->first();
                        if ($detail == null) {
                            $cron              = new CronJob();
                            $cron->signature   = $signature;
                            $cron->schedule    = $schedule;
                            $cron->error_count = 0;
                            $cron->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    /**
     * If it's an artisan command, strip off the PHP
     *
     * @param $command
     * @return string
     */
    protected static function fixupCommand($command)
    {
        $parts = explode(' ', $command);

        if (count($parts) > 2 && $parts[1] === "'artisan'") {
            array_shift($parts);
        }

        return implode(' ', $parts);
    }

}
