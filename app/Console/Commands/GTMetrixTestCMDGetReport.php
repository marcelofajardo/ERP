<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CronJobReport;
use App\WebsiteStoreView;
use App\StoreViewsGTMetrix;
use Carbon\Carbon;

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;

class GTMetrixTestCMDGetReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GT-metrix-test-get-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GT metrix get site report';

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
            \Log::info('GTMetrix :: Report cron start ' );
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $client = new GTMetrixClient();
            $client->setUsername(env('GTMETRIX_USERNAME'));
            $client->setAPIKey(env('GTMETRIX_API_KEY'));
            $client->getLocations();
            $client->getBrowsers();

            $storeViewListNotQueued = StoreViewsGTMetrix::whereNotNull('website_url')
                                        ->where('status','not_queued')
                                        ->get()->toArray();

            
            foreach ($storeViewListNotQueued as $value) {
                try {
                    $test  = $client->startTest( $value['website_url'] );
                    $update = [
                        'test_id' => $test->getId(),
                        'status'  => 'queued',
                    ];
                    StoreViewsGTMetrix::where('id',$value['id'])->update( $update );
                } catch (\Exception $e) {
                    \Log::error($this->signature.' :: '.$e->getMessage() );
                    break;
                }
            }
            
            // Get site report
            $storeViewList = StoreViewsGTMetrix::whereNotNull('test_id')
                            ->whereNotIn('status',['completed','error'])
                            ->get()->toArray();
            foreach ($storeViewList as $value) {
                $test = $client->getTestStatus( $value['test_id'] );
                StoreViewsGTMetrix::where('test_id',$value['test_id'])->where('store_view_id',$value['store_view_id'])->update( [
                    'status'          => $test->getState(),
                    'error'           => $test->getError(),
                    'report_url'      => $test->getReportUrl(),
                    'html_load_time'  => $test->getHtmlLoadTime(),
                    'html_bytes'      => $test->getHtmlBytes(),
                    'page_load_time'  => $test->getPageLoadTime(),
                    'page_bytes'      => $test->getPageBytes(),
                    'page_elements'   => $test->getPageElements(),
                    'pagespeed_score' => $test->getPagespeedScore(),
                    'yslow_score'     => $test->getYslowScore(),
                    'resources'       => json_encode($test->getResources()),
                ]);
            }

            \Log::info('GTMetrix :: Report cron complete ' );
            $report->update(['end_time' => Carbon::now()]);

        } catch (\Exception $e) {
            \Log::error($this->signature.' :: '.$e->getMessage() );
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
