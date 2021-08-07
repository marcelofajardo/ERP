<?php

namespace App\Console\Commands;

use App\Category;
use App\ScrapedProducts;
use App\CronJobReport;
use App\Loggers\LogScraper;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Console\Command;

class CategoryMissingReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:missing-references';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all the missing references for categories submitted by scrapers';

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
            // Create cron job report
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // Set empty
            $arrUnknown = [];

            // Get all categories from log_scraper
            $logScrapers = ScrapedProducts::join("brands as b","b.id","scraped_products.brand_id")
            ->whereNotNull('category')
            ->whereNotIn('website', ['amrstore', 'antonia', 'baseblu', 'brunarosso', 'coltorti', 'doublef', 'giglio', 'griffo210', 'leam', 'les-market', 'lidiashopping', 'nugnes1920', 'savannahs', 'stilmoda', 'vinicio'])
            ->select(["b.name as brand","website","category"])
            ->get(['website', 'brand', 'category']);
            //$logScrapers = LogScraper::whereNotNull('category')->where('url', 'LIKE', '%farfetch%')->get(['category']);

            // Loop over result
            foreach ($logScrapers as $logScraper) {
                // Get product to update
                $arrCategories = @unserialize($logScraper->category);

                // Is it an array?
                if (is_array($arrCategories) && count($arrCategories) > 0) {
                    // Get last category
                    $lastCategory = array_pop($arrCategories);

                    // Remove brand from category name
                    $lastCategory = trim(str_ireplace($logScraper->brand, '', $lastCategory));

                    // Check if the category is in the references
                    $exists = Category::where('title', '=', $lastCategory)->first();

                    // Exists?
                    if ($exists == null) {
                        $exists = Category::where('references', 'LIKE', '%' . $lastCategory . '%')->first();
                    }

                    // Still null
                    if ($exists == null) {
                        // $arrUnknown[] = $logScraper->website . ':' . $lastCategory;
                        $arrUnknown[] = $lastCategory;
                    }
                }
            }

            // Make array unique
            $arrUnknown = array_unique($arrUnknown);
            $arrUnknown = implode(',', $arrUnknown);

            // Update category 143
            $unknownCategory = Category::find(143);

            // Update
            if ($unknownCategory != null && strlen($arrUnknown) > 0) {
                $unknownCategory->references = $unknownCategory->references . ',' . $arrUnknown;
                $unknownCategory->save();
            }

            // Update cron report
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
