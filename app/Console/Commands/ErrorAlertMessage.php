<?php

namespace App\Console\Commands;

use App\DeveloperTask;
use App\LogKeyword;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class errorAlertMessage extends Command
{
    const CRON_ISSUE_MODULE_NAME = "268";
    const CRON_ISSUE_PRIORITY    = 1;
    const CRON_ISSUE_STATUS      = "Planned";
    const DEFAULT_ASSIGNED_TO    = 1;

    //scrappersImagesDelete
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'errorAlertMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message to user 6 if error occured.';

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
        $filename = '/laravel-' . now()->format('Y-m-d') . '.log';

        $path         = storage_path('logs');
        $fullPath     = $path . $filename;
        $errSelection = [];
        try {
            $content = File::get($fullPath);
            preg_match_all("/\[(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})\](.*)/", $content, $match);
            $logKeywords = LogKeyword::all();
            if (isset($match[7])) {
                foreach ($match[7] as $value) {
                    foreach ($logKeywords as $key => $logKeyword) {
                        if (strpos(strtolower($value), strtolower($logKeyword->text)) !== false) {
                            $message = "You have error which matched the keyword  '" . $logKeyword->text . "'";
                            $message .= " | " . $value;
                            $subject          = "You have error which matched the keyword  '" . $logKeyword->text . "'";
                            $hasAssignedIssue = DeveloperTask::where("subject", "like", "%{$subject}%")->whereDate("created_at", date("Y-m-d"))->where("is_resolved", 0)->first();
                            if (!$hasAssignedIssue) {
                                $requestData = new Request();
                                $requestData->setMethod('POST');
                                $requestData->request->add([
                                    'priority'    => self::CRON_ISSUE_PRIORITY,
                                    'issue'       => $message,
                                    'status'      => self::CRON_ISSUE_STATUS,
                                    'module'      => self::CRON_ISSUE_MODULE_NAME,
                                    'subject'     => $subject,
                                    'assigned_to' => \App\Setting::get("cron_issue_assinged_to", self::DEFAULT_ASSIGNED_TO),
                                ]);
                                app('App\Http\Controllers\DevelopmentController')->issueStore($requestData, 'issue');
                            }

                        }
                    }
                }
            }
            $this->output->write('Cron Done', true);
        } catch (\Exception $e) {
            $this->output->write("Error is caught here! => " . $e->getMessage(), true);
        }
    }
}
