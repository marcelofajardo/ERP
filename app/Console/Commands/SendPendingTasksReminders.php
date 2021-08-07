<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPendingTasksReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:pending-tasks-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends every morning 4 main users pending tasks count via whatsapp!';

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

            $tasks = Task::whereNull('is_completed')->whereRaw('tasks.id IN (SELECT task_id FROM task_users WHERE user_id IN (6, 7, 49, 56) AND type LIKE "%User%")')->get()->groupBy('assign_to');

            foreach ($tasks as $user_id => $data) {
                $user = User::find($user_id);

                if ($user) {
                    $count   = count($data);
                    $message = "Today You have $count pending tasks.";

                    dump("$user_id - $user->name has $count pending tasks");

                    try {
                        dump("Sending message");

                        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $message);
                    } catch (\Exception $e) {
                        dump($e->getMessage());
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
