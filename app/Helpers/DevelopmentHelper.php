<?php

namespace App\Helpers;

use App\DeveloperTask;

class DevelopmentHelper
{
    public static function getDeveloperTasks($developerId, $status = 'In Progress', $task_type)
    {

        // Get open tasks for developer
        $developerTasks = DeveloperTask::where('user_id', $developerId)
            ->join('task_types', 'task_types.id', '=', 'developer_tasks.task_type_id')
            ->select('*', 'developer_tasks.id as task_id')
            ->where('parent_id', '=', '0')
            ->where('status', $status)
            ->where('task_type_id', $task_type)
            ->orderBy('priority', 'ASC')
            ->orderBy('subject', 'ASC')
            ->get();

        // Return developer tasks
        return $developerTasks;
    }

    public static function scrapTypes()
    {
        return [
            "1" => "Typescript",
            "2" => "NodeJS Request/Cheerio",
            "3" => "NodeJS Puppeteer",
            "4" => "NodeJS Puppeteer with URL list",
            "5" => "NodeJS Puppeteer Luminati with URL list",
            "6" => "Py Scraper"
        ];
    }

    public static function scrapTypeById($id)
    {
        if(!empty($id)) {
            return isset(self::scrapTypes()[$id]) ? self::scrapTypes()[$id] : "";
        }

        return "";
    }

    public static function needToApproveMessage()
    {
        $approveMessage = 0;

        $approvalmodel  = \App\Setting::where("name","is_approve_message_btn")->first();
        if($approvalmodel) {
            $approveMessage = $approvalmodel->val;
        }

        return $approveMessage;
    }
}
