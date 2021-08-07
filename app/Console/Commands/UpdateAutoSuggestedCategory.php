<?php

namespace App\Console\Commands;

use App\Category;
use Illuminate\Console\Command;

class UpdateAutoSuggestedCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:auto-suggested-category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Auto suggested category';

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
        $unKnownCategory   = Category::where('title', 'LIKE', '%Unknown Category%')->first();
        $unKnownCategories = explode(',', $unKnownCategory->references);
        $unKnownCategories = array_unique($unKnownCategories);

        $input             = preg_quote('', '~');
        $unKnownCategories = preg_grep('~' . $input . '~', $unKnownCategories);

        //$unKnownCategories = [];
        //$unKnownCategories[] = "women/clothing/trousers/trousers/alexander mcqueen prince of wales trousers";
        //$unKnownCategories[] = "women/clothing/tops/tops/alexander mcqueen flounced top";
        //$unKnownCategories[] = "men/bags/business and travel bags/prada document holder in saffiano";

        if (!empty($unKnownCategories)) {
            foreach ($unKnownCategories as $i => $unkc) {
                $filter = \App\Category::updateCategoryAutoSpace($unkc);
                if ($filter) {
                    $old         = $unKnownCategory->id;
                    $from        = $unkc;
                    $to          = $filter->id;
                    $change      = 'yes';
                    $wholeString = $unkc;
                    if ($change == 'yes') {
                        \App\Jobs\UpdateProductCategoryFromErp::dispatch([
                            "from"    => $from,
                            "to"      => $to,
                            "user_id" => 152,
                        ])->onQueue("supplier_products");
                    }
                    $c = $unKnownCategory;
                    if ($c) {
                        $allrefernce = explode(",", $c->references);
                        $newRef      = [];
                        if (!empty($allrefernce)) {
                            foreach ($allrefernce as $ar) {
                                if ($ar != $wholeString) {
                                    $newRef[] = $ar;
                                }
                            }
                        }
                        $c->references = implode(",", $newRef);
                        $c->save();
                        // new category reference store
                        if ($filter) {

                            $existingRef   = explode(",", $filter->references);
                            $existingRef[] = $from;

                            $userUpdatedAttributeHistory = \App\UserUpdatedAttributeHistory::create([
                                'old_value'      => $filter->references,
                                'new_value'      => implode(",", array_unique($existingRef)),
                                'attribute_name' => 'category',
                                'attribute_id'   => $filter->id,
                                'user_id'        => 152,
                            ]);

                            $filter->references = implode(",", array_unique($existingRef));
                            $filter->save();

                            print($unkc . " updated to " . $filter->title);
                            echo PHP_EOL;
                        }
                    }
                }

            }
        }
    }
}
