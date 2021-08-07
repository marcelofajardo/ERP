<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use App\KeywordToCategory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCustomerToCategoryByKeywords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customers-by-keyword-to-category';

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

            $keywordsToCategories = KeywordToCategory::all();

            Customer::where('is_categorized_for_bulk_messages', 0)->with('messageHistory')->chunk(100, function ($customers) use ($keywordsToCategories) {
                foreach ($customers as $customer) {
                    $customerLastThreeMessages = $customer->messageHistory;
                    foreach ($customerLastThreeMessages as $message) {
                        foreach ($keywordsToCategories as $keywordsToCategory) {
                            if (stripos(strtolower($message->message), strtolower($keywordsToCategory->keyword_value)) !== false) {
                                $customer->is_categorized_for_bulk_messages = 1;
                                $customer->save();
                                $this->saveCustomerWithCategory($customer, $keywordsToCategory);
                                break 2;
                            }
                        }
                    }
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }

    private function saveCustomerWithCategory($customer, $keywordToCategory)
    {
        DB::table('customer_with_categories')->where('customer_id', $customer->id)->delete();
        DB::table('customer_with_categories')->insert([
            'customer_id'   => $customer->id,
            'category_type' => $keywordToCategory->category_type,
            'model_id'      => $keywordToCategory->model_id,
        ]);

    }
}
