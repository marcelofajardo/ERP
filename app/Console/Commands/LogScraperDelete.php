<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Loggers\LogScraper;
use Carbon\Carbon;

class LogScraperDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log_scraper:delete';

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
        //where ArrivalDate < DATE_SUB(NOW(), INTERVAL 15 DAY);
        $allLogs = LogScraper::where('created_at', '<=', Carbon::now()->subDays(15)->toDateTimeString())->delete();
    }
}
