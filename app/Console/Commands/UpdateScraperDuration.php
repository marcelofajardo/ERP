<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Supplier;
use App\Scraper;
use App\ScraperDuration;
use Illuminate\Support\Facades\Log;

class UpdateScraperDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateScraperDuration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'UpdateScraperDuration';

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
        $activeSuppliers = Scraper::with([
            'scraperDuration' => function($q){
                $q->orderBy('id', 'desc');
            },
            'scrpRemark' => function($q){
                $q->whereNull("scrap_field")->where('user_name','!=','')->orderBy('created_at','desc');
            },
            'latestMessageNew' => function($q){
                $q->whereNotIn('chat_messages.status', ['7', '8', '9', '10'])
                ->take(1)
                ->orderBy("id","desc");
            },
            'lastErrorFromScrapLogNew',
            'developerTaskNew',
            'scraperMadeBy',
            'childrenScraper.scraperMadeBy',
            'mainSupplier'
        ])
        ->withCount('childrenScraper')
        ->join("suppliers as s", "s.id", "scrapers.supplier_id")
            ->where('supplier_status_id', 1)
            ->whereIn("scrapper", [1, 2])
            ->whereNull('parent_id')
            ->orderby('scrapers.flag', 'desc')
            ->orderby('s.supplier', 'asc')
            ->get();

        foreach($activeSuppliers as $scraper){
            if($scraper->server_id){
                if (!$scraper->parent_id) {
                    $name = $scraper->scraper_name;
                } else {
                    $name = $scraper->parent->scraper_name . '/' . $scraper->scraper_name;
                }
        
                /* This curl need to replace with guzzleHttp but for now i am keeping this. */

                // $url = 'http://' . $scraper->server_id . '.theluxuryunlimited.com:' . env('NODE_SERVER_PORT') . '/process-list?filename=' . $name . '.js'; 
                $url = 'http://' . $scraper->server_id . '.theluxuryunlimited.com:' . config('env.NODE_SERVER_PORT') . '/process-list?filename=' . $name . '.js'; 

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                curl_close($curl);
                $duration = json_decode($response);

                if(empty($duration->Process[0])){
                    Log::debug("Scrapper Duration Log: => " . $response);
                    continue;
                }

                $pid = $duration->Process[0]->pid;      
                $duration = isset($duration->Process[0]->duration) ? $duration->Process[0]->duration : null ;      

                if ($duration) {

                    $duration = explode(' ', $duration);
                    $text = '';
                    if(in_array('Hours', $duration)){
                        $text .= (strlen($duration[0]) == 2 ? $duration[0] : '0'.$duration[0]) . ':';
                        $text .= $duration[0] . ':';
                    }else{
                        $text .= '00:';
                    }
                    if(in_array('Miuntes', $duration)){
                        $text .= (strlen($duration[array_search('Miuntes', $duration) - 1]) == 2 ? $duration[array_search('Miuntes', $duration) - 1] : '0'.$duration[array_search('Miuntes', $duration) - 1]) . ':';
                            }else{
                        $text .= '00:';
                    }
                    if(in_array('Seconds', $duration)){
                        $text .= (strlen($duration[array_search('Seconds', $duration) - 1]) == 2 ? $duration[array_search('Seconds', $duration) - 1] : '0'.$duration[array_search('Seconds', $duration) - 1]);
                    }else{
                        $text .= '00';
                    }

                    $scrap_duration = ScraperDuration::where('scraper_id', $scraper->id)->where('process_id', $pid)->first();
                    if(!$scrap_duration){
                        $scrap_duration = new ScraperDuration();
                    } 
                    $scrap_duration->scraper_id = $scraper->id;
                    $scrap_duration->process_id = $pid;
                    $scrap_duration->duration = $text; 
                    $scrap_duration->save();
                    // dump($scrap_duration->id . ' => ' . $text);
                    
                } 
            }
        }
            

    }
}
