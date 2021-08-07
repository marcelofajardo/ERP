<?php

namespace App\Console\Commands;

use App\Category;
use App\CronJobReport;
use App\Product;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateWiseCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:wise-category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $scraper;

    /**
     * Create a new command instance.
     *
     * @param GebnegozionlineProductDetailsScraper $scraper
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

            // $products = ScrapedProducts::where('has_sku', 1)->where('website', 'Wiseboutique')->get();
            // $products = ScrapedProducts::where('created_at', '>', '2019-06-03 00:00')->get();
            $products = ScrapedProducts::all();
            // $products = ScrapedProducts::where('sku', 'SIDNEYMAMOBLACK')->get();

            $women_count          = 0;
            $women_second_count   = 0;
            $women_third_count    = 0;
            $all_categories_count = 0;
            $no_category_count    = 0;
            $no_match_count       = 0;

            foreach ($products as $count => $product) {
                if ($old_product = Product::where('sku', $product->sku)->first()) {
                    $properties_array = $product->properties ?? [];

                    if (array_key_exists('category', $properties_array)) {
                        $categories  = Category::all();
                        $category_id = 1;

                        if (is_array($properties_array['category'])) {
                            foreach ($properties_array['category'] as $key => $cat) {
                                $up_cat = strtoupper($cat);

                                if ($up_cat == 'WOMAN') {
                                    $up_cat = 'WOMEN';
                                }

                                if ($key == 0 && $up_cat == 'WOMEN') {
                                    dump("$count - Woman Category");
                                    $women_count++;
                                    $women_children = Category::where('title', 'WOMEN')->first()->childs;
                                    // dd($women_children);
                                }

                                if (isset($women_children)) {
                                    foreach ($women_children as $children) {
                                        if (strtoupper($children->title) == $up_cat) {
                                            dump("$count - Woman Category 2 level");
                                            $women_second_count++;
                                            $category_id = $children->id;
                                        }

                                        foreach ($children->childs as $child) {
                                            if (strtoupper($child->title) == $up_cat) {
                                                dump("$count - Woman Category 3 Level");
                                                $women_third_count++;
                                                $category_id = $child->id;
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($categories as $category) {
                                        if (strtoupper($category->title) == $up_cat) {
                                            dump("$count - All Categories found item");
                                            $all_categories_count++;
                                            $category_id = $category->id;
                                        }
                                    }
                                }

                            }

                            unset($women_children);

                            $old_product->category = $category_id;
                            $old_product->save();
                        }

                    } else {
                        dump("NO CATEGORY - $product->sku");
                        $no_category_count++;
                    }
                } else {
                    dump("DIDNT FIND MATCH - $product->sku");
                    $no_match_count++;
                }
            }

            dump("Women Category - $women_count");
            dump("Women 2 Level - $women_second_count");
            dump("Women 3 Level - $women_third_count");
            dump("Found in All categories - $all_categories_count");
            dump("No Category - $no_category_count");
            dump("No Match - $no_match_count");

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
