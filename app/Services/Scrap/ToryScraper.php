<?php

namespace App\Services\Scrap;

use App\ScrapCounts;
use App\ScrapEntries;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class ToryScraper extends Scraper
{
    private const URL = [
        'clothing' => 'https://www.toryburch.it/abbigliamento/visualizza-tutto/?sz=8000&start=1',
        'shoes' => 'https://www.toryburch.it/scarpe/visualizza-tutto/?sz=8000&start=1',
        'bags' => 'https://www.toryburch.it/borse/visualizza-tutto/?sz=8000&start=1',
        'fragrance' => 'https://www.toryburch.it/fragranze/visualizza-tutto/',
        'bags2' => 'https://www.toryburch.it/accessori/portafogli/?sz=1200&start=1',
        'wallets' => 'https://www.toryburch.it/accessori/portafogli/?sz=1200&start=1&format=ajax&instart_disable_injection=true',
        'card holder' => 'https://www.toryburch.it/porta-tessere-e-porta-monete/?sz=1200&start=1',
        'wrist wallet' => 'https://www.toryburch.it/accessori/portafogli-da-polso-e-trousse/?sz=1200&start=1',
        'cosmetic cases' => 'https://www.toryburch.it/accessori/cosmetic-cases/?sz=1200&start=1',
        'sunglasses' => 'https://www.toryburch.it/accessori/occhiali/?sz=1200&start=1',
        'belts' => 'https://www.toryburch.it/accessori/cinture/?sz=1200&start=1',
        'hitech' => 'https://www.toryburch.it/accessori/hi-tech/?sz=1200&start=1',
        'earings' => 'https://www.toryburch.it/accessori/orecchini/?sz=1200&start=1',
        'jewellery' => 'https://www.toryburch.it/accessori/gioielli/?sz=1200&start=1',
        'bracelets' => 'https://www.toryburch.it/accessori/bracciali/?sz=1200&start=1',
        'watches' => 'https://www.toryburch.it/accessori/orologi/?sz=1200&start=1',
        'necklaces' => 'https://www.toryburch.it/accessori/collane/?sz=1200&start=1',
        'rings' => 'https://www.toryburch.it/accessori/anelli/?sz=1200&start=1',
    ];


    public function scrap(): void
    {
        foreach (self::URL as $url) {
            $this->scrapPage($url);
        }
    }

    private function scrapPage($url, $hasProduct=true): void
    {
        $scrapEntry = ScrapEntries::where('url', $url)->first();
        if (!$scrapEntry) {
            $scrapEntry = new ScrapEntries();
            $scrapEntry->title = $url;
            $scrapEntry->site_name = 'Tory';
            $scrapEntry->url = $url;
            $scrapEntry->save();
        }

        if ($hasProduct) {
            $this->getProducts($scrapEntry);
        }

    }

    private function getProducts(ScrapEntries $scrapEntriy ): void
    {

        $date = date('Y-m-d');
        $allLinks = ScrapCounts::where('scraped_date', $date)->where('website', 'Tory')->first();
        if (!$allLinks) {
            $allLinks = new ScrapCounts();
            $allLinks->scraped_date = $date;
            $allLinks->website = 'Tory';
            $allLinks->save();
        }
        $body = $this->getContent($scrapEntriy->url);
        $c = new HtmlPageCrawler($body);

        $products = $c->filter('a.product-tile__name');
        foreach ($products as $product) {
            $allLinks->link_count = $allLinks->link_count + 1;
            $allLinks->save();
            $title = $this->getTitleFromProduct($product);
            $link = $this->getLinkFromProduct($product);

            echo $link;

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

            $entry = new ScrapEntries();
            $entry->title = $title;
            $entry->url = $link;
            $entry->site_name = 'Tory';
            $entry->is_product_page = 1;
            $entry->save();

        }

    }

    private function getTitleFromProduct($product) {
        try {
            $description = preg_replace('/\s\s+/', '', $product->getAttribute('title'));
        } catch (\Exception $exception) {
            $description = '';
        }

        return $description;
    }

    private function getLinkFromProduct($product)
    {
        try {
            $link = preg_replace('/\s\s+/', '', $product->getAttribute('href'));
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
            'current_page_number' => 1,
            'total_pages' => $maxPageNumber
        ];

        return $options;
    }
}