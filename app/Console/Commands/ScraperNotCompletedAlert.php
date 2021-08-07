<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Scraper;
use App\ScrapRemark;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScraperNotCompletedAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:not-completed-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store an alert if scraper not completed';

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
            $scrapers = Scraper::whereNull('last_completed_at')
                            ->orWhere('last_completed_at', '<', 
                                Carbon::now()->subHours(30)->toDateTimeString()
                            )->get();
            if(count($scrapers)) {
                foreach($scrapers as $key => $item) {
                    $remark_entry = ScrapRemark::create([
                        'scraper_name' => $item->scraper_name,
                        'remark' => "Scraper not completed",
                    ]);
                }
            }
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
