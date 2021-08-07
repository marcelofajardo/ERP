<?php

namespace App\Console\Commands;

use App\CompetitorFollowers;
use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;

class SendDmToCompetitorsFollowers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'competitors:dm-followers-following';

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

            $competitorFollowes = CompetitorFollowers::where('status', 2)->get();

            if ($competitorFollowes->count() === 0) {
                return;
            }

            $ig = new Instagram();

            foreach ($competitorFollowes as $competitorFollower) {
//            $
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
