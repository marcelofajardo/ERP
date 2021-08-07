<?php

namespace App\Console\Commands;

use App\Category;
use App\CronJobReport;
use App\Product;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixCategoryNameBySupplier extends Command
{

    const TOP_MAIN_GENDER_CATEGORY = [2, 3];

    public $category = null;
    public $categories;
    public $categoryRefrences = [];
    public $genderCategories;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:fix-by-supplier';

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

            $this->category = Category::where('id', '>', 3)->whereNotNull('references')->where('references', '!=', '')->orderBy('id', 'DESC')->get();

            if (!$this->category->isEmpty()) {
                foreach ($this->category as $i => $crt) {
                    $this->categoryRefrences[$i]['original_name'] = $crt->title;
                    $references                                   = explode(",", $crt->references);
                    if (!empty($references)) {
                        foreach ($references as $reference) {
                            $this->categoryRefrences[$i]['refrence'][] = $reference;
                        }
                    }
                }
            }

            $this->categories = $this->getCategories(self::TOP_MAIN_GENDER_CATEGORY);

            Product::where('is_scraped', 1)->where('category', '<', 4)->orderBy('id', 'DESC')->chunk(1000, function ($products) {
//        Product::where('id', 143121)->orderBy('id', 'DESC')->chunk(1000, function ($products) {
                echo 'Chunk again=======================================================' . "\n";
                $total = 1000;
                foreach ($products as $product) {
                    $this->classify2($product);
                    $total--;
                    echo PHP_EOL;
                    echo $total . " Finished";
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }

    public function getCategories($ids = [])
    {
        $category   = Category::whereIn("id", $ids)->get();
        $categories = [];

        if (!$category->isEmpty()) {
            foreach ($category as $c) {
                $childrenCategories = $c->childs;
                foreach ($childrenCategories as $childrenCategory) {
                    $categories[$c->id][$childrenCategory->id] = $childrenCategory->title;
                    $grandChildren                             = $childrenCategory->childs;
                    foreach ($grandChildren as $grandChild) {
                        $categories[$c->id][$grandChild->id] = $grandChild->title;
                    }
                }
            }
        }

        return $categories;

    }

    private function classify2($product)
    {
        $records    = $this->category;
        $categories = $this->categories;

        // this is optimized code
        $scrapedProducts = $product->many_scraped_products;
        foreach ($scrapedProducts as $scrapedProduct) {

            $catt = $scrapedProduct->properties['category'] ?? [];

            if (is_array($catt)) {
                $catt = implode('', $catt);
            }

            foreach ($this->categoryRefrences as $categoryRefs) {
                $originalCategory = $categoryRefs["original_name"];
                if (!empty($categoryRefs['refrence'])) {
                    foreach ($categoryRefs['refrence'] as $key => $refs) {
                        $cat = strtoupper($refs);

                        if (stripos(strtoupper($catt), $cat) !== false
                            || stripos(strtoupper($scrapedProduct->title ?? ''), $cat) !== false
                            || stripos(strtoupper($scrapedProduct->url ?? ''), $cat) !== false
                        ) {

                            $gender = $this->getMaleOrFemale($scrapedProduct->properties);

                            if ($gender === false) {
                                $gender = $this->getMaleOrFemale($scrapedProduct->title);
                            }

                            if ($gender === false) {
                                $gender = $this->getMaleOrFemale($scrapedProduct->url);
                            }

                            if ($product->supplier === 'Tory Burch' || $originalCategory == 'Pumps') {
                                $gender = 2;
                            }

                            if ($originalCategory == 'Shirts' && $gender == 2) {
                                $originalCategory = 'Tops';
                            }

                            if ($originalCategory == 'Clutches' && $gender == 2) {
                                $originalCategory = 'Handbags';
                            }

                            if ($originalCategory == 'Coats & Jackets' && $gender == 3) {
                                $originalCategory = 'Coats & Jackets & Suits';
                            }

                            if ($originalCategory == 'Tops' && $gender == 3) {
                                $originalCategory = 'T-Shirts';
                            }

                            if ($originalCategory == 'Skirts') {
                                $gender = 2;
                            }

                            if ($originalCategory == 'Shawls And Scarves' && $gender == 3) {
                                $originalCategory = 'Scarves & Wraps';
                            }

                            if ($originalCategory == 'Belts' && (stripos($catt, 'bag') !== false || stripos($product->title, 'bag') !== false || stripos($product->url, 'bag') !== false)) {
                                $originalCategory = 'Belt Bag';
                            }

                            if ($gender === false) {
                                $this->warn('NOOOOO' . $product->supplier);
                                $product->category = 1;
                                $product->save();
                                continue;
                            }

                            $matchedCategories = isset($categories[$gender]) ? $categories[$gender] : [];
                            if (!empty($matchedCategories)) {
                                foreach ($matchedCategories as $mcat => $title) {
                                    if ($title == $originalCategory) {
                                        $product->category = $mcat;
                                        $this->error('SAVED');
                                        $product->save();
                                        return;
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }

        // category loop
        /*foreach ($records as $record) {
    $originalCategory = $record->title;
    $rec              = explode(',', $record->references);

    // find all scraped products
    $scrapedProducts  = $product->many_scraped_products;

    foreach ($scrapedProducts as $scrapedProduct) {

    //scraped product category refrence
    $catt = $scrapedProduct->properties['category'] ?? [];
    if (is_array($catt)) {
    $catt = implode('', $catt);
    }

    foreach ($rec as $kk => $cat) {

    $cat = strtoupper($cat);

    dump($catt, $cat, $scrapedProduct->title, $scrapedProduct->url);
    dump('=================================================');

    if (stripos(strtoupper($catt), $cat) !== false
    || stripos(strtoupper($scrapedProduct->title ?? ''), $cat) !== false
    || stripos(strtoupper($scrapedProduct->url ?? ''), $cat) !== false
    ) {
    $gender = $this->getMaleOrFemale($scrapedProduct->properties);

    if ($gender === false) {
    $gender = $this->getMaleOrFemale($scrapedProduct->title);
    }

    if ($gender === false) {
    $gender = $this->getMaleOrFemale($scrapedProduct->url);
    }

    if ($product->supplier === 'Tory Burch' || $originalCategory == 'Pumps') {
    $gender = 2;
    }

    if ($originalCategory == 'Shirts' && $gender == 2) {
    $originalCategory = 'Tops';
    }

    if ($originalCategory == 'Clutches' && $gender == 2) {
    $originalCategory = 'Handbags';
    }

    if ($originalCategory == 'Coats & Jackets' && $gender == 3) {
    $originalCategory = 'Coats & Jackets & Suits';
    }

    if ($originalCategory == 'Tops' && $gender == 3) {
    $originalCategory = 'T-Shirts';
    }

    if ($originalCategory == 'Skirts') {
    $gender = 2;
    }

    if ($originalCategory == 'Shawls And Scarves' && $gender == 3) {
    $originalCategory = 'Scarves & Wraps';
    }

    if ($originalCategory == 'Belts' && (stripos($catt, 'bag') !== false || stripos($product->title, 'bag') !== false || stripos($product->url, 'bag') !== false)) {
    $originalCategory = 'Belt Bag';
    }

    if ($gender === false) {
    $this->warn('NOOOOO' . $product->supplier);
    $product->category = 1;
    $product->save();
    continue;
    }

    $parentCategory     = Category::find($gender);
    $childrenCategories = $parentCategory->childs;

    foreach ($childrenCategories as $childrenCategory) {
    if ($childrenCategory->title == $originalCategory) {
    $product->category = $childrenCategory->id;
    $this->error('SAVED');
    $product->save();
    return;
    }

    $grandChildren = $childrenCategory->childs;
    foreach ($grandChildren as $grandChild) {
    if ($grandChild->title == $originalCategory) {
    $product->category = $grandChild->id;
    $product->save();
    $this->error('SAVED');
    return;
    }
    }
    }
    }
    }
    }
    }*/
    }

    private function getMaleOrFemale($category)
    {
        if (is_array($category)) {
            $category = json_encode($category);
        }
        if (is_array($category)) {
            foreach ($category as $cat) {
                if (strtoupper($cat) === 'MAN' ||
                    strtoupper($cat) === 'MEN' ||
                    strtoupper($cat) === 'UOMO' ||
                    strtoupper($cat) === 'UOMONI') {
                    return 3;
                }
            }

            foreach ($category as $cat) {
                if (strtoupper($cat) === 'WOMAN' ||
                    strtoupper($cat) === 'WOMEN' ||
                    strtoupper($cat) === 'DONNA' ||
                    strtoupper($cat) === 'LADIES') {
                    return 3;
                }
            }

            return false;
        }

        $category = strtoupper($category);

        if (strpos($category, 'WOMAN') !== false ||
            strpos($category, 'WOMEN') !== false ||
            strpos($category, 'DONNA') !== false ||
            strpos($category, 'LADY') !== false ||
            strpos($category, 'LADIES') !== false ||
            strpos($category, 'GIRL') !== false
        ) {
            return 2;
        }

        if (strpos($category, 'MAN') !== false ||
            strpos($category, 'MEN') !== false ||
            strpos($category, 'UOMO') !== false ||
            strpos($category, 'GENTS') !== false ||
            strpos($category, 'UOMONI') !== false ||
            strpos($category, 'GENTLEMAN') !== false ||
            strpos($category, 'GENTLEMEN') !== false
        ) {
            return 3;
        }

        return false;
    }
}
