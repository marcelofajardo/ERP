<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\DeveloperTask;
use App\Http\Controllers\WhatsAppController;
use App\Issue;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class SendMessageToUserIfTheirTaskIsNotComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:send-to-users-who-exceeded-limit';

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

            return;

            $now = Carbon::now()->toDateTimeString();

            $tasks = DeveloperTask::where(function ($query) use ($now) {
                $query->whereRaw('TIMESTAMPDIFF(HOUR, `estimate_time`, "' . $now . '") = 1');
            })
                ->where('status', '!=', 'Done')
                ->where('estimate_time', '!=', '')
                ->whereNotNull('estimate_time')
                ->get();

            foreach ($tasks as $task) {
                $message = 'You have 1 hour to complete the task #' . $task->id . '. Please update if this will be completed or not.';

                $myRequest = new Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add([
                    'message'           => $message,
                    'developer_task_id' => $task->id,
                    'status'            => 2,
                ]);
                app(WhatsAppController::class)->sendMessage($myRequest, 'developer_task');

            }

            $tasks = DeveloperTask::where(function ($query) use ($now) {
                $query->whereRaw('TIMESTAMPDIFF(HOUR, `estimate_time`, "' . $now . '") = 0');
            })
                ->where('status', '!=', 'Done')
                ->where('estimate_time', '!=', '')
                ->whereNotNull('estimate_time')
                ->get();

            foreach ($tasks as $task) {
                $message = 'Is your task #' . $task->id . ' complete? Please mark as Complete if its completed or let us know if it needs to be revised.';

                $myRequest = new Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add([
                    'message'           => $message,
                    'developer_task_id' => $task->id,
                    'status'            => 2,
                ]);
                app(WhatsAppController::class)->sendMessage($myRequest, 'developer_task');

            }

            $tasks = DeveloperTask::whereRaw('"' . $now . '" > `estimate_time`')
                ->where('status', '!=', 'Done')
                ->where('estimate_time', '!=', '')
                ->whereNotNull('estimate_time')
                ->get();

            foreach ($tasks as $task) {
                $message = 'You have not updated the time for task #' . $task->id . '. Please revise ASAP.';

                $myRequest = new Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add([
                    'message'           => $message,
                    'developer_task_id' => $task->id,
                    'status'            => 2,
                ]);
                app(WhatsAppController::class)->sendMessage($myRequest, 'developer_task');

            }

            $this->sendAlertsForIssues($now);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }

    private function sendAlertsForIssues($now)
    {
        $tasks = Issue::where(function ($query) use ($now) {
            $query->whereRaw('TIMESTAMPDIFF(HOUR, `estimate_time`, "' . $now . '") = 1');
        })
            ->where('is_resolved', '0')
            ->where('estimate_time', '!=', '')
            ->whereNotNull('estimate_time')
            ->get();

        foreach ($tasks as $task) {
            $message = 'You have 1 hour to resolve the issue #' . $task->id . '. Please update if this will be resolved or not.';

            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add([
                'message'  => $message,
                'issue_id' => $task->id,
                'status'   => 2,
            ]);
            app(WhatsAppController::class)->sendMessage($myRequest, 'issue');

        }

        $tasks = Issue::where(function ($query) use ($now) {
            $query->whereRaw('TIMESTAMPDIFF(HOUR, `estimate_time`, "' . $now . '") = 0');
        })
            ->where('is_resolved', '0')
            ->where('estimate_time', '!=', '')
            ->whereNotNull('estimate_time')
            ->get();

        foreach ($tasks as $task) {
            $message = 'Is your issue #' . $task->id . ' resolved? Please mark as Resolved if its resolved or let us know if it needs to be revised.';

            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add([
                'message'  => $message,
                'issue_id' => $task->id,
                'status'   => 2,
            ]);
            app(WhatsAppController::class)->sendMessage($myRequest, 'issue');

        }

        $tasks = Issue::whereRaw('"' . $now . '" > `estimate_time`')
            ->where('is_resolved', '0')
            ->where('estimate_time', '!=', '')
            ->whereNotNull('estimate_time')
            ->get();

        foreach ($tasks as $task) {
            $message = 'You have not updated the time for issue #' . $task->id . '. Please revise ASAP.';

            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add([
                'message'  => $message,
                'issue_id' => $task->id,
                'status'   => 2,
            ]);

            app(WhatsAppController::class)->sendMessage($myRequest, 'issue');

        }

        $report->update(['end_time' => Carbon::now()]);
    }
}
