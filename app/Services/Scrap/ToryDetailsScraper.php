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

class ToryDetailsScraper extends Scraper
{

    public function scrap()
    {
        $products = ScrapEntries::where('is_scraped', 0)->where('is_product_page', 1)->where('site_name', 'Tory')->take(2500)->get();


        foreach ($products as $product) {
            $this->getProductDetails($product);
        }
    }

    public function doesProductExist($url) {
        $content = $this->getContent($url);
        if ($content === '') {
            return false;
        }


        $c = new HtmlPageCrawler($content);
        $title = $this->getTitle($c);

        if ($title !== '' && strlen($title) > 2) {
            return true;
        }

        return false;
    }

    /**
     * @param ScrapEntries $scrapEntry
     * @throws \Exception
     */
    private function getProductDetails(ScrapEntries $scrapEntry): void
    {
        $content = $this->getContent($scrapEntry->url);
        if ($content === '') {
            $scrapEntry->delete();
            return;
        }

        $c = new HtmlPageCrawler($content);
        $title = $this->getTitle($c);
        $brand = 'TORY BURCH';
        $price = $this->getPrice($c);
        $description = $this->getDescription($c);
        $properties = $this->getProperties($c);
        $sku = $this->getSku($c);
        $images = $this->getImages($c);

        if (!$images || !$title) {
            $scrapEntry->delete();
            return;
        }

        $brandId = $this->getBrandId($brand);

        $color = $properties['color'] ?? '';
        $color = str_replace(' ', '', $color);
        $color = str_replace('/', '', $color);
        $color = str_replace('\\', '', $color);

        $sku .= $color;



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
        $image->website = 'Tory';
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

       app('App\Services\Products\ToryProductsCreator')->createProduct($image);
    }

    private function getTitle(HtmlPageCrawler $c) {
        try {
            $title = preg_replace('/\s\s+/', '', $c->filter('h1.title')->getInnerHtml());
            $title = str_replace("\n", '', $title);
        } catch (\Exception $exception) {
            $title = '';
        }
        return $title;
    }

    private function getPrice(HtmlPageCrawler $c) {
        try {
            $price = preg_replace('/\s\s+/', '', $c->filter('div.price span')->getAttribute('data-number-price'));
        } catch (\Exception $exception) {
            $price = 'N/A';
        }

        return $price;
    }

    private function getSku(HtmlPageCrawler $c) {
        try {
            $properties = $c->filter('div.styleNumber span')->getInnerHtml();
            $sku = str_replace('Codice Prodotto', '', $properties);

        } catch (\Exception $exception) {
            $sku = 'N/A';
        }

        return trim($sku);
    }

    private function getDescription(HtmlPageCrawler $c) {
        try {
            $description = preg_replace('/\s\s+/', '', strip_tags($c->filter('div.product-description__content p')->getInnerHtml()));
        } catch (\Exception $exception) {
            $description = '';
        }


        $description = str_replace('\n', '', $description);
        return $description;
    }

    private function getImages(HtmlPageCrawler $c) {
        $images = $c->filter('div.thumbnail-list__item img')->getIterator();
        $content = [];

        foreach ($images as $image) {
            $content[] = trim($image->getAttribute('src'));
        }

        return $this->downloadImages($content, 'tory');
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
        $properties = [];
        $propertiesRaw = $c->filter('div#longDescription ul li')->getIterator();
        $colorData = $c->filter('div#pdpATCDivsubProductDiv div.variation-attributes div.swatches div.swatches__disp-name')->getInnerHtml();

        if ($colorData) {
            $properties['color'] = trim($colorData);
        }

        foreach ($propertiesRaw as $p)
        {
            $pStr = str_replace('\n', '', $p->textContent);
            $p = explode(';', $pStr);
            if (count($p) === 1) {
                $properties[] = $pStr;
                continue;
            }

            foreach ($p as $key=>$px) {
                $pp = explode(':', $px);
                if (count($pp) === 1) {
                    $properties[] = $px;
                    continue;
                }

                $properties['sizes'][trim($pp[0])] = trim($pp[1]);
            }

        }

        return $properties;
    }

    private function getImageUrl($url)
    {
        return $url;
    }
}
