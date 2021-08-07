<?php

namespace App\Console\Commands\Manual;

use App\ChatMessage;
use App\DeveloperTask;
use App\ErpPriority;
use App\Issue;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportIssues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:issues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Console command to import issues';

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
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            $issues = Issue::all();
            $data   = array();

            foreach ($issues as $issue) {
//            $data[] = array(
                //                'user_id' => $issue->user_id,
                //                'module_id' => $issue->module,
                //                'priority' => $issue->priority,
                //                'subject' => $issue->subject,
                //                'task' => $issue->issue,
                //                'status' => $issue->is_resolved == 1 ? 'Done' : 'Planned',
                //                'created_by' => $issue->submitted_by,
                //                'is_resolved' => $issue->is_resolved,
                //                'estimate_time' => $issue->estimate_time,
                //                'cost' => $issue->cost,
                //                'task_type_id' => 3,
                //                'responsible_user_id' => $issue->responsible_user_id,
                //                'created_at' => $issue->created_at
                //            );

                $developer_task                      = new DeveloperTask();
                $developer_task->user_id             = $issue->user_id;
                $developer_task->module_id           = $issue->module;
                $developer_task->priority            = $issue->priority;
                $developer_task->subject             = $issue->subject;
                $developer_task->task                = $issue->issue;
                $developer_task->status              = $issue->is_resolved == 1 ? 'Done' : 'Planned';
                $developer_task->created_by          = !empty($issue->submitted_by) ? $issue->submitted_by : 6;
                $developer_task->is_resolved         = $issue->is_resolved;
                $developer_task->estimate_time       = $issue->estimate_time;
                $developer_task->cost                = $issue->estimate_time;
                $developer_task->task_type_id        = 3;
                $developer_task->responsible_user_id = !is_null($issue->responsible_user_id) ? $issue->responsible_user_id : "";
                $developer_task->created_at          = $issue->created_at;
                $developer_task->save();
                $new_issue_id = $developer_task->id;

                $chat_msg = ChatMessage::where('issue_id', $issue->id)->first();
                if (!empty($chat_msg)) {
                    $chat_msg->issue_id = $new_issue_id;
                    $chat_msg->save();
                }

                // need to move priority as well
                $priority = ErpPriority::where("model_id", $issue->id)->where("model_type", Issue::class)->first();
                if ($priority) {
                    $priority->model_id   = $new_issue_id;
                    $priority->model_type = DeveloperTask::class;
                    $priority->save();
                }

                //$developer_task = DeveloperTask::insert($data);
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
