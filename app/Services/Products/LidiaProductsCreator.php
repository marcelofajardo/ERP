<?php

namespace App\Services\Products;

use App\Brand;
use App\Product;
use App\Setting;
use App\Category;
use App\Supplier;
use Validator;
use Storage;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class LidiaProductsCreator
{
    public function createProduct($image)
    {
       $data['sku'] = str_replace(' ', '', $image->sku);
       $validator = Validator::make($data, [
         'sku' => 'unique:products,sku'
       ]);

       if ($validator->fails()) {
         $product = Product::where('sku', $image->sku)->first();
       } else {
         $product = new Product;
       }

      switch ($image->website) {
        case 'lidiashopping':
          $supplier = 'Lidia';
          break;
        case 'cuccuini':
          $supplier = 'Cuccini';
          break;
        default:
          $supplier = '';
      }

       $product->sku = str_replace(' ', '', $image->sku);
       $product->brand = $image->brand_id;
       $product->supplier = $supplier;
       $product->name = $image->title;
       $product->short_description = $image->description;
       $product->supplier_link = $image->url;
       $product->stage = 3;
       $product->is_scraped = 1;

       $properties_array = $image->properties;

       // if (array_key_exists('Details', $properties_array)) {
       //   if (strpos($properties_array['Details'], 'Made in') !== false) {
       //     $product->made_in = str_replace('\n', '', substr($properties_array['Details'], strpos($properties_array['Details'], 'Made in') + 8));
       //
       //     $product->composition = str_replace('\n', ' ', substr($properties_array['Details'], 0, strpos($properties_array['Details'], 'Made in')));
       //   } else {
       //     $product->composition = (string) $properties_array['Details'];
       //   }
       // }
       //
       // if (array_key_exists('Color Code', $properties_array)) {
       //   $product->color = $properties_array['Color Code'];
       // }

       if (array_key_exists('sizes', $properties_array)) {
         $sizes = $properties_array['sizes'];
         // if (array_key_exists('Length', $sizes)) {
         //   preg_match_all('/\s((.*))\s/', $sizes['Length'], $match);
         //
         //   $product->lmeasurement = (int) $match[1][0];
         //   $product->measurement_size_type = 'measurement';
         // }

         $imploded_sizes = implode(',', $sizes);

         $product->size = $imploded_sizes;

         // if (strpos($sizes, 'Width:') !== false) {
         //   preg_match_all('/Width: ([\d]+)/', $sizes, $match);
         //
         //   $product->lmeasurement = (int) $match[1][0];
         //   $product->measurement_size_type = 'measurement';
         // }
         //
         // if (strpos($sizes, 'Height:') !== false) {
         //   preg_match_all('/Height: ([\d]+)/', $sizes, $match);
         //
         //   $product->hmeasurement = (int) $match[1][0];
         // }
         //
         // if (strpos($sizes, 'Depth:') !== false) {
         //   preg_match_all('/Depth: ([\d]+)/', $sizes, $match);
         //
         //   $product->dmeasurement = (int) $match[1][0];
         // }
       }

       if (array_key_exists('color', $properties_array)) {
         $product->color = $properties_array['color'];
       }

       if (array_key_exists('COLORI', $properties_array)) {
         $product->color = $properties_array['COLORI'];
       }

       if (array_key_exists('COMPOSIZIONE', $properties_array)) {
         $product->composition = $properties_array['COMPOSIZIONE'];
       }

       if (array_key_exists('Category', $properties_array)) {
         $categories = Category::all();
         $category_id = 1;

         foreach ($properties_array['Category'] as $cat) {
           if ($cat == 'WOMAN') {
             $cat = 'WOMEN';
           }

           foreach ($categories as $category) {
             if (strtoupper($category->title) == $cat) {
               $category_id = $category->id;
             }
           }
         }

         $product->category = $category_id;
       }

       if (array_key_exists('material_used', $properties_array)) {
         $product->composition = $properties_array['material_used'];
       }

       $brand = Brand::find($image->brand_id);

       if (strpos($image->price, ',') !== false) {
         if (strpos($image->price, '.') !== false) {
           if (strpos($image->price, ',') < strpos($image->price, '.')) {
             $final_price = str_replace(',', '', $image->price);;
           } else {
             $final_price = $image->price;
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

       if ($db_supplier = Supplier::where('supplier', $supplier)->first()) {
         $product->suppliers()->syncWithoutDetaching($db_supplier->id);
       }

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
