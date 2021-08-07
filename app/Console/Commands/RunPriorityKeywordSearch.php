<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HashTag;

class RunPriorityKeywordSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:priority-keyword-search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run priority keyword search - normal, affiliate';

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
        //call priority api call for google search
        // $searchKeywords = HashTag::where('priority', 1)->where('platforms_id', 2)->get(['hashtag', 'id']);
        $searchKeywords = [];
        $postData = json_encode($searchKeywords);

        // call this endpoint - /api/googleSearch
        $this->callCurl(env('NODE_SCRAPER_SERVER') . "api/googleSearch", $postData);

        //call priority api call for google affiliate search
        $affiliateKeywords = HashTag::where('priority', 1)->where('platforms_id', 3)->get(['hashtag', 'id']);

        // $affiliateKeywords = [];
        $postData = json_encode($affiliateKeywords);

        // call this endpoint - /api/googleSearchDetails
        $this->callCurl(env('NODE_SCRAPER_SERVER') . "api/googleSearchDetails", $postData);
    }

    function callCurl($url, $postData){
        // call this endpoint
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
        ),
        CURLOPT_POSTFIELDS => "$postData"
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    }
}
