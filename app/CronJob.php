<?php

namespace App;

use App\DeveloperTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


class CronJob extends Model
{
    const CRON_ISSUE_SUBJECT_PREFIX = "URGENT-CRON-ISSUE-";
    const CRON_ISSUE_MODULE_NAME = "Cron";
    const CRON_ISSUE_PRIORITY = 1;
    const CRON_ISSUE_STATUS = "Planned";
    const DEFAULT_ASSIGNED_TO = 1;


    public function index()
    {
        $cron = CronJob::orderBy('id')->get();
        return $cron;
    }

    public static function insertLastError($signature, $error = "")
    {
        $cron = self::where("signature", $signature)->first();

        if (!$cron) {
            $cron            = new self;
            $cron->signature = $signature;
            $cron->schedule  = "N/A";
        }
        $cron->last_status = 'error';
        $cron->error_count += 1;
        $cron->last_error = $error;
        $cron->save();

        $issueName = strtoupper(self::CRON_ISSUE_SUBJECT_PREFIX . $signature);

        $hasAssignedIssue = DeveloperTask::where("subject", $issueName)->where("is_resolved", 0)->first();

        if (!$hasAssignedIssue) {
            $requestData = new Request();
            $requestData->setMethod('POST');
            $requestData->request->add([
                'priority'    => self::CRON_ISSUE_PRIORITY,
                'issue'       => $error,
                'status'      => self::CRON_ISSUE_STATUS,
                'module'      => self::CRON_ISSUE_MODULE_NAME,
                'subject'     => $issueName,
                'assigned_to' => \App\Setting::get("cron_issue_assinged_to",self::DEFAULT_ASSIGNED_TO),
            ]);
            app('App\Http\Controllers\DevelopmentController')->issueStore($requestData, 'issue');
        }
        /*else{
            $requestData = new Request();
            $requestData->setMethod('POST');
            $requestData->request->add(['issue_id' => $hasAssignedIssue->id, 'message' => $error, 'status' => 1]);
            app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'issue');
        }*/

        // once cron error done we need to assign that error to the related person

    }

}
