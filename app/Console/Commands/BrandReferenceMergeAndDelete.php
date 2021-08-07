<?php

namespace App\Console\Commands;

use App\Brand;
use Illuminate\Console\Command;

class BrandReferenceMergeAndDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:merge-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes brands reference and if brand is present it will delete it';

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
        echo "Starting to add from here" . PHP_EOL;

        $count = Brand::count();

        echo "Total brand found :" . $count . PHP_EOL;

        for ($i = 0; $i < $count; $i++) {

            if ($i == 0) {
                $brand = Brand::first();
            } else if ($lastBrand) {
                $brand = Brand::where("id", ">", $lastBrand->id)->whereNull("deleted_at")->first();
            }

            if ($brand) {
                // call the reference
                $reference = explode(',', $brand->references);
                foreach ($reference as $ref) {
                    if (!empty($ref)) {

                        $similarBrands = Brand::where('name', 'LIKE', $ref)->where(function ($q) {
                            $q->where("references", "")->orWhereNull('references');
                        })->where('id', '!=', $brand->id)->get();

                        foreach ($similarBrands as $similarBrand) {
                            $product = \App\Product::where("brand", $similarBrand->id)->get();
                            if (!$product->isEmpty()) {
                                foreach ($product as $p) {
                                    $lastBrandId   = $p->brand;
                                    $p->brand      = $brand->id;
                                    $p->last_brand = $lastBrandId;
                                    $p->save();
                                    \Log::channel('productUpdates')->info("{$brand->id} updated with product" . $p->sku);
                                }
                            }
                            $similarBrand->delete();
                        }
                    }
                }
                $lastBrand = $brand;
            }
        }

        die;

        /*$brands = Brand::all();

    foreach ($brands as $brand) {
    $brandId = $brand->id;
    $reference = $brand->references;
    if(!empty($reference)){
    $brandReferences = explode(',', $reference);
    foreach ($brandReferences as $ref) {
    if(!empty($ref)){
    $similarBrands = Brand::where('name','LIKE','%'.$ref.'%')->where('id', '!=', $brandId)->get();
    foreach ($similarBrands as $similarBrand) {
    $product = \App\Product::where("brand", $similarBrand->id)->get();
    if (!$product->isEmpty()) {
    foreach ($product as $p) {
    $lastBrand     = $p->brand;
    $p->brand      = $brandId;
    $p->last_brand = $lastBrand;
    $p->save();
    \Log::channel('productUpdates')->info("{$brandId} updated with product" . $p->sku);
    }
    }
    $similarBrand->delete();
    }
    }
    }
    }
    }*/
    }
}
