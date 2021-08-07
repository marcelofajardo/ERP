<?php

namespace App\Services\Scrap;

use App\Brand;
use App\ScrapedProducts;
use App\ScrapEntries;
use App\Product;
use App\Setting;
use Storage;
use Validator;
use Wa72\HtmlPageDom\HtmlPageCrawler;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class DoubleFProductDetailsScraper extends Scraper
{

    public function scrap()
    {
        $products = ScrapEntries::where('is_product_page', 1)->where('site_name', 'DoubleF')->take(5000)->get();

        foreach ($products as $product) {
            $this->getProductDetails($product);
        }
    }

    private function getSizes(HtmlPageCrawler $c) {
//        $sizes = $c->filter('script')->getIterator();
        $content = [];

//        foreach ($sizes as $size) {
//            $html = trim($size->textContent);
//            if (strpos($html, 'new Product.Config') !== false) {
//                $html = explode('var unsaleableProducts', $html);
//                $htmlData = trim($html[0]);
//                $htmlData = str_replace('var spConfig = new Product.Config(', '', $htmlData);
//                $htmlData = str_replace(');', '', $htmlData);
//
//                $data = json_decode($htmlData, true);
//
//                foreach ($data as $datum) {
//                    foreach ($datum as $item) {
//                        if ($item['label'] == 'Size') {
//                            $options = $item['options'];
//                            $options = array_map(function($item) {
//                                return $item['label'];
//                            }, $options);
//
//                            return $options;
//                        }
//                    }
//                }
//            }
//        }

        return $content;

    }

    public function doesProductExist($product) {
        $url = $product->url;
        $content = $this->getContent($url, 'GET', 'it', false);
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

    private function getProductDetails(ScrapEntries $scrapEntry)
    {

        $content = $this->getContent($scrapEntry->url, 'GET', 'it', false);
        if ($content === '') {
            $scrapEntry->delete();
            return;
        }

        echo "$scrapEntry->url \n";


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



        $image->brand_id = $brandId;
        $image->sku = $sku;
        $image->website = 'DoubleF';
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

        $properties = $image->properties;
        if (!isset($properties['Color code'])) {
            return;
        }
        $colorCode = explode('-', $properties['Color code']);
        if (count($colorCode) !== 2) {
            return;
        }

        $colorCode = $colorCode[1];
        $sku2 = $image->sku.$colorCode;
        $image->sku = $sku2;

        $image->save();


        $scrapEntry->is_scraped = 1;
        $scrapEntry->save();

//        app('App\Services\Products\DoubleProductsCreator')->createDoubleProducts($image);
    }

    private function getTitle(HtmlPageCrawler $c) {
        try {
            $title = preg_replace('/\s\s+/', '', $c->filter('h1 div.name')->getInnerHtml());
        } catch (\Exception $exception) {
            $title = '';
        }
        return $title;
    }

    private function getPrice(HtmlPageCrawler $c) {
        try {
            $price = preg_replace('/\s\s+/', '', $c->filter('div.price-box span.price')->getInnerHtml());
        } catch (\Exception $exception) {
            $price = 'N/A';
        }

        $price = str_replace('&nbsp;', '', $price);
        $price = str_replace('&euro;', '€', $price);

        return $price;
    }

    private function getSku(HtmlPageCrawler $c) {
        try {
            $properties = $c->filter('div#tab1 ul li')->getIterator();
            $sku = '';
            foreach ($properties as $property) {
                if (strpos($property->textContent, 'Product code') !== false) {
                    $sku = $property->textContent;
                    $sku = explode(':', $sku);
                    $sku = $sku[1];
                    $sku = explode('/', $sku);
                    $sku = $sku[0];
                    $sku = str_replace(' ', '', preg_replace('/\s\s+/', '', $sku));
                }
            }

        } catch (\Exception $exception) {
            $sku = 'N/A';
        }

        return $sku;
    }

    private function getDescription(HtmlPageCrawler $c) {
        try {
            $title = preg_replace('/\s\s+/', '', strip_tags($c->filter('div#tab1 p')->getInnerHtml()));
        } catch (\Exception $exception) {
            $title = '';
        }

        $title = str_replace('-', '\n', $title);
        return $title;
    }

    private function getImages(HtmlPageCrawler $c) {
        $images = $c->filter('div.product-img-box a')->getIterator();
        $content = [];

        foreach ($images as $image) {
            $content[] = trim($image->getAttribute('href'));
        }

        return $this->downloadImages($content, 'doublef');
    }

    private function getDesignerName(HtmlPageCrawler $c)
    {
        try {
            $title = preg_replace('/\s\s+/', '', $c->filter('div.product-name h2 strong a')->getInnerHtml());
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
        $bread = $c->filter('ul.breadcrumbs li a')->getIterator();
        $propertiesData = ['size' => $sizes];


        $categoryTypes = [];

        foreach ($bread as $item) {
            if (trim($item->textContent) != 'Home') {
                $categoryTypes[] = trim($item->textContent);
            }
        }

        if (in_array('Donna', $categoryTypes, false) || in_array('Woman', $categoryTypes, false)) {
            $propertiesData['gender'] = 'Female';
        } else {
            $propertiesData['gender'] = 'Male';
        }

        $propertiesData['category'] = $categoryTypes;

        $allProperties = $c->filter('div#accordion li')->getIterator();

        foreach ($allProperties as $property) {
            $item = strip_tags($property->textContent);
            if (strpos($item, 'Made') !== false) {
                $propertiesData['Made In'] = str_replace('Made in ', '', $item);
                continue;
            }

            $item = explode(':', $item, 2);
            if (count($item) !== 2 || strpos($item[0], 'Product code') !== false) {
                continue;
            }

            $propertiesData[$item[0]] = trim($item[1]);

        }

        return $propertiesData;
    }

    private function getImageUrl($url)
    {
        return $url;
    }
}
