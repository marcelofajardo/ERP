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

class GetImagesBySku extends Scraper
{

    private $supportedBrands = [
        'Yves Saint Laurent' => 'https://www.ysl.com/Search/Index?siteCode=SAINTLAURENT_IT&season=A,P,E&department=llmnwmn&gender=D,U,E&emptySearchResult=true&textsearch={QUERY}',
        'valentino' => '',
        'prada' => 'https://store.prada.com/SearchDisplay?searchTerm={QUERY}&q={QUERY}&storeId=32851',
        'fendi' => 'https://www.fendi.com/it/search-results?async=true&q={QUERY}',
        'stella mcartny' => '',
        'kenzo' => '',
        'farfetch' => 'https://www.farfetch.com/it/shopping/tops-1/items.aspx?q={QUERY}'
    ];

    public function scrap($yo)
    {
        $products = Product::where('brand', 18)->get();

        foreach ($products as $product) {
            if ($product->hasMedia(config('constants.media_tags'))) {
                continue;
            }

            $brand = $product->brands;

            if (!$brand) {
                continue;
            }


            $brand = strtolower($brand->name);

            if (array_key_exists($brand, $this->supportedBrands)) {
                $this->getProductDetails($product, $brand);
            }

        }
    }


    /**
     * @param ScrapEntries $scrapEntry
     * @throws \Exception
     */
    private function getProductDetails($product, $brand): void
    {

        if ($product->hasMedia(config('constants.media_tags'))) {
            return;
        }


        $sku = $product->sku;
        if (!$sku) {
            return;
        }

        $sku = str_replace(' ', '', $sku);

        $sku = $this->decorateSku($sku, $product->brand);

        $url = str_replace('{QUERY}', $sku, $this->supportedBrands[$brand]);

        $content = $this->getContent($url);
        if ($content === '') {
            return;
        }

        $c = new HtmlPageCrawler($content);
        $images = [];

        switch (strtolower($brand)) {
            case 'yves saint laurent':
//                $product = $c->filter('article')->getIterator();
                break;
            case 'prada':
            case 'fendi':
                $productUrl = '';
                $productBox = $c->filter('div.products div.product-card-mini a');
                if (count($productBox)) {
                    $productUrl = $productBox->getAttribute('href');
                }

                if (!$productUrl) {
                    break;
                }

                $productUrl = 'https://fendi.com' . $productUrl;
                $productContent = $this->getContent($productUrl);
                if ($productContent === '') {
                    return;
                }

                $c2 = new HtmlPageCrawler($productContent);
                $images = $this->getImagesForFendi($c2);

            foreach ($images as $image_name) {
                $path = public_path('uploads') . '/social-media/' . $image_name;
                $media = MediaUploader::fromSource($path)
                                        ->toDirectory('product/'.floor($product->id / config('constants.image_per_folder')))
                                        ->upload();
                $product->attachMedia($media,config('constants.media_tags'));
            }
            case 'farfetch':
            default:
                break;

        }

    }

    private function getImagesForFendi(HtmlPageCrawler $c): array
    {
        $images = $c->filter('div.inner a img')->getIterator();
        $content = [];

        foreach ($images as $image) {
            if (trim($image->getAttribute('data-zoom-img'))) {
                $content[] = trim($image->getAttribute('data-zoom-img'));
            }
        }

        return $this->downloadImages($content, 'fendi');
    }

    private function getImagesForPrada(HtmlPageCrawler $c) {
        $images = $c->filter('div.thumbnail-list__item img')->getIterator();


        foreach ($images as $image) {
            $content[] = trim($image->getAttribute('src'));
        }

        dd($content);

        return $this->downloadImages($content, 'tory');
    }

    private function getImagesForYSL(HtmlPageCrawler $c) {
        $images = $c->filter('div.thumbnail-list__item img')->getIterator();
        $content = [];

        foreach ($images as $image) {
            $content[] = trim($image->getAttribute('src'));
        }

        return $this->downloadImages($content, 'tory');
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
    private function getImageUrl($url)
    {
        return $url;
    }

    private function decorateSku($sku, $brandId)
    {
        if ($brandId !== 18) {
            return $sku;
        }

        if (strlen($sku) === 19) {
            $skuArray = [];
            $skuArray[0] = substr($sku, 0, 6);
            $skuArray[1] = substr($sku, 10, 4);
            $skuArray[2] = substr($sku, 14, 5);
            $skuArray[3] = substr($sku, 6, 1);
            $skuArray[4] = substr($sku, 7, 3);

            return implode('_', $skuArray);
        }

        if (strlen($sku) === 14) {
            $skuArray = [];
            $skuArray[0] = substr($sku, 0, 6);
            $skuArray[1] = substr($sku, 6, 3);
            $skuArray[2] = substr($sku, 9, 5);

            return implode('_', $skuArray);
        }

        if (strlen($sku) === 20) {
            $skuArray = [];
            $skuArray[0] = substr($sku, 0, 6);
            $skuArray[1] = substr($sku, 11, 4);
            $skuArray[2] = substr($sku, 15, 5);
            $skuArray[3] = substr($sku, 6, 1);
            $skuArray[4] = substr($sku, 7, 4);

            return implode('_', $skuArray);
        }

    }
}
