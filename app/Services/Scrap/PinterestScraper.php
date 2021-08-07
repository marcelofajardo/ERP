<?php
namespace App\Services\Scrap;

use Wa72\HtmlPageDom\HtmlPageCrawler;

class PinterestScraper extends Scraper
{
    private const GOOGLE_IMAGE_SEARCH_URL = [
        'https://www.google.com/search?tbs=isz:l&tbm=isch&source=lnms&q=site:www.pinterest.com "{query_string}"&&chips=q:site:www.pinterest.com "{query_string}",g_1:{chip_value}',
        'https://www.pinterest.com/search/?q={query_string}&rs=rs&eq='
    ];


    public function scrapPinterestImages($q, $chip_value, $outputCount): array
    {
        $query = str_replace('{query_string}', $q, self::GOOGLE_IMAGE_SEARCH_URL[0]);
        $query = str_replace('{chip_value}', $chip_value, $query);
        $body = $this->getContent($query);

        $c = new HtmlPageCrawler($body);
        // $imageJson = $c->filter('body')->filter('div.rg_meta');
        $imageJson = $c->filter('body')->filter('div.RAyV4b');

        $images = [];

        foreach ($imageJson as $key => $image) {
            // $item = json_decode($image->firstChild->data, true);
            foreach ($image->firstChild->attributes as $att => $image) {
                if( $image->name == 'src' ){
                    $images[] = $image->value ?? null;
                }
            }
            // $images[] = $item['ou'];

            if ($key+1>=$outputCount) {
                break;
            }
        }
        return $images;
    }
}