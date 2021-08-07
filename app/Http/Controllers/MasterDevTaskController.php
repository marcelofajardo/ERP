<?php

namespace App\Http\Controllers;

use App\Library\Github\GithubClient;
use App\MemoryUsage;
use Illuminate\Http\Request;
use ProjectDirectory;

class MasterDevTaskController extends Controller
{
    /*
    Database
    - Average load time
    - Database size now vs Database size 24 hours ago

    Development
    - Open branches per repository
    - Number of errors in the current log file

    Cron jobs
    - Number of failed crons last 24 hours

    Whatsapp
    - Number of messages last 3 hours
    - Number of messages last 24 hours

    Scraping
    - Number of errors per scraper last 24 hours
    - Number of products per scraper last 24 hours

    Cropping
    - Number of crops last 3 hours
    - Number of crops last 24 hours
     */
    public function index(Request $request)
    {
        $currentSize = \DB::table("database_historical_records")->orderBy("created_at", "desc")->first();
        //echo '<pre>'; print_r($currentSize); echo '</pre>';exit;
        $sizeBefore  = null;
        if (!empty($currentSize)) {
            $sizeBefore = \DB::table("database_historical_records")
                ->whereRaw(\DB::raw("DATE(created_at) = DATE('" . $currentSize->created_at . "' - INTERVAL 1 DAY)"))
                ->first();
        }

        $topFiveTables = \App\DatabaseTableHistoricalRecord::whereDate('created_at',date("Y-m-d"))->groupBy('database_name')->orderBy('size','desc')->limit(5)->get();
        // find the open branches
        //$github     = new GithubClient;
        //$repository = $github->getRepository();
        $repoArr    = [];
		$github     = new GithubClient;
        $repository = $github->getRepository();
        
        if (!empty($repository)) {
            foreach ($repository as $i => $repo) {
                $repoId = $repo->full_name;
                $pulls  = $github->getPulls($repoId, "q=is%3Aopen+is%3Apr");
                 $repoArr[$i]["name"] =  $repoId;
                if (!empty($pulls)) {
                    foreach ($pulls as $pull) {
                        $repoArr[$i]["pulls"][] = [
                            "title" => $pull->title,
                            "no"    => $pull->number,
                            "url"   => $pull->html_url,
                            "user"   => $pull->user->login,
                        ];
                    }
                }
            }
        }
        $cronjobReports = null;
        
        $cronjobReports = \App\CronJob::join("cron_job_reports as cjr", "cron_jobs.signature", "cjr.signature")
        ->where("cjr.start_time", '>', \DB::raw('NOW() - INTERVAL 24 HOUR'))
        ->where("cron_jobs.last_status", "error")
        ->groupBy("cron_jobs.signature")
        ->get();

        $scraper1hrsReports = null;
        $scraper1hrsReports = \App\CroppedImageReference::where("created_at",">=",\DB::raw("DATE_SUB(NOW(),INTERVAL 1 HOUR)"))->select(
            [\DB::raw("count(*) as cnt")]
        )->first();
        $scraper24hrsReports = null;
        $scraper24hrsReports = \App\CroppedImageReference::where("created_at",">=",\DB::raw("DATE_SUB(NOW(),INTERVAL 24 HOUR)"))->select(
            [\DB::raw("count(*) as cnt")]
        )->first();

        $last3HrsMsg = null;
        $last24HrsMsg = null;

        $last3HrsMsg = \DB::table("chat_messages")->where("created_at",">=",\DB::raw("DATE_SUB(NOW(),INTERVAL 3 HOUR)"))->select(
            [\DB::raw("count(*) as cnt")]
        )->first();

        $last24HrsMsg = \DB::table("chat_messages")->where("created_at",">=",\DB::raw("DATE_SUB(NOW(),INTERVAL 24 HOUR)"))->select(
            [\DB::raw("count(*) as cnt")]
        )->first();

        $threehours = strtotime(date("Y-m-d H:i:s", strtotime('-3 hours')));
        $twentyfourhours = strtotime(date("Y-m-d H:i:s", strtotime('-24 hours')));

        $last3HrsJobs = \DB::table("jobs")->where("created_at",">=",$threehours)->select(
            [\DB::raw("count(*) as cnt")]
        )->first();

        $last24HrsJobs = \DB::table("jobs")->whereDate("created_at",">=",$twentyfourhours)->select(
            [\DB::raw("count(*) as cnt")]
        )->first();

        // Get scrape data
        $sql = '
            SELECT
                s.id,
                s.supplier,
                COUNT(ls.id) AS total,
                SUM(IF(ls.validated=0,1,0)) AS failed,
                SUM(IF(ls.validated=1,1,0)) AS validated,
                SUM(IF(ls.validation_result LIKE "%[error]%",1,0)) AS errors
            FROM
                suppliers s
            JOIN
                scrapers sc
            ON 
                sc.supplier_id = s.id    
            JOIN
                scraped_products ls 
            ON  
                sc.scraper_name=ls.website
            WHERE
                ls.website != "internal_scraper" AND
                ls.last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY)
            ORDER BY
                sc.scraper_priority desc
        ';
        $scrapeData = \DB::select($sql);
		
		//DB Image size management#3118
		$projectDirectorySql = "select * FROM `project_file_managers` where size > notification_at";

        $memory_use = MemoryUsage::
                whereDate('created_at', now()->format('Y-m-d'))
                ->orderBy('used','desc')
                ->first();


        $projectDirectoryData = \DB::select($projectDirectorySql);


        $logRequest = \App\LogRequest::where('status_code',"!=",200)->whereDate("created_at",date("Y-m-d"))->groupBy('status_code')->select(["status_code",\DB::raw("count(*) as total_error")])->get();


		return view("master-dev-task.index",compact(
            'currentSize','sizeBefore','repoArr','cronjobReports','last3HrsMsg','last24HrsMsg','scrapeData','scraper1hrsReports','scraper24hrsReports','projectDirectoryData','last3HrsJobs','last24HrsJobs','topFiveTables','memory_use','logRequest'));
    }

}
