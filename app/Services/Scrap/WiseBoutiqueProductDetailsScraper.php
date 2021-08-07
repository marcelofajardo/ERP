<?php

namespace App\Services\Scrap;

use App\Brand;
use App\Category;
use App\ScrapedProducts;
use App\ScrapEntries;
use App\Product;
use App\Setting;
use Storage;
use Validator;
use Wa72\HtmlPageDom\HtmlPageCrawler;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class WiseBoutiqueProductDetailsScraper extends Scraper
{

    public function scrap()
    {
        $products = ScrapEntries::where('is_scraped', 0)->where('is_product_page', 1)->where('site_name', 'Wiseboutique')->take(5000)->get();

        foreach ($products as $product) {
            $this->getProductDetails($product);
        }
    }

    public function doesProductExist($product) {
        $url = $product->url;
        $content = $this->getContent($url);
        if ($content === '') {
            return false;
        }
        


        $c = new HtmlPageCrawler($content);
        $title = $this->getTitle($c);


        if ($title !== '' && strlen($title) > 2) {
            $props = $product->properties;
            $props['sizes_prop'] = $this->getSizes($c);
            $product->properties = $props;
            $product->save();
            return true;
        }

        return false;
    }

    public function deleteProducts()
    {
        $products = ScrapedProducts::where('website', 'Wiseboutique')->get();
        foreach ($products as $product) {
          if ($old_product = Product::where('sku', str_replace(' ', '', $product->sku))->first()) {
            $old_product->delete();
          }

          if ($old_product = Product::where('sku', $product->sku)->first()) {
            $old_product->delete();
          }
        }
    }

    public function createProducts()
    {
      $products = ScrapedProducts::where('has_sku', 1)->where('website', 'Wiseboutique')->get();

      foreach ($products as $product) {
        if ($old_product = Product::where('sku', str_replace(' ', '', $product->sku))->first()) {
          $old_product->sku = str_replace(' ', '', $product->sku);
          $old_product->brand = $product->brand_id;
          $old_product->supplier = 'Wise Boutique';
          $old_product->name = $product->title;
          $old_product->short_description = $product->description;
          $old_product->supplier_link = $product->url;
          $old_product->stage = 3;

          $properties_array = $product->properties ?? [];


          if (array_key_exists('1', $properties_array)) {
            $old_product->composition = (string) $properties_array['1'];
          }

          if (array_key_exists('Colors', $properties_array)) {
            $old_product->color = $properties_array['Colors'];
          }

          foreach ($properties_array as $property) {
            if (!is_array($property)) {
              if (strpos($property, 'Width:') !== false) {
                preg_match_all('/Width: ([\d]+)/', $property, $match);

                $old_product->lmeasurement = (int) $match[1];
                $old_product->measurement_size_type = 'measurement';
              }

              if (strpos($property, 'Height:') !== false) {
                preg_match_all('/Height: ([\d]+)/', $property, $match);

                $old_product->hmeasurement = (int) $match[1];
              }

              if (strpos($property, 'Depth:') !== false) {
                preg_match_all('/Depth: ([\d]+)/', $property, $match);

                $old_product->dmeasurement = (int) $match[1];
              }
            }
          }

          if (array_key_exists('category', $properties_array)) {
            $categories = Category::all();
            $category_id = 1;

            foreach ($properties_array['category'] as $cat) {
              if ($cat == 'WOMAN') {
                $cat = 'WOMEN';
              }

              foreach ($categories as $category) {
                if (strtoupper($category->title) == $cat) {
                  $category_id = $category->id;
                }
              }
            }

            $old_product->category = $category_id;
          }

          $brand = Brand::find($product->brand_id);

          $price = (int) preg_replace('/[\&euro;.]/', '', $product->price);
          $old_product->price = $price;

          if(!empty($brand->euro_to_inr))
            $old_product->price_inr = $brand->euro_to_inr * $old_product->price;
          else
            $old_product->price_inr = Setting::get('euro_to_inr') * $old_product->price;

          $old_product->price_inr = round($old_product->price_inr, -3);
          $old_product->price_special = $old_product->price_inr - ($old_product->price_inr * $brand->deduction_percentage) / 100;

          $old_product->price_special = round($old_product->price_special, -3);

          $old_product->save();

          $old_product->detachMediaTags(config('constants.media_tags'));

          foreach ($product->images as $image_name) {
            $path = public_path('uploads') . '/social-media/' . $image_name;
            $media = MediaUploader::fromSource($path)
                                  ->toDirectory('product/'.floor($old_product->id / config('constants.image_per_folder')))
                                  ->upload();
            $old_product->attachMedia($media,config('constants.media_tags'));
          }
        } else {
          $new_product = new Product;
          $new_product->sku = str_replace(' ', '', $product->sku);
          $new_product->brand = $product->brand_id;
          $new_product->supplier = 'Wise Boutique';
          $new_product->name = $product->title;
          $new_product->short_description = $product->description;
          $new_product->supplier_link = $product->url;
          $new_product->stage = 3;

          $properties_array = $product->properties ?? [];


          if (array_key_exists('1', $properties_array)) {
            $new_product->composition = (string) $properties_array['1'];
          }

          if (array_key_exists('Colors', $properties_array)) {
            $new_product->color = $properties_array['Colors'];
          }

          foreach ($properties_array as $property) {
            if (!is_array($property)) {
              if (strpos($property, 'Width:') !== false) {
                preg_match_all('/Width: ([\d]+)/', $property, $match);

                $new_product->lmeasurement = (int) $match[1];
                $new_product->measurement_size_type = 'measurement';
              }

              if (strpos($property, 'Height:') !== false) {
                preg_match_all('/Height: ([\d]+)/', $property, $match);

                $new_product->hmeasurement = (int) $match[1];
              }

              if (strpos($property, 'Depth:') !== false) {
                preg_match_all('/Depth: ([\d]+)/', $property, $match);

                $new_product->dmeasurement = (int) $match[1];
              }
            }
          }

          if (array_key_exists('category', $properties_array)) {
            $categories = Category::all();
            $category_id = 1;

            foreach ($properties_array['category'] as $cat) {
              if ($cat == 'WOMAN') {
                $cat = 'WOMEN';
              }

              foreach ($categories as $category) {
                if (strtoupper($category->title) == $cat) {
                  $category_id = $category->id;
                }
              }
            }

            $new_product->category = $category_id;
          }

          $brand = Brand::find($product->brand_id);

          $price = (int) preg_replace('/[\&euro;.]/', '', $product->price);
          $new_product->price = $price;

          if(!empty($brand->euro_to_inr))
            $new_product->price_inr = $brand->euro_to_inr * $new_product->price;
          else
            $new_product->price_inr = Setting::get('euro_to_inr') * $new_product->price;

          $new_product->price_inr = round($new_product->price_inr, -3);
          $new_product->price_special = $new_product->price_inr - ($new_product->price_inr * $brand->deduction_percentage) / 100;

          $new_product->price_special = round($new_product->price_special, -3);

          $new_product->save();

          foreach ($product->images as $image_name) {
            $path = public_path('uploads') . '/social-media/' . $image_name;
            $media = MediaUploader::fromSource($path)
                                  ->toDirectory('product/'.floor($old_product->id / config('constants.image_per_folder')))
                                  ->upload();
            $new_product->attachMedia($media,config('constants.media_tags'));
          }
        }
      }
    }

    private function getProductDetails(ScrapEntries $scrapEntry)
    {

        $content = $this->getContent($scrapEntry->url);
        if ($content === '') {
            $scrapEntry->delete();
            return;
        }

        $c = new HtmlPageCrawler($content);
        $title = $this->getTitle($c);
        $brand = $this->getDesignerName($c);
        $price = $this->getPrice($c);
        $sku = $this->getSku($c);
        $images = $this->getImages($c);
        $description = $this->getDescription($c);
        $properties = $this->getProperties($c);

        if (!$images || !$title) {
            $scrapEntry->delete();
            return;
        }

        $brandId = $this->getBrandId($brand);

        if (!$brandId) {
            $scrapEntry->delete();
            return;
        }

        $image = ScrapedProducts::where('sku', $sku)->orWhere('url', $scrapEntry->url)->first();
        if (!$image) {
            $image = new ScrapedProducts();
        }

        $sku = str_replace(' ', '', $sku);
        $sku = str_replace('/', '', $sku);

        $image->brand_id = $brandId;
        $image->sku = $sku;
        $image->website = 'Wiseboutique';
        $image->title = $title;
        $image->description = $description;
        $image->images = $images;
        $image->price = $price;
        if ($sku != 'N/A') {
            $image->has_sku = 1;
        }
        $image->is_price_updated = 1;
        $image->url = $scrapEntry->url;
        $image->properties = $properties;
        $image->save();


        $scrapEntry->is_scraped = 1;
        $scrapEntry->save();

        $data['sku'] = str_replace(' ', '', $image->sku);
        $validator = Validator::make($data, [
          'sku' => 'unique:products,sku'
        ]);

        if ($validator->fails()) {

        } else {
          $product = new Product;
          $product->sku = str_replace(' ', '', $image->sku);
          $product->brand = $image->brand_id;
          $product->supplier = 'Wise Boutique';
          $product->name = $image->title;
          $product->short_description = $image->description;
          $product->supplier_link = $image->url;
          $product->stage = 3;
          $product->is_scraped = 1;

          $properties_array = $image->properties ?? [];


          if (array_key_exists('1', $properties_array)) {
            $product->composition = (string) $properties_array['1'];
          }

          if (array_key_exists('Colors', $properties_array)) {
            $product->color = $properties_array['Colors'];
          }

          foreach ($properties_array as $property) {
            if (!is_array($property)) {
              if (strpos($property, 'Width:') !== false) {
                preg_match_all('/Width: ([\d]+)/', $property, $match);

                $product->lmeasurement = (int) $match[1];
                $product->measurement_size_type = 'measurement';
              }

              if (strpos($property, 'Height:') !== false) {
                preg_match_all('/Height: ([\d]+)/', $property, $match);

                $product->hmeasurement = (int) $match[1];
              }

              if (strpos($property, 'Depth:') !== false) {
                preg_match_all('/Depth: ([\d]+)/', $property, $match);

                $product->dmeasurement = (int) $match[1];
              }
            }
          }

          if (array_key_exists('category', $properties_array)) {
            $categories = Category::all();
            $category_id = 1;

            foreach ($properties_array['category'] as $cat) {
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

          $brand = Brand::find($image->brand_id);

          $price = (int) preg_replace('/[\&euro;.]/', '', $image->price);
          $product->price = $price;

          if(!empty($brand->euro_to_inr))
            $product->price_inr = $brand->euro_to_inr * $product->price;
          else
            $product->price_inr = Setting::get('euro_to_inr') * $product->price;

          $product->price_inr = round($product->price_inr, -3);
          $product->price_special = $product->price_inr - ($product->price_inr * $brand->deduction_percentage) / 100;

          $product->price_special = round($product->price_special, -3);

          $product->save();

          foreach ($images as $image_name) {
            $path = public_path('uploads') . '/social-media/' . $image_name;
            $media = MediaUploader::fromSource($path)
                                  ->toDirectory('product/'.floor($product->id / config('constants.image_per_folder')))
                                  ->upload();
            $product->attachMedia($media,config('constants.media_tags'));
          }
        }
    }

    private function getTitle(HtmlPageCrawler $c) {
        try {
            $title = preg_replace('/\s\s+/', '', $c->filter('div.dettagliinterno h2')->getInnerHtml());
        } catch (\Exception $exception) {
            $title = '';
        }
        return $title;
    }

    private function getPrice(HtmlPageCrawler $c) {
        try {
            $price = preg_replace('/\s\s+/', '', $c->filter('div.prezzidettaglio div span')->getInnerHtml());
        } catch (\Exception $exception) {
            $price = 'N/A';
        }

        return $price;
    }

    private function getSku(HtmlPageCrawler $c) {
        try {
            $sku = preg_replace('/\s\s+/', '', $c->filter('div.dettagliinterno h3 i span')->getInnerHtml());
        } catch (\Exception $exception) {
            $sku = 'N/A';
        }

        $sku = str_replace(' ', '', $sku);

        return $sku;
    }

    private function getDescription(HtmlPageCrawler $c) {
        try {
            $title = preg_replace('/\s\s+/', '', strip_tags($c->filter('div.descrizioniprodotto div')->getInnerHtml()));
        } catch (\Exception $exception) {
            $title = '';
        }

        $title = str_replace('-', '\n', $title);
        return $title;
    }

    private function getImages(HtmlPageCrawler $c) {
        $images = $c->filter('.dettagli a')->getIterator();
        $content = [];

        foreach ($images as $image) {
            $content[] = 'https://www.wiseboutique.com' . trim($image->getAttribute('href'));
        }


        return $this->downloadImages($content, 'wiseboutique');
    }

    private function getSizes(HtmlPageCrawler $c) {
        $sizes = $c->filter('div.taglia')->getIterator();
        $content = [];

        foreach ($sizes as $size) {
            $content[] = trim($size->textContent);
        }

        return $content;

    }

    private function getDesignerName(HtmlPageCrawler $c)
    {
        try {
            $title = preg_replace('/\s\s+/', '', $c->filter('h1.notranslate a span')->getInnerHtml());
        } catch (\Exception $exception) {
            $title = '';
        }
        return $title;
    }

    private function getBrandId($brandName)
    {
        $brand = Brand::where('name', $brandName)->first();

        if (!$brand) {
            return false;
        }


        return $brand->id;
    }

    private function downloadImages($data, $prefix = 'img'): array
    {
        $images = [];
        foreach ($data as $key=>$datum) {
            try {
                $datum = $this->getImageUrl($datum);
                $imgData = file_get_contents($datum);
            } catch (\Exception $exception) {
                continue;
            }

            $fileName = $prefix . '_' . md5(time()).'.png';
            Storage::disk('uploads')->put('social-media/'.$fileName, $imgData);

            $images[] = $fileName;
        }

        return $images;
    }

    private function getProperties(HtmlPageCrawler $c) {
        $sizes = $this->getSizes($c);
        $propertiesValues =  $c->filter('div.dettagliinterno div.clear .col9')->getIterator();
        $propertiesData = ['size' => $sizes];

        foreach ($propertiesValues as $key=>$property) {
            $value = preg_replace('/\s\s+/', '\n', $property->textContent);
            $propertiesData[] = $value;
        }

        $bread = $c->filter('ol.breadcrumb li')->filter('a span')->getIterator();

        $categoryTypes = [];

        foreach ($bread as $item) {
            if (trim($item->textContent) != 'HOME') {
                $categoryTypes[] = trim($item->textContent);
            }
        }

        if (in_array('DONNA', $categoryTypes, false) || in_array('WOMAN', $categoryTypes, false)) {
            $propertiesData['gender'] = 'Female';
        } else {
            $propertiesData['gender'] = 'Male';
        }

        $propertiesData['category'] = $categoryTypes;

        return $propertiesData;
    }

    private function getImageUrl($url)
    {
        $content = $this->getContent($url);
        if ($content === '') {
            return '';
        }

        $c = new HtmlPageCrawler($content);

        $imageUrl = $c->filter('img')->attr('src');
        $imageUrl = str_replace(' ', '%20', $imageUrl);
        return $imageUrl;
    }
}
