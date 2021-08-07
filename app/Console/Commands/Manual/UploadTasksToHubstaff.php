<?php

namespace App\Console\Commands\Manual;

use Carbon\Carbon;
use DB;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Console\Command;
use Storage;

class UploadTasksToHubstaff extends Command
{

    public $HUBSTAFF_TOKEN_FILE_NAME;
    public $SEED_REFRESH_TOKEN;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hubstaff:upload_tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload all the tasks to hubstaff';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->HUBSTAFF_TOKEN_FILE_NAME = 'hubstaff_tokens.json';
        // $this->SEED_REFRESH_TOKEN       = getenv('HUBSTAFF_SEED_PERSONAL_TOKEN');
        $this->SEED_REFRESH_TOKEN       = config('env.HUBSTAFF_SEED_PERSONAL_TOKEN');
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
            //
            $this->uploadNormalTasks();
            $this->uploadDeveloperTasks();
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function uploadNormalTasks()
    {
        $tasks = DB::table('tasks')
            ->whereNull('is_completed')
            ->where('hubstaff_task_id', '=', 0)
            ->leftJoin('hubstaff_members', 'hubstaff_members.user_id', '=', 'tasks.assign_to')
            ->select(['tasks.id', 'tasks.task_subject as summary', 'hubstaff_members.hubstaff_user_id as assignee_id'])
            ->get()
            ->toArray();

        $tasks = array_map(
            function ($task) {
                $task->summary = '#' . $task->id . ' => ' . $task->summary;
                return $task;
            },
            $tasks
        );

        echo "Total tasks: " . sizeof($tasks) . PHP_EOL;
        $this->uploadTasks($tasks, 'tasks');
        echo "UPLOADED TASKS" . PHP_EOL;
    }

    private function uploadDeveloperTasks()
    {
        $assignedTasks = DB::table('developer_tasks')
            ->whereIn('status', ['Discussing', 'In Progress', 'Issue', 'Planned'])
            ->where('hubstaff_task_id', '=', 0)
            ->leftJoin('hubstaff_members', 'hubstaff_members.user_id', '=', 'developer_tasks.user_id')
            ->select(['developer_tasks.id', 'developer_tasks.subject as summary', 'developer_tasks.task_type_id', 'hubstaff_members.hubstaff_user_id as assignee_id'])
            ->get()
            ->toArray();

        $assignedTasks = array_map(
            function ($task) {
                $summary = '#';
                if ($task->task_type_id == 1) {
                    $summary .= 'DEVTASK-' . $task->id . ' => ' . $task->summary;
                }
                $task->summary = $summary;
                return $task;
            },
            $assignedTasks
        );

        echo "Total Dev tasks: " . sizeof($assignedTasks) . PHP_EOL;
        $this->uploadTasks($assignedTasks, 'developer_tasks');
        echo "UPLOADED DEVELOPER TASKS";
    }

    private function uploadTasks($tasks, $tableName)
    {
        foreach ($tasks as $index => $task) {
            $taskId = $this->uploadTask($task);
            if ($taskId) {
                echo "(" . ($index + 1) . "/" . sizeof($tasks) . ") Created Hubstaff Task: " . $taskId . ' for task: ' . $task->id . PHP_EOL;

                DB::table($tableName)
                    ->where('id', '=', $task->id)
                    ->update(
                        [
                            'hubstaff_task_id' => $taskId,
                        ]
                    );
            } else {
                echo "(" . ($index + 1) . "/" . sizeof($tasks) . ")Failed to create task for task ID: " . $task->id . PHP_EOL;
            }
            sleep(5);
        }
    }

    private function uploadTask($task, $shouldRetry = true)
    {

        $tokens = $this->getTokens();

        // $url        = 'https://api.hubstaff.com/v2/projects/' . getenv('HUBSTAFF_BULK_IMPORT_PROJECT_ID') . '/tasks';
        $url        = 'https://api.hubstaff.com/v2/projects/' . config('env.HUBSTAFF_BULK_IMPORT_PROJECT_ID') . '/tasks';
        $httpClient = new Client();
        try {

            $response = $httpClient->post(
                $url,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $tokens->access_token,
                        'Content-Type'  => 'application/json',
                    ],

                    RequestOptions::BODY    => json_encode([
                        'summary'     => substr($task->summary, 0, 200),
                        // 'assignee_id' => isset($task->assignee_id) ? $task->assignee_id : getenv('HUBSTAFF_DEFAULT_ASSIGNEE_ID'),
                        'assignee_id' => isset($task->assignee_id) ? $task->assignee_id : config('env.HUBSTAFF_DEFAULT_ASSIGNEE_ID'),
                    ]),
                ]
            );
            $parsedResponse = json_decode($response->getBody()->getContents());
            return $parsedResponse->task->id;
        } catch (Exception $e) {
            if ($e instanceof ClientException) {
                $this->refreshTokens();
                if ($shouldRetry) {
                    return $this->uploadTask(
                        $task,
                        false
                    );
                }
            }
            echo $e->getMessage() . PHP_EOL;
        }
        return false;
    }

    private function refreshTokens()
    {
        $tokens = $this->getTokens();
        $this->generateAccessToken($tokens->refresh_token);
    }

    private function getTokens()
    {
        if (!Storage::disk('local')->exists($this->HUBSTAFF_TOKEN_FILE_NAME)) {
            $this->generateAccessToken($this->SEED_REFRESH_TOKEN);
        }
        $tokens = json_decode(Storage::disk('local')->get($this->HUBSTAFF_TOKEN_FILE_NAME));
        return $tokens;
    }

    /**
     * returns boolean
     */
    private function generateAccessToken(string $refreshToken)
    {
        $httpClient = new Client();
        try {
            $response = $httpClient->post(
                'https://account.hubstaff.com/access_tokens',
                [
                    RequestOptions::FORM_PARAMS => [
                        'grant_type'    => 'refresh_token',
                        'refresh_token' => $refreshToken,
                    ],
                ]
            );

            $responseJson = json_decode($response->getBody()->getContents());

            $tokens = [
                'access_token'  => $responseJson->access_token,
                'refresh_token' => $responseJson->refresh_token,
            ];

            return Storage::disk('local')->put($this->HUBSTAFF_TOKEN_FILE_NAME, json_encode($tokens));
        } catch (Exception $e) {
            return false;
        }
    }
}
