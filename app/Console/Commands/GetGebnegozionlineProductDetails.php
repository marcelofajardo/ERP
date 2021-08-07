<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Services\Scrap\GebnegozionlineProductDetailsScraper;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GetGebnegozionlineProductDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gebnegozionline:get-products-detail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $scraper;

    /**
     * Create a new command instance.
     *
     * @param GebnegozionlineProductDetailsScraper $scraper
     */
    public function __construct(GebnegozionlineProductDetailsScraper $scraper)
    {
        $this->scraper = $scraper;
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

            $letters = env('SCRAP_ALPHAS', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            
            if (strpos($letters, 'G') === false) {
                return;
            }
            
            $this->scraper->scrap();

            $report->update(['end_time' => Carbon::now()]);

        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
