<?php

namespace App\Services\Scrap;

use App\Brand;
use App\ScrapCounts;
use App\ScrapEntries;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class WiseBoutiqueScraper extends Scraper
{
    private $scrapKey  = '';
    private const URL = [
        'man' => 'https://www.wiseboutique.com/en/man',
        'woman' => 'https://www.wiseboutique.com/en/woman',
        'HOMEPAGE' => 'https://www.wiseboutique.com/en',
    ];


    public function scrap(): void
    {
        $brands = Brand::whereNull('deleted_at')->get();
        foreach ($brands as $brand) {
            if ($brand->name == 'TODS') {
                $brand->name = 'tod%27s';
            }
            $brand->name = str_replace(' &amp; ', '&', $brand->name);
            $brand->name = str_replace('&amp;', '&', $brand->name);
            $this->scrapPage(self::URL['woman'] . '-' . strtolower(str_replace(' ', '+', trim($brand->name))) . '?n=120');
            $this->scrapPage(self::URL['man'] . '-' . strtolower(str_replace(' ', '+', trim($brand->name))) . '?n=120');
        }
    }

    private function scrapPage($url, $hasProduct=true): void
    {
        $scrapEntry = ScrapEntries::where('url', $url)->first();
        if (!$scrapEntry) {
            $scrapEntry = new ScrapEntries();
            $scrapEntry->title = $url;
            $scrapEntry->url = $url;
            $scrapEntry->site_name = 'Wiseboutique';
            $scrapEntry->save();
        }

        if ($hasProduct) {
            $this->getProducts($scrapEntry);
            return;
        }
    }

    private function getProducts(ScrapEntries $scrapEntry ): void
    {

        $date = date('Y-m-d');
        $allLinks = ScrapCounts::where('scraped_date', $date)->where('website', 'Wiseboutique')->first();
        if (!$allLinks) {
            $allLinks = new ScrapCounts();
            $allLinks->scraped_date = $date;
            $allLinks->website = 'Wiseboutique';
            $allLinks->save();
        }

        $body = $this->getContent($scrapEntry->url);
        $c = new HtmlPageCrawler($body);

        $products = $c->filter('.contfoto .cotienifoto a:first-child')->getIterator();


        foreach ($products as $product) {
            $allLinks->link_count = $allLinks->link_count + 1;
            $allLinks->save();
            $title = $product->getAttribute('title') ?? 'N/A';
            $link = self::URL['HOMEPAGE'] . '/' . $product->getAttribute('href');

            if (!$link) {
                continue;
            }

            $entry = ScrapEntries::where('url', $link)
                ->first()
            ;


            if (!$entry) {
                $entry = new ScrapEntries();
            }

            $entry->title = $title;
            $entry->url = $link;
            $entry->is_product_page = 1;
            $entry->site_name = 'Wiseboutique';
            $entry->save();
        }
    }
}
