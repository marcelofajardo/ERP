<?php

namespace App\Console\Commands;

use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendFailedJobReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-report:failed-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send failed jobs report every one 5 min';

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
        $reportSubject = $this->signature."-".date("Y-M-D");
        
        try {
            $beforeFiveMin = Carbon::now()->subMinutes(5)->toDateTimeString();
            $failedReports = \DB::table("failed_jobs")->where("failed_at",">",$beforeFiveMin)->get();
            if(!$failedReports->isEmpty()) {
                throw new \Exception("Error Processing jobs, Total Failed Jobs in last five min : ".$failedReports->count(), 1);
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($reportSubject, $e->getMessage());
        }
    }
}
