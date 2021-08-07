<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CronJobReport;
use App\WebsiteStoreView;
use App\StoreViewsGTMetrix;
use Carbon\Carbon;
use App\Setting;

use Entrecore\GTMetrixClient\GTMetrixClient;
use Entrecore\GTMetrixClient\GTMetrixTest;

class GTMetrixTestCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GT-metrix-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GT metrix test all site';

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
            \Log::info('GTMetrix :: Daily cron start ' );
            $cronStatus = Setting::where('name',"gtmetrixCronStatus")->get()->first();
            
            if( !empty($cronStatus) && $cronStatus->val == 'stop' ){
                \Log::info('GTMetrix :: stopped' );
                return false;
            }

            $cronType    = Setting::where('name',"gtmetrixCronType")->get()->first();
            $cronRunTime = Setting::where('name',"gtmetrixCronRunDate")->get()->first();
            
            if( !empty( $cronRunTime ) ){

                if( $cronRunTime->val != now()->format('Y-m-d') && $cronType->val != 'daily' ){
                    \Log::info('GTMetrix :: cron run time false' );
                    return false;
                }
            }

            if( !empty( $cronType ) && $cronType->val == 'weekly' ){
                $nextDate = now()->addWeeks(1)->format('Y-m-d');
            }else{
                $nextDate = now()->tomorrow()->format('Y-m-d');
            }
            $this->nextCronRunTime( $nextDate );
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            \Log::info('GTMetrix :: Daily cron start ' );
            $client = new GTMetrixClient();
            $client->setUsername(env('GTMETRIX_USERNAME'));
            $client->setAPIKey(env('GTMETRIX_API_KEY'));
            $client->getLocations();
            $client->getBrowsers();

            $storeViewList = WebsiteStoreView::whereNotNull('website_store_id')
                            // ->where('website_store_views.id',977)
                            ->join("website_stores as ws", "ws.id", "website_store_views.website_store_id")
                            ->join("websites as w", "w.id", "ws.website_id")
                            ->join("store_websites as sw", "sw.id", "w.store_website_id")
                            ->select("website_store_views.code","website_store_views.id", "sw.website")
                            ->get()->toArray();
            
            \Log::info('GTMetrix :: store website =>'.sizeof( $storeViewList ) );

            $request_too_many_pending = false;

            foreach ($storeViewList as $value) {
                $webite = $value['website'].'/'.$value['code'];

                if ( $request_too_many_pending ) {

                    $create = [
                        'store_view_id' => $value['id'],
                        'status'        => 'not_queued',
                        'website_url'   => $webite,
                    ];
                    StoreViewsGTMetrix::create( $create );
                    continue;
                }

                try {
                    $test  = $client->startTest( $webite );
                    $create = [
                        'store_view_id' => $value['id'],
                        'test_id'       => $test->getId(),
                        'status'        => 'queued',
                        'website_url'   => $webite,
                    ];
                } catch (\Exception $e) {
                    \Log::error($this->signature .' :: '.$e->getMessage() );
                    $request_too_many_pending = true;
                    $create = [
                        'store_view_id' => $value['id'],
                        'status'        => 'not_queued',
                        'error'         => $e->getMessage(),
                        'website_url'   => $webite,
                    ];
                }
                StoreViewsGTMetrix::create( $create );
            }

            // Get tested site report 
            // \Artisan::call('GT-metrix-test-get-report');
            \Log::info('GTMetrix :: Daily run complete ');
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \Log::error('GTMetrix :: '.$e->getMessage() );
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    public function nextCronRunTime( $date = null )
    {
        
        $type = Setting::where('name',"gtmetrixCronRunDate")->get()->first();
        if(empty($type)) {
            $type['name'] = "gtmetrixCronRunDate";
            $type['type'] = "date";
            $type['val']  = $date;
            Setting::create($type);
        } else {
            $type->val = $date;
            $type->save();
        }
        return true;
    }
}
