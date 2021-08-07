<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\DailyActivity;
use App\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MovePlannedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:planned-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moves unfinished planned tasks to another day and resets statutory tasks completed at datetime';

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

            $today         = Carbon::now()->format('Y-m-d');
            $planned_tasks = Task::whereNotNull('time_slot')->where('planned_at', '<', "$today 00:00")->whereNull('is_completed')->orderBy('time_slot', 'ASC')->get();

            foreach ($planned_tasks as $task) {
                $task->planned_at = $today;
                $task->pending_for += 1;
                $task->save();
            }

            // dd(count($planned_tasks));

            $statutory_tasks = Task::where('is_statutory', 1)->whereNotNull('is_completed')->whereNull('is_verified')->get();

            foreach ($statutory_tasks as $task) {
                $task->is_completed = null;
                $task->save();
            }

            $daily_activities = DailyActivity::whereNull('is_completed')->where('for_date', '<', "$today 00:00")->get();

            foreach ($daily_activities as $task) {
                $task->for_date = $today;
                $task->pending_for += 1;
                $task->save();
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
