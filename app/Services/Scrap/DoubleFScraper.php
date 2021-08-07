<?php

namespace App\Services\Scrap;

use App\Brand;
use App\ScrapCounts;
use App\ScrapEntries;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class DoubleFScraper extends Scraper
{
    private const URL = [
        'woman' => 'https://www.thedoublef.com/it_en/woman/designers/',
        'man' => 'https://www.thedoublef.com/it_en/man/designers/'
    ];


    public function scrap(): void
    {
        $brands = Brand::whereNull('deleted_at')->get();
        foreach ($brands as $brand) {
            if (trim($brand->name) == 'DOLCE & GABBANA') {
                $brand->name = 'DOLCE E GABBANA';
            }
            $brand->name = str_replace('&amp;', 'E', $brand->name);
            $brand->name = str_replace('&', 'E', $brand->name);
            $this->scrapPage(self::URL['woman'] . strtolower(str_replace(' ', '-', trim($brand->name))) . '?limit=1000');
            $this->scrapPage(self::URL['man'] . strtolower(str_replace(' ', '-', trim($brand->name))) . '?limit=1000');
        }
    }

    private function scrapPage($url, $hasProduct=true): void
    {
        $scrapEntry = ScrapEntries::where('url', $url)->first();
        if (!$scrapEntry) {
            $scrapEntry = new ScrapEntries();
            $scrapEntry->title = $url;
            $scrapEntry->site_name = 'DoubleF';
            $scrapEntry->url = $url;
            $scrapEntry->save();
        }

        if ($hasProduct) {
            $this->getProducts($scrapEntry);
            return;
        }

        $body = $this->getContent($url);

        $c = new HtmlPageCrawler($body);
        $links = $c->filter('div.designers-list')->filter('ul li a')->getIterator();

        $urls = [];

        foreach ($links as $key=>$link) {
            $text = $link->firstChild->data;
            $text = trim(preg_replace('/\s\s+/', '', $text));
            $text = str_replace(' ', '-', strtolower($text));
            if ($text === '' || $text === 'designers') {
                continue;
            }
            $urls[$text.'_'.$key] = $link->getAttribute('href');
        }

        foreach ($urls as $itemUrl) {
            $this->scrapPage($itemUrl);
        }

    }

    private function getProducts(ScrapEntries $scrapEntriy ): void
    {
        $date = date('Y-m-d');
        $allLinks = ScrapCounts::where('scraped_date', $date)->where('website', 'DoubleF')->first();
        if (!$allLinks) {
            $allLinks = new ScrapCounts();
            $allLinks->scraped_date = $date;
            $allLinks->website = 'DoubleF';
            $allLinks->save();
        }

//        $paginationData = $scrapEntriy->pagination;
//        if (!$paginationData)
//        {
//            $body = $this->getContent($scrapEntriy->url);
//            $c = new HtmlPageCrawler($body);
//            $scrapEntriy->pagination =  $this->getPaginationData($c);
//            $scrapEntriy->save();
//        }
//
//        $pageNumber = $scrapEntriy->pagination['current_page_number'];
//        $totalPageNumber = $scrapEntriy->pagination['total_pages'];
//
//        if ($pageNumber < $totalPageNumber) {
//            $pageNumber++;
//        }

        $body = $this->getContent($scrapEntriy->url, 'GET', 'it', false);

        $c = new HtmlPageCrawler($body);

        $products = $c->filter('.products-grid div.box');
        foreach ($products as $product) {
            $allLinks->link_count = $allLinks->link_count + 1;
            $allLinks->save();
            $title = $this->getTitleFromProduct($product);
            $link = $this->getLinkFromProduct($product);


            if (!$title || !$link) {
                continue;
            }

            $entry = ScrapEntries::where('title', $title)
                ->orWhere('url', $link)
                ->first()
            ;

            if ($entry) {
                continue;
            }

            echo "$link \n";


            $entry = new ScrapEntries();
            $entry->title = $title;
            $entry->url = $link;
            $entry->site_name = 'DoubleF';
            $entry->is_product_page = 1;
            $entry->save();

        }

//        if ($pageNumber >= $totalPageNumber) {
//            $scrapEntriy->pagination = [
//                'current_page_number' => 1,
//                'total_pages' => $totalPageNumber
//            ];
//            $scrapEntriy->is_scraped = 0;
//            $scrapEntriy->save();
//        }

    }

    private function getTitleFromProduct($product) {
        try {
            $description = preg_replace('/\s\s+/', '', $product->getElementsByTagName('h4')->item(0)->textContent);
        } catch (\Exception $exception) {
            $description = '';
        }

        return $description;
    }

    private function getLinkFromProduct($product)
    {
        try {
            $link = $product->getElementsByTagName('a')->item(0)->getAttribute('href');
        } catch (\Exception $exception) {
            $link = '';
        }

        return $link;
    }

    private function getPaginationData( HtmlPageCrawler $c): array
    {
        $maxPageNumber = 1;
        $options = [
            'current_page_number' => 1,
            'total_pages' => $maxPageNumber
        ];

        $text = $c->filter('div.pages ol li.current span')->getInnerHtml();
        $text = preg_replace('/\s\s+/', '', $text);
        if (strlen($text) < 5) {
            return $options;
        }

        $text = explode(' ', $text);
        $maxPageNumber = $text[count($text)-1];

        $options = [
            'current_page_number' => 0,
            'total_pages' => $maxPageNumber
        ];

        return $options;
    }
}