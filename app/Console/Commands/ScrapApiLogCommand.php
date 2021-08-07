<?php

namespace App\Console\Commands;

use App\ScrapApiLog;
use App\Scraper;
use Illuminate\Console\Command;

class ScrapApiLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ScrapApi:LogCommand';

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

        ScrapApiLog::where('created_at', '<', now()->subDays(7))->delete();

        $activeSuppliers = Scraper::with([
            'scraperDuration' => function ($q) {
                $q->orderBy('id', 'desc');
            },
            'scrpRemark' => function ($q) {
                $q->whereNull("scrap_field")->where('user_name', '!=', '')->orderBy('created_at', 'desc');
            },
            'latestMessageNew' => function ($q) {
                $q->whereNotIn('chat_messages.status', ['7', '8', '9', '10'])
                    ->take(1)
                    ->orderBy("id", "desc");
            },
            'lastErrorFromScrapLogNew',
            'developerTaskNew',
            'scraperMadeBy',
            'childrenScraper.scraperMadeBy',
            'mainSupplier',

        ])
            ->withCount('childrenScraper')
            ->join("suppliers as s", "s.id", "scrapers.supplier_id")
            ->where('supplier_status_id', 1)
            ->whereIn("scrapper", [1, 2])
            ->whereNull('parent_id')->get();

        foreach ($activeSuppliers as $key => $supplier) {
            $scraper = Scraper::find($supplier->id);
            if (!$scraper->parent_id) {
                $name = $scraper->scraper_name;
            } else {
                $name = $scraper->parent->scraper_name . '/' . $scraper->scraper_name;
            }

            // $url = 'http://' . $supplier->server_id . '.theluxuryunlimited.com:' . env('NODE_SERVER_PORT') . '/send-position?website=' . $name;
            $url = 'http://' . $supplier->server_id . '.theluxuryunlimited.com:' . config('env.NODE_SERVER_PORT') . '/send-position?website=' . $name;

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($curl);

            if (!empty($response)) {

                $response = json_decode($response);

                if (!empty($response->log)) {
                    $log = base64_decode($response->log);

                    if (!empty($log)) {

                        $api_log = new ScrapApiLog;
                        $api_log->scraper_id = $scraper->id;
                        $api_log->server_id = $scraper->server_id;
                        $api_log->log_messages = $log;
                        $api_log->save();
                    }

                }

            }

        }
    }
}
