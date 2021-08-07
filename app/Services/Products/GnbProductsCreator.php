<?php

namespace App\Services\Products;

use App\Brand;
use App\Product;
use App\Supplier;
use App\ScrapActivity;
use App\Setting;
use Validator;
use Storage;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class GnbProductsCreator
{
    public function createGnbProducts($image)
    {
       $data['sku'] = str_replace(' ', '', $image->sku);
       $validator = Validator::make($data, [
         'sku' => 'unique:products,sku'
       ]);

       $old_supplier = '';
       if ($validator->fails()) {
         $product = Product::where('sku', $image->sku)->first();
           // $params = [
           //     'website'             => 'Tory',
           //     'scraped_product_id'  => $image->id,
           //     'status'              => 1
           // ];
           //
           // ScrapActivity::create($params);
           $old_supplier = $product->supplier;
       } else {
         $product = new Product;
       }

       $product->sku = str_replace(' ', '', $image->sku);
       $product->brand = $image->brand_id;
       $product->supplier = 'G & B Negozionline';
       $product->name = $image->title;
       $product->short_description = $image->description;
       $product->supplier_link = $image->url;
       $product->stage = 3;
       $product->is_scraped = 1;

       $properties_array = $image->properties;

       if (array_key_exists('Details', $properties_array)) {
         if (strpos($properties_array['Details'], 'Made in') !== false) {
           $product->made_in = str_replace('\n', '', substr($properties_array['Details'], strpos($properties_array['Details'], 'Made in') + 8));

           $product->composition = str_replace('\n', ' ', substr($properties_array['Details'], 0, strpos($properties_array['Details'], 'Made in')));
         } else {
           $product->composition = (string) $properties_array['Details'];
         }
       }

       if (array_key_exists('Color Code', $properties_array)) {
         $product->color = $properties_array['Color Code'];
       }

       if (array_key_exists('Size & Fit', $properties_array)) {
         $sizes = $properties_array['Size & Fit'];
         if (strpos($sizes, 'Width:') !== false) {
           preg_match_all('/Width: ([\d]+)/', $sizes, $match);

           $product->lmeasurement = (int) $match[1][0];
           $product->measurement_size_type = 'measurement';
         }

         if (strpos($sizes, 'Height:') !== false) {
           preg_match_all('/Height: ([\d]+)/', $sizes, $match);

           $product->hmeasurement = (int) $match[1][0];
         }

         if (strpos($sizes, 'Depth:') !== false) {
           preg_match_all('/Depth: ([\d]+)/', $sizes, $match);

           $product->dmeasurement = (int) $match[1][0];
         }
       }

       $brand = Brand::find($image->brand_id);

       if (strpos($image->price, ',') !== false) {
         if (strpos($image->price, '.') !== false) {
           if (strpos($image->price, ',') < strpos($image->price, '.')) {
             $final_price = str_replace(',', '', $image->price);;
           }
         } else {
           $final_price = str_replace(',', '.', $image->price);
         }
       } else {
         $final_price = $image->price;
       }

       $price =  round(preg_replace('/[\&euro;€,]/', '', $final_price));
       $product->price = $price;

       if(!empty($brand->euro_to_inr))
         $product->price_inr = $brand->euro_to_inr * $product->price;
       else
         $product->price_inr = Setting::get('euro_to_inr') * $product->price;

       $product->price_inr = round($product->price_inr, -3);
       $product->price_special = $product->price_inr - ($product->price_inr * $brand->deduction_percentage) / 100;

       $product->price_special = round($product->price_special, -3);

       $product->save();

       // if ($old_supplier != '' && $old_supplier != 'G & B Negozionline') {
         if ($db_supplier = Supplier::where('supplier', 'G & B Negozionline')->first()) {
           $product->suppliers()->syncWithoutDetaching($db_supplier->id);
         }
       // }

       $images = $image->images;

       $product->detachMediaTags(config('constants.media_tags'));

       foreach ($images as $image_name) {
         // Storage::disk('uploads')->delete('/social-media/' . $image_name);

         $path = public_path('uploads') . '/social-media/' . $image_name;
         $media = MediaUploader::fromSource($path)
                                ->toDirectory('product/'.floor($product->id / config('constants.image_per_folder')))
                                ->upload();
         $product->attachMedia($media,config('constants.media_tags'));
       }

    }
}
