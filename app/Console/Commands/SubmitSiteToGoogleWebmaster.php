<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\WebsiteStoreView;
use App\WebsiteStoreViewsWebmasterHistory;
use App\CronJobReport;
use Carbon\Carbon;

class SubmitSiteToGoogleWebmaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submit-site-to-google-webmaster';

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

            $fetchStores = WebsiteStoreView::whereNotNull('website_store_id')
                    ->whereNotIn('site_submit_webmaster',[1])
                    ->join("website_stores as ws", "ws.id", "website_store_views.website_store_id")
                    ->join("websites as w", "w.id", "ws.website_id")
                    ->join("store_websites as sw", "sw.id", "w.store_website_id")
                    ->select("website_store_views.code","website_store_views.id", "sw.website")
                    ->get()->toArray();
            
            foreach ($fetchStores as $key => $value) {

                $websiter = urlencode(utf8_encode($value['website'].'/'.$value['code']));
                $url_for_sites = 'https://searchconsole.googleapis.com/webmasters/v3/sites/'.$websiter;
                $token         = env('GOOGLE_CLIENT_ACCESS_TOKEN');

        		$curl = curl_init();
                //replace website name with code coming form site list
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url_for_sites,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_HTTPHEADER => array( 
                        'Accept: application/json',
                        'Content-length: 0', 
                        "authorization: Bearer ".$token
                    ),
                ));
                $response = curl_exec($curl);
                $response = json_decode($response);

                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                    \Log::info($this->signature.' Request URL::'.$url_for_sites);
                    \Log::info($this->signature.' Request Token::'.$token);
                    \Log::error($this->signature.' Error Msg::'.$error_msg);
                }

                curl_close($curl);

                if( !empty($response) ){
                    $history = [
                        'website_store_views_id' => $value['id'],
                        'log' => isset( $response->error->message ) ? $response->error->message : 'Error'
                    ];
                
                    WebsiteStoreViewsWebmasterHistory::insert( $history );

                    \Log::info($this->signature.' Request URL::'.$url_for_sites);
                    \Log::info($this->signature.' Request Token::'.$token);
                    \Log::error($this->signature.' Error Msg::'.$response->error->message);
                }else{
                    WebsiteStoreView::where('id', $value['id'])->update( [ 'site_submit_webmaster' => 1 ] );
                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \Log::error($this->signature.' Error Msg::'.$e->getMessage());
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
