<?php

namespace App\Console\Commands;

use App\Scraper;
use App\ScraperProcess;
use Illuminate\Console\Command;

class AssignScraperProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign-scrap-process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Scraper process';

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
        //
        $readFile = env('SCRAPER_PROCESS_LOGS_FILE');

        if (!empty($readFile)) {
            $lines = @file($readFile);
            if (!empty($lines)) {
                foreach ($lines as $line) {
                    $data        = explode(" ", $line);
                    $scraperName = $data[0];
                    $serverId    = $data[1];
                    $startAt     = $data[2];
                    $endAt       = $data[3];

                    $scraper = Scraper::where("scraper_name", $scraperName)->first();

                    if ($scraper) {
                        $insert = ScraperProcess::create([
                            "scraper_id"   => $scraper->id,
                            "scraper_name" => $scraperName,
                            "server_id"    => $serverId,
                            "started_at"   => $startAt,
                            "ended_at"     => (stripos($data[3], "processing") !== false) ? null : $data[3],
                        ]);
                    }

                }
            }
        }
    }
}
