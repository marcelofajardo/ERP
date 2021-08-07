<?php

namespace App\Console\Commands;

use App\Brand;
use Illuminate\Console\Command;

class BrandMergeWithProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Its combines brand with refernce by calculating the similar text and update the product';

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
        $brands = Brand::all()->pluck('name', 'id')->toArray();

        foreach ($brands as $brandId => $brandKeyword) {

            $input = $brandKeyword;

            $similarWord = [];

            $unlinkId = [];
            echo $brandKeyword . " Started \n";
            foreach ($brands as $keyId => $word) {
                if ($brandId == $keyId) {
                    continue;
                }

                $originalWord = $word;

                //remove space
                $input = preg_replace('/\s+/', '', $input);

                $word = preg_replace('/\s+/', '', $word);

                //remove all special character
                $input = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $input);

                $word = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $word);

                similar_text(strtolower($input), strtolower($word), $percent);

                if ($percent >= 60) {

                    $reference = $originalWord;
                    $brandId   = $brandId;
                    //getting references
                    $ref = Brand::find($brandId);

                    //check if reference exist
                    if ($ref->references) {
                        if (in_array($reference, explode(',', $ref->references))) {
                            unset($brands[$keyId]);
                            continue;
                        }
                        $reference = $ref->references . ',' . $reference;
                    } else {
                        $reference = ',' . $reference;
                    }

                    if (!empty($brandId)) {
                        \Log::channel('productUpdates')->info("{$brandId} updated with " . $reference);

                        $success = Brand::where("id", $brandId)->update(['references' => $reference]);

                        $product = \App\Product::where("brand", $keyId)->get();
                        if (!$product->isEmpty()) {
                            foreach ($product as $p) {
                                $lastBrand     = $p->brand;
                                $p->brand      = $brandId;
                                $p->last_brand = $lastBrand;
                                $p->save();
                                \Log::channel('productUpdates')->info("{$brandId} updated with product" . $p->sku);
                            }
                        }
                    }
                    unset($brands[$keyId]);
                }
            }
        }
    }
}
