<?php

namespace App\Console;

use App\Console\Commands\CheckScrapersLog;
use App\Console\Commands\DocumentReciever;
use App\Console\Commands\DoubleFProductDetailScraper;
use App\Console\Commands\DoubleFScraper;
use App\Console\Commands\EnrichWiseProducts;
use App\Console\Commands\FixCategoryNameBySupplier;
use App\Console\Commands\FlagCustomersIfTheyHaveAComplaint;
use App\Console\Commands\GetGebnegozionlineProductDetails;
use App\Console\Commands\GetGebnegozionlineProductDetailsWithEmulator;
use App\Console\Commands\GetGebnegozionlineProductEntries;
use App\Console\Commands\GetMostUsedWordsInCustomerMessages;
use App\Console\Commands\GrowInstagramAccounts;
use App\Console\Commands\MailingListSendMail;
use App\Console\Commands\MakeApprovedImagesSchedule;
use App\Console\Commands\MakeKeywordAndCustomersIndex;
use App\Console\Commands\PostScheduledMedia;
use App\Console\Commands\CheckLogins;
use App\Console\Commands\AutoInterestMessage;
use App\Console\Commands\AutoReminder;
use App\Console\Commands\AutoMessenger;
use App\Console\Commands\FetchEmails;
use App\Console\Commands\FetchAllEmails;
use App\Console\Commands\CheckEmailsErrors;
use App\Console\Commands\SaveProductsImages;
use App\Console\Commands\MessageScheduler;
use App\Console\Commands\SendAutoReplyToCustomers;
use App\Console\Commands\SendMessageToUserIfTheirTaskIsNotComplete;
use App\Console\Commands\SendPendingTasksReminders;
use App\Console\Commands\SendRecurringTasks;
use App\Console\Commands\CheckMessagesErrors;
use App\Console\Commands\SendBroadcastMessageToColdLeads;
use App\Console\Commands\SendProductSuggestion;
use App\Console\Commands\SendActivitiesListing;
use App\Console\Commands\SendDailyPlannerReport;
use App\Console\Commands\ProcessCommentsFromCompetitors;
//use App\Console\Commands\SyncInstagramMessage;
use App\Console\Commands\SendReminderToCustomerIfTheyHaventReplied;
use App\Console\Commands\SendReminderToDubbizlesIfTheyHaventReplied;
use App\Console\Commands\SendReminderToSupplierIfTheyHaventReplied;
use App\Console\Commands\SendReminderToVendorIfTheyHaventReplied;
use App\Console\Commands\SendReminderToTaskIfTheyHaventReplied;
use App\Console\Commands\SendReminderToDevelopmentIfTheyHaventReplied;
use App\Console\Commands\UpdateInventory;
use App\Console\Commands\UpdateSkuInGnb;
use App\Console\Commands\CreateScrapedProducts;
use App\Console\Commands\UploadProductsToMagento;
use App\Console\Commands\UpdateGnbPrice;
use App\Console\Commands\DeleteGnbProducts;
use App\Console\Commands\DeleteWiseProducts;
use App\Console\Commands\UpdateWiseProducts;
use App\Console\Commands\UpdateWiseCategory;
use App\Console\Commands\UpdateDoubleProducts;
use App\Console\Commands\ScheduleList;
use App\Console\Commands\IncrementFrequencyWhatsappConfig;
use App\Console\Commands\SendHourlyReports;
use App\Console\Commands\RunMessageQueue;
use App\Console\Commands\MonitorCronJobs;
use App\Console\Commands\SendVoucherReminder;
use App\Console\Commands\VisitorLogs;
use App\Console\Commands\InfluencerDescription;

use App\Console\Commands\MovePlannedTasks;
use App\Console\Commands\ResetDailyPlanner;
use App\Console\Commands\SkuErrorCount;
use App\Console\Commands\ImageBarcodeGenerator;
use App\Console\Commands\UpdateImageBarcodeGenerator;
use App\Console\Commands\SetTemplatesForProduct;

//use App\Console\Commands\SaveProductsImages;

use App\Console\Commands\UpdateMagentoProductStatus;
use App\Console\Commands\ImportCustomersEmail;
use App\Console\Commands\TwilioCallLogs;
use App\Console\Commands\ZoomMeetingRecordings;
use App\Console\Commands\ZoomMeetingDeleteRecordings;
use App\Console\Commands\RecieveResourceImages;
use App\Console\Commands\CheckWhatsAppActive;
use App\Console\Commands\ParseLog;
use App\Http\Controllers\MagentoController;
use App\Http\Controllers\NotificaitonContoller;
use App\Http\Controllers\NotificationQueueController;
use App\Console\Commands\UpdateShoeAndClothingSizeFromChatMessages;
use App\Console\Commands\UpdateCustomerSizeFromOrder;
use App\Console\Commands\CreateErpLeadFromCancellationOrder;
use App\Console\Commands\SendQueuePendingChatMessages;
use App\Console\Commands\SendQueuePendingChatMessagesGroup;
use App\Console\Commands\SyncCustomersFromMagento;
use App\Console\Commands\StoreChatMessagesToAutoCompleteMessages;

use App\NotificationQueue;
use App\Benchmark;
use App\Task;
use Carbon\Carbon;
use App\CronJobReport;
use App\Console\Commands\UpdateCronSchedule;
use App\Console\Commands\RunErpEvents;
use App\Console\Commands\RunErpLeads;
use App\Console\Commands\GetOrdersFromnMagento;
use App\Console\Commands\NumberOfImageCroppedCheck;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\StoreBrands;
use App\Console\Commands\StoreLiveChats;
use App\Console\Commands\RunPriorityKeywordSearch;
use App\Console\Commands\CacheMasterControl;
use App\Console\Commands\SendEventNotificationBefore24hr;
use App\Console\Commands\SendEventNotificationBefore2hr;
//use App\Console\Commands\SendEventNotificationBefore30Min;
use App\Console\Commands\AccountHubstaffActivities;
use App\Console\Commands\DailyHubstaffActivityLevel;
use App\Console\Commands\GenerateProductPricingJson;
use seo2websites\ErpExcelImporter\Console\Commands\EmailExcelImporter;
use App\Console\Commands\FetchStoreWebsiteOrder;
use App\Console\Commands\UserPayment;
use App\Console\Commands\ScrapLogs;
use App\Console\Commands\AuthenticateWhatsapp;
use App\Console\Commands\getLiveChatIncTickets;
use App\Console\Commands\RoutesSync;
use App\Console\Commands\DeleteChatMessages;
use seo2websites\PriceComparisonScraper\PriceComparisonScraperCommand;
use App\Console\Commands\CustomerListToEmailLead;
use App\Console\Commands\WayBillTrackHistories;
use App\Console\Commands\ProjectDirectory;
use App\Console\Commands\LogScraperDelete;
use App\Console\Commands\AssetsManagerPaymentCron;
use App\Console\Commands\SendEmailNewsletter;
use App\Console\Commands\DeleteStoreWebsiteCategory;
use App\Console\Commands\RunGoogleAnalytics;
use App\Console\Commands\scrappersImages;
use App\Console\Commands\scrappersImagesDelete;
use App\Console\Commands\productActivityStore;
use App\Console\Commands\errorAlertMessage;
use App\Console\Commands\InstagramHandler;
use App\Console\Commands\SendDailyReports;
use App\Console\Commands\SendDailyLearningReports;
use App\Console\Commands\InsertPleskEmail;
use App\Console\Commands\SendDailyPlannerNotification;
use App\Console\Commands\RemoveScrapperImages;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PostScheduledMedia::class,
        CheckLogins::class,
        //        SyncInstagramMessage::class,
        GetGebnegozionlineProductDetails::class,
        GetGebnegozionlineProductEntries::class,
        AutoInterestMessage::class,
        AutoReminder::class,
        AutoMessenger::class,
        FetchEmails::class,
        FetchAllEmails::class,
        CheckEmailsErrors::class,
        MessageScheduler::class,
        SendRecurringTasks::class,
        CheckMessagesErrors::class,
        SendProductSuggestion::class,
        SendActivitiesListing::class,
        SendPendingTasksReminders::class,
        MakeApprovedImagesSchedule::class,
        UpdateSkuInGnb::class,
        CreateScrapedProducts::class,
        UpdateGnbPrice::class,
        DeleteGnbProducts::class,
        DeleteWiseProducts::class,
        UpdateWiseProducts::class,
        UpdateWiseCategory::class,
        UpdateDoubleProducts::class,
        EnrichWiseProducts::class,
        DoubleFProductDetailScraper::class,
        DoubleFScraper::class,
        SendHourlyReports::class,
        SaveProductsImages::class,
        RunMessageQueue::class,
        MonitorCronJobs::class,
        SendVoucherReminder::class,
        GetGebnegozionlineProductDetailsWithEmulator::class,
        UpdateInventory::class,
        UpdateMagentoProductStatus::class,
        SendBroadcastMessageToColdLeads::class,
        MovePlannedTasks::class,
        SendDailyPlannerReport::class,
        ResetDailyPlanner::class,
        //        SaveProductsImages::class,
        GrowInstagramAccounts::class,
        SendMessageToUserIfTheirTaskIsNotComplete::class,
        SendReminderToCustomerIfTheyHaventReplied::class,
        UploadProductsToMagento::class,
        SendAutoReplyToCustomers::class,
        FixCategoryNameBySupplier::class,
        ImportCustomersEmail::class,
        TwilioCallLogs::class,
        ZoomMeetingRecordings::class,
        ZoomMeetingDeleteRecordings::class,
        FlagCustomersIfTheyHaveAComplaint::class,
        MakeKeywordAndCustomersIndex::class,
        GetMostUsedWordsInCustomerMessages::class,
        SendReminderToCustomerIfTheyHaventReplied::class,
        SendReminderToSupplierIfTheyHaventReplied::class,
        SendReminderToVendorIfTheyHaventReplied::class,
        SendReminderToTaskIfTheyHaventReplied::class,
        SendReminderToDevelopmentIfTheyHaventReplied::class,
        SendReminderToDubbizlesIfTheyHaventReplied::class,
        UpdateShoeAndClothingSizeFromChatMessages::class,
        UpdateCustomerSizeFromOrder::class,
        DocumentReciever::class,
        RecieveResourceImages::class,
        CreateErpLeadFromCancellationOrder::class,
        SendQueuePendingChatMessages::class,
        SendQueuePendingChatMessagesGroup::class,
        ScheduleList::class,
        CheckWhatsAppActive::class,
        IncrementFrequencyWhatsappConfig::class,
        UpdateCronSchedule::class,
        RunErpEvents::class,
        RunErpLeads::class,
        ParseLog::class,
        SkuErrorCount::class,
        VisitorLogs::class,
        ImageBarcodeGenerator::class,
        UpdateImageBarcodeGenerator::class,
        GetOrdersFromnMagento::class,
        SyncCustomersFromMagento::class,
        NumberOfImageCroppedCheck::class,
        SetTemplatesForProduct::class,
        CheckScrapersLog::class,
        StoreBrands::class,
        MailingListSendMail::class,
        StoreLiveChats::class,
        RunPriorityKeywordSearch::class,
        CacheMasterControl::class,
        InfluencerDescription::class,
        ProcessCommentsFromCompetitors::class,
        SendEventNotificationBefore24hr::class,
        SendEventNotificationBefore2hr::class,
        //SendEventNotificationBefore30min::class,
        AccountHubstaffActivities::class,
        DailyHubstaffActivityLevel::class,
        EmailExcelImporter::class,
        GenerateProductPricingJson::class,
        FetchStoreWebsiteOrder::class,
        UserPayment::class,
        ScrapLogs::class,
        AuthenticateWhatsapp::class,
        getLiveChatIncTickets::class,
		RoutesSync::class,
        DeleteChatMessages::class,
        PriceComparisonScraperCommand::class,
        WayBillTrackHistories::class,
        CustomerListToEmailLead::class,
        WayBillTrackHistories::class,
        ProjectDirectory::class,
        LogScraperDelete::class,
        AssetsManagerPaymentCron::class,
        SendEmailNewsletter::class,
        DeleteStoreWebsiteCategory::class,
        RunGoogleAnalytics::class,
		RunGoogleAnalytics::class,
        scrappersImages::class,
        scrappersImagesDelete::class,
        productActivityStore::class,
        errorAlertMessage::class,
        InstagramHandler::class,
        SendDailyReports::class,
        SendDailyLearningReports::class,
        SendDailyPlannerNotification::class,
        InsertPleskEmail::class,
        StoreChatMessagesToAutoCompleteMessages::class,
        RemoveScrapperImages::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('ScrapperImage:REMOVE')->hourly(); // Remove scrapper iamges older than 1 day

        // $schedule->command('reminder:send-to-dubbizle')->everyMinute()->withoutOverlapping()->timezone('Asia/Kolkata');
        // $schedule->command('reminder:send-to-vendor')->everyMinute()->withoutOverlapping()->timezone('Asia/Kolkata');
        // $schedule->command('reminder:send-to-customer')->everyMinute()->withoutOverlapping()->timezone('Asia/Kolkata');
        // $schedule->command('reminder:send-to-supplier')->everyMinute()->withoutOverlapping()->timezone('Asia/Kolkata');
        // $schedule->command('visitor:logs')->everyMinute()->withoutOverlapping()->timezone('Asia/Kolkata');



        // Store unknown categories on a daily basis
        //$schedule->command('category:missing-references')->daily();

        //This command will set the count of the words used...
        // $schedule->command('bulk-customer-message:get-most-used-keywords')->daily();

        //Get list of schedule and put list in cron jobs table
        // $schedule->command('schedule:list')->daily();

        //This command will get the influencers details and get information from it
        // $schedule->command('influencer:description')->daily();

        //Get Orders From Magento
        //2020-02-17 $schedule->command('getorders:magento')->everyFiveMinutes()->withoutOverlapping();

        //This will run every  five minutes checking and making keyword-customer relationship...
        //2020-02-17 s$schedule->command('index:bulk-messaging-keyword-customer')->everyFiveMinutes()->withoutOverlapping();

        //This will run every fifteen minutes checking if new mail is recieved for email importer...
        // $schedule->command('excelimporter:run')->everyFiveMinutes()->withoutOverlapping();

        //Flag customer if they have a complaint
        // $schedule->command('flag:customers-with-complaints')->daily();

        //Imcrement Frequency Every Day Once Whats App Config
        // $schedule->command('whatsppconfig:frequency')->daily();

        //This command sends the reply on products if they request...
        // $schedule->command('customers:send-auto-reply')->everyFifteenMinutes();


        //This command checks for the whatsapp number working properly...
        // $schedule->command('whatsapp:check')->everyFifteenMinutes();

        //assign the category to products, runs twice daily...
        //$schedule->command('category:fix-by-supplier')->twiceDaily();

        //Get Posts , Userdata as well as comments based on hastag
        // $schedule->command('competitors:process-users')->daily();


        //$schedule->command('message:send-to-users-who-exceeded-limit')->everyThirtyMinutes()->timezone('Asia/Kolkata');


//        $schedule->call(function () {
//            $report = CronJobReport::create([
//                'signature' => 'update:benchmark',
//                'start_time' => Carbon::now()
//            ]);
//
//            $benchmark = Benchmark::orderBy('for_date', 'DESC')->first()->toArray();
//            $tasks = Task::where('is_statutory', 0)->whereNotNull('is_verified')->get();
//
//            if ($benchmark[ 'for_date' ] != date('Y-m-d')) {
//                $benchmark[ 'for_date' ] = date('Y-m-d');
//                Benchmark::create($benchmark);
//            }
//
//            foreach ($tasks as $task) {
//                $time_diff = Carbon::parse($task->is_completed)->diffInDays(Carbon::now());
//
//                if ($time_diff >= 2) {
//                    $task->delete();
//                }
//            }
//
//            $report->update(['end_time' => Carbon::now()]);
//        })->dailyAt('00:00');

//2020-02-17        $schedule->call(function () {
//            \Log::debug('deQueueNotficationNew Start');
//            NotificationQueueController::deQueueNotficationNew();
//        })->everyFiveMinutes();

// THE COMMAND BELOW SEEMS TO BE A DUPLICATE FROM ANOTHER CRON TO FETCH MAGENTO ORDERS
//2020-02-17         $schedule->call(function () {
//            $report = CronJobReport::create([
//                'signature' => 'update:benchmark',
//                'start_time' => Carbon::now()
//            ]);
//
//            MagentoController::get_magento_orders();
//            //fetched magento orders...
//
//            $report->update(['end_time' => Carbon::now()]);
//        })->hourly();

        //2020-02-17 $schedule->command('product:replace-text')->everyFiveMinutes();

        //2020-02-17 $schedule->command('numberofimages:cropped')->hourly()->withoutOverlapping();

        //        $schedule->command('instagram:grow-accounts')->dailyAt('13:00')->timezone('Asia/Kolkata');
        //2020-02-17 $schedule->command('send:hourly-reports')->dailyAt('12:00')->timezone('Asia/Kolkata');
        //2020-02-17 $schedule->command('send:hourly-reports')->dailyAt('15:30')->timezone('Asia/Kolkata');
        //2020-02-17 $schedule->command('send:hourly-reports')->dailyAt('17:30')->timezone('Asia/Kolkata');
        // $schedule->command('run:message-queues')->everyFiveMinutes()->between('07:30', '17:00')->withoutOverlapping(10);
        //2020-02-17 $schedule->command('monitor:cron-jobs')->everyMinute();
        //        $schedule->command('cold-leads:send-broadcast-messages')->everyMinute()->withoutOverlapping();
        // $schedule->exec('/usr/local/php72/bin/php-cli artisan queue:work --once --timeout=120')->everyMinute()->withoutOverlapping(3);

        // $schedule->command('save:products-images')->hourly();

        // Voucher Reminders
        // $schedule->command('send:voucher-reminder')->daily();

        // Updates Magento Products status on ERP
        // $schedule->command('update:magento-product-status')->dailyAt(03);

        //        $schedule->command('post:scheduled-media')
        //            ->everyMinute();

        //Getting SKU ERROR LOG
        //2020-02-17 $schedule->command('sku-error:log')->hourly();
        // $schedule->command('check:user-logins')->everyFiveMinutes();
        // $schedule->command('send:image-interest')->cron('0 07 * * 1,4'); // runs at 7AM Monday and Thursday

        // Sends Auto messages
        // $schedule->command('send:auto-reminder')->hourly();
        // $schedule->command('send:auto-messenger')->hourly();
        // $schedule->command('check:messages-errors')->hourly();
        // $schedule->command('send:product-suggestion')->dailyAt('07:00')->timezone('Asia/Kolkata');
        // $schedule->command('send:activity-listings')->dailyAt('23:45')->timezone('Asia/Kolkata');
        // $schedule->command('run:message-scheduler')->dailyAt('01:00')->timezone('Asia/Kolkata');

        // Tasks
        //2020-02-17 $schedule->command('send:recurring-tasks')->everyFifteenMinutes()->timezone('Asia/Kolkata');
        // $schedule->command('send:pending-tasks-reminders')->dailyAt('07:30')->timezone('Asia/Kolkata');
        // $schedule->command('move:planned-tasks')->dailyAt('01:00')->timezone('Asia/Kolkata');

        // Fetches Emails
        //2020-02-17 Changed command below from fifteen minutes to hourly
        // $schedule->command('fetch:emails')->hourly();
        // $schedule->command('check:emails-errors')->dailyAt('03:00')->timezone('Asia/Kolkata');
        // $schedule->command('parse:log')->dailyAt('03:00')->timezone('Asia/Kolkata');
        //2020-02-17 $schedule->command('document:email')->everyFifteenMinutes()->timezone('Asia/Kolkata');
        //2020-02-17 $schedule->command('resource:image')->everyFifteenMinutes()->timezone('Asia/Kolkata');
        // $schedule->command('send:daily-planner-report')->dailyAt('08:00')->timezone('Asia/Kolkata');
        // $schedule->command('send:daily-planner-report')->dailyAt('22:00')->timezone('Asia/Kolkata');
        // $schedule->command('reset:daily-planner')->dailyAt('07:30')->timezone('Asia/Kolkata');

        // $schedule->command('template:product')->dailyAt('22:00')->timezone('Asia/Kolkata');

        //2020-02-17 $schedule->command('save:products-images')->cron('0 */3 * * *')->withoutOverlapping()->emailOutputTo('lukas.markeviciuss@gmail.com'); // every 3 hours

        // Update the inventory (every fifteen minutes)
        // $schedule->command('inventory:update')->dailyAt('00:00')->timezone('Asia/Dubai');

        // Auto reject listings by empty name, short_description, composition, size and by min/max price (every fifteen minutes)
        //$schedule->command('product:reject-if-attribute-is-missing')->everyFifteenMinutes();

        //This command saves the twilio call logs in call_busy_messages table...
        //2020-02-17 $schedule->command('twilio:allcalls')->everyFifteenMinutes();
        // Saved zoom recordings corresponding to past meetings based on meeting id
        // $schedule->command('meeting:getrecordings')->hourly();
        // $schedule->command('meeting:deleterecordings')->dailyAt('07:00')->timezone('Asia/Kolkata');

        // Check scrapers
        // $schedule->command('scraper:not-running')->hourly()->between('7:00', '23:00');

        // Move cold leads to customers
        // $schedule->command('cold-leads:move-to-customers')->daily();

        // send only cron run time
        $queueStartTime = \App\ChatMessage::getStartTime();
        $queueEndTime  = \App\ChatMessage::getEndTime();
        $queueTime  = \App\ChatMessage::getQueueTime();
        // check if time both is not empty then run the cron
        if(!empty($queueStartTime) && !empty($queueEndTime)) {
            if(!empty($queueTime)) {
                foreach($queueTime as $no => $time) {
                    if($time > 0) {


                        $allowCounter = true;
                        $counterNo[] = $no;
                        $schedule->command('send:queue-pending-chat-messages '.$no)->cron('*/'.$time.' * * * *')->between($queueStartTime, $queueEndTime);
                        $schedule->command('send:queue-pending-chat-group-messages '.$no)->cron('*/'.$time.' * * * *')->between($queueStartTime, $queueEndTime);

                    }
                }


            }

            /*if(!empty($allowCounter) and $allowCounter==true and !empty($counterNo))
            {
                $tempSettingData = DB::table('settings')->where('name','is_queue_sending_limit')->get();
                $numbers = array_unique($counterNo);
                foreach ($numbers as $number)
                {

                    $tempNo = $number;
                    $settingData = $tempSettingData[0];
                    $messagesRules = json_decode($settingData->val);
                    $counter = ( !empty($messagesRules->$tempNo) ? $messagesRules->$tempNo : 0);
                    $insert_data = null;

                    $insert_data = array(
                        'counter'=>$counter,
                        'number'=>$number,
                        'time'=>now()
                    );
                    DB::table('message_queue_history')->insert($insert_data);

                }
            }*/
        }


        // need to run this both cron every minutes
        //2020-02-17 $schedule->command('cronschedule:update')->everyMinute();
        //2020-02-17 $schedule->command('erpevents:run')->everyMinute();
        // /$schedule->command('erpleads:run')->everyMinute();

//        $schedule->command('barcode-generator-product:run')->everyFiveMinutes()->between('23:00', '7:00')->withoutOverlapping();
//        $schedule->command('barcode-generator-product:update')->everyFiveMinutes()->withoutOverlapping();


        // HUBSTAFF
        // $schedule->command('hubstaff:refresh_users')->hourly();
        // send hubstaff report
        // Sends hubstaff report to whatsapp
        // $schedule->command('hubstaff:send_report')->hourly()->between('7:00', '23:00');
        // $schedule->command('hubstaff:load_activities')->hourly();
        
        // $schedule->command('hubstaff:account')->dailyAt('20:00')->timezone('Asia/Dubai');
        // $schedule->command('scraplogs:activity')->dailyAt('01:00')->timezone('Asia/Dubai');
        
        // $schedule->command('hubstaff:daily-activity-level-check')->dailyAt('21:00')->timezone('Asia/Dubai');

        //Sync customer from magento to ERP
        //2020-02-17 $schedule->command('sync:erp-magento-customers')->everyFifteenMinutes();

        // Github
        $schedule->command('live-chat:get-tickets')->everyFifteenMinutes();
        $schedule->command('google-analytics:run')->everyFifteenMinutes();
        $schedule->command('newsletter:send')->daily();
        $schedule->command('delete:store-website-category')->daily();
        $schedule->command('memory_usage')->everyMinute();

        //$schedule->command('github:load_branch_state')->hourly();
        // $schedule->command('checkScrapersLog')->dailyAt('8:00');
        // $schedule->command('store:store-brands-from-supplier')->dailyAt('23:45');
        // $schedule->command('MailingListSendMail')->everyFifteenMinutes()->timezone('Asia/Kolkata');

        //Run google priority scraper
        // $schedule->command('run:priority-keyword-search')->daily();
        //2020-02-17 $schedule->command('MailingListSendMail')->everyFifteenMinutes()->timezone('Asia/Kolkata');
        //2020-02-17 Changed below to hourly
        // $schedule->command('cache:master-control')->hourly()->withoutOverlapping();
        // $schedule->command('database:historical-data')->hourly()->withoutOverlapping();

        //update currencies
        // $schedule->command('currencies:refresh')->hourly();
        // $schedule->command('send:event-notification2hr')->hourly();
        // $schedule->command('send:event-notification24hr')->hourly();
        // $schedule->command('currencies:update_name')->monthly();
        // $schedule->command('send-report:failed-jobs')->everyFiveMinutes();
        // $schedule->command('send:event-notification30min')->everyFiveMinutes();
        // $schedule->command('generate:product-pricing-json')->daily();

        // Customer chat messages quick data
        // $schedule->command('customer:chat-message-quick-data')->dailyAt('13:00');;
        // $schedule->command('fetch-store-website:orders')->hourly();

        // If scraper not completed, store alert
        // $schedule->command('scraper:not-completed-alert')->dailyAt('00:00');
		
		$schedule->command('routes:sync')->hourly()->withoutOverlapping();

		//$schedule->command('command:assign_incomplete_products')->dailyAt('01:30');
		$schedule->command('send:daily-reports')->dailyAt('23:00');

		
        //update order way billtrack histories
        $schedule->command('command:waybilltrack')->dailyAt("1:00");
       
		//update directory manager to db
	    //$schedule->command('project_directory:manager')->dailyAt("1:00");


         // make payment receipt for hourly associates on daily basis.
        //  $schedule->command('users:payment')->dailyAt('12:00')->timezone('Asia/Kolkata');
        // $schedule->command('check:landing-page')->everyMinute();

        $schedule->command('ScrapApi:LogCommand')->hourly();
        $schedule->command('HubstuffActivity:Command')->daily();

        $schedule->command('AuthenticateWhatsapp:instance')->hourly();
        // Get tickets from Live Chat inc and put them as unread messages
        // $schedule->command('livechat:tickets')->everyMinute();
        // delate chat message 
         //$schedule->command('delete:chat-messages')->dailyAt('00:00')->timezone('Asia/Kolkata');

        //daily cron for checking due date and add to cashflow 
        $schedule->command("assetsmanagerduedate:pay")->daily();

        //for adding due date in asset manager
        $schedule->command("assetsmanagerpayment:cron Daily")->daily();
        $schedule->command("assetsmanagerpayment:cron Weekly")->weekly();
        $schedule->command("assetsmanagerpayment:cron Yearly")->yearly();
        $schedule->command("assetsmanagerpayment:cron Monthly")->monthly();
        $schedule->command("assetsmanagerpayment:cron Bi-Weekly")->twiceMonthly(1, 16, '13:00');
        
        
        
        //cron for fcm push notifications
        $schedule->command("fcm:send")->everyMinute();
        //cron for influencers start stop
        $schedule->command('influencers:startstop')->hourly();
        //cron for price check api daily basis
        $schedule->command("pricedrop:check")->daily();
		// Cron for scrapper images.
		$schedule->command("scrappersImages")->daily();
        $schedule->command("scrappersImagesDelete")->daily();
        //cron for instagram handler daily basis
        $schedule->command("instagram:handler")->everyMinute()->withoutOverlapping();

        //Cron for activity
        $schedule->command("productActivityStore")->dailyAt("0:00");
        $schedule->command("errorAlertMessage")->daily();

        $schedule->command("UpdateScraperDuration")->everyFifteenMinutes();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
