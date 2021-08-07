<?php

namespace App\Console\Commands;

use App\ChatMessage;
use App\Vendor;
use App\CroppedImageReference;
use App\Customer;
use App\Helpers\StatusHelper;
use App\Product;
use App\ReplyCategory;
use App\Supplier;
use Cache;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CacheMasterControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:master-control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load all master control variables into cache';

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
        try {

            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // update cache for the cropped image references -- using in master control
            Cache::remember('cropped_image_references', 30, function () {
                return CroppedImageReference::count();
            });

            // update pending crop references -- using in master control
            Cache::remember('pending_crop_reference', 30, function () {
                return Product::where('status_id', StatusHelper::$autoCrop)->where('stock', '>=', 1)->count();
            });

            // update pending crop references -- using in master control
            Cache::remember('crop_reference_week_count', 30, function () {
                return CroppedImageReference::where('created_at', '>=', \Carbon\Carbon::now()->subDays(7)->startOfDay())->count();
            });

            Cache::remember('cronLastErrors', 30, function () {
                return \App\CronJob::join("cron_job_reports as cjr", "cron_jobs.signature", "cjr.signature")
                    ->where("cjr.start_time", '>', \DB::raw('NOW() - INTERVAL 24 HOUR'))->where("cron_jobs.last_status", "error")->groupBy("cron_jobs.signature")->get();
            });

            // update crop reference daily count -- using in master control
            Cache::remember('crop_reference_daily_count', 30, function () {
                return CroppedImageReference::whereDate('created_at', Carbon::today())->count();
            });

            // pending crop category -- using in master control
            Cache::remember('pending_crop_category', 30, function () {
                return Product::where('status_id', StatusHelper::$attributeRejectCategory)->where('stock', '>=', 1)->count();
            });

            // status count -- using in master control
            Cache::remember('status_count', 30, function () {
                return StatusHelper::getStatusCount();
            });

            // scraped product in stock -- using in master control
            Cache::remember('result_scraped_product_in_stock', 30, function () {
                $sqlScrapedProductsInStock = "
                    SELECT
                        COUNT(DISTINCT(sp.sku)) as ttl
                    FROM
                        suppliers s
                    JOIN
                        scrapers sc
                    ON
                        s.id=sc.supplier_id
                    JOIN
                        scraped_products sp
                    ON
                        sp.website=sc.scraper_name
                    WHERE
                        s.supplier_status_id=1 AND
                        sp.validated=1 AND
                        sp.website!='internal_scraper' AND
                        sp.last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY)";

                return DB::select($sqlScrapedProductsInStock);
            });

            //Getting Customer Chat
            $chat = null;//ChatMessage::where('created_at','>=', Carbon::now()->subDay()->toDateTimeString());

            /*Cache::remember('result_customer_chat', 30, function () use ($chat) {

                $chatCustomers = clone $chat;
                $customerChats = $chatCustomers->select('customer_id')->whereNotNull('customer_id')->whereNotNull('number')->orderBy('created_at', 'desc')->groupBy('customer_id')->get()->toArray();

                $customerArrays = [];
                foreach ($customerChats as $customerChat) {
                    $customerArrays[] = $customerChat['customer_id'];
                }

                $customerPlaceholders = implode(',', array_fill(0, count($customerArrays), '?'));

                $customers = [];
                if (!empty($customerPlaceholders)) {
                    $customers = Customer::select('id', 'name', 'phone')->whereIn('id', $customerArrays)->orderByRaw("field(id,{$customerPlaceholders})", $customerArrays)->get();
                }

                return $customers;
            });

            //Getting Supplier Chat
            Cache::remember('result_supplier_chat', 30, function () use ($chat) {

                $chatSuppliers = clone $chat;

                $supplierChats = $chatSuppliers->select('supplier_id')->whereNotNull('supplier_id')->orderBy('created_at', 'desc')->groupBy('supplier_id')->get()->toArray();

                $supplierArrays = [];
                if (!empty($supplierChats)) {
                    foreach ($supplierChats as $supplierChat) {
                        $supplierArrays[] = $supplierChat['supplier_id'];
                    }
                }

                $supplierPlaceholders = implode(',', array_fill(0, count($supplierArrays), '?'));

                $suppliers = [];
                if (!empty($supplierPlaceholders)) {
                    $suppliers = Supplier::whereIn('id', $supplierArrays)->orderByRaw("field(id,{$supplierPlaceholders})", $supplierArrays)->get();
                }

                return $suppliers;
            });

            //Getting Vendor Chat
            Cache::remember('result_vendor_chat', 30, function () use ($chat) {

                $vendorChats = $chat->select('vendor_id')->whereNotNull('vendor_id')->orderBy('created_at', 'desc')->groupBy('vendor_id')->get()->toArray();

                $vendorArrays = [];
                if (!empty($vendorChats)) {
                    foreach ($vendorChats as $vendorChat) {
                        $vendorArrays[] = $vendorChat['vendor_id'];
                    }
                }

                $vendorPlaceholders = implode(',', array_fill(0, count($vendorArrays), '?'));
                $vendors            = [];
                if (!empty($vendorPlaceholders)) {
                    $vendors = Vendor::whereIn('id', $vendorArrays)->orderByRaw("field(id,{$vendorPlaceholders})", $vendorArrays)->get();
                }

                return $vendors;

            });*/

            Cache::remember('reply_categories', 30, function () use ($chat) {
                return $reply_categories = ReplyCategory::all();
            });

            Cache::remember('vendorReplier', 30, function () use ($chat) {
                return $vendorReplier = \App\Reply::where("model", "Vendor")->whereNull("deleted_at")->pluck("reply", "id")->toArray();
            });

            Cache::remember('supplierReplier', 30, function () use ($chat) {
                return $supplierReplier = \App\Reply::where("model", "Supplier")->whereNull("deleted_at")->pluck("reply", "id")->toArray();
            });

            Cache::remember('latestScrapRemarks', 30, function () use ($chat) {
                return \DB::select("
                    select * 
                    from scrap_remarks as sr 
                    join ( select max(id) as id from scrap_remarks group by scraper_name) as max_s on sr.id =  max_s.id order by created_at desc"
                );
            });

            Cache::remember('todaytaskhistory', 30, function (){
                $date = "'%".date('Y-m-d')."%'";   

                return DB::select("SELECT users.name, developer_tasks.subject, developer_tasks.id as devtaskId,tasks.id as task_id,tasks.task_subject as task_subject,  hubstaff_activities.starts_at, SUM(tracked) as day_tracked 
                  FROM `users` 
                  JOIN hubstaff_members ON hubstaff_members.user_id=users.id 
                  JOIN hubstaff_activities ON hubstaff_members.hubstaff_user_id=hubstaff_activities.user_id 
                  LEFT JOIN developer_tasks ON hubstaff_activities.task_id=developer_tasks.hubstaff_task_id 
                  LEFT JOIN tasks ON hubstaff_activities.task_id=tasks.hubstaff_task_id 
                  WHERE ( (`hubstaff_activities`.`starts_at` LIKE " . $date . ") AND (developer_tasks.id is NOT NULL or tasks.id is not null) and hubstaff_activities.task_id > 0)
                    GROUP by hubstaff_activities.task_id
                    order by day_tracked desc ");
            });

            Cache::remember('hubstafftrackingnotiification', 30, function (){

                $yesterdayDate = Carbon::now()->subDays(1)->format('Y-m-d');



                $hubstaff_notifications = \App\Hubstaff\HubstaffActivityNotification::join("users as u", "hubstaff_activity_notifications.user_id", "u.id")->leftJoin("user_avaibilities as av", "hubstaff_activity_notifications.user_id", "av.user_id")
                ->whereDate('start_date',$yesterdayDate)
                ->select([
                    "hubstaff_activity_notifications.*", 
                    "u.name as user_name",
                    "av.minute as daily_working_hour",
                ])
                ->get()->toArray();

                return $hubstaff_notifications;

            });


            Cache::remember('hubstafftrackingnotiification', 30, function (){
                
                $yesterdayDate = Carbon::now()->subDays(1)->format('Y-m-d');

                

                $hubstaff_notifications = \App\Hubstaff\HubstaffActivityNotification::join("users as u", "hubstaff_activity_notifications.user_id", "u.id")->leftJoin("user_avaibilities as av", "hubstaff_activity_notifications.user_id", "av.user_id")
                ->whereDate('start_date',$yesterdayDate)
                ->select([
                    "hubstaff_activity_notifications.*", 
                    "u.name as user_name",
                    "av.minute as daily_working_hour",
                ])
                ->get()->toArray();

                return $hubstaff_notifications;

            });


            $report->update(['end_time' => Carbon::now()]);

        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
