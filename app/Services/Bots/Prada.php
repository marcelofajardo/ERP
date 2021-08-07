<?php

namespace App\Services\Bots;

use App\Brand;
use App\Console\Commands\Bots\Chrome;
use App\ScrapEntries;
use App\Services\Scrap\Scraper;
use GuzzleHttp\Client;
use NunoMaduro\LaravelConsoleDusk\Manager;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class Prada extends Scraper
{
    private $proxyList;

    private $selectedProxy;

    private $manager;

    public function getSelectedProxy()
    {
        return $this->selectedProxy;
    }

    public function setProxyList(): void
    {
        $this->selectedProxy = [
          'ip' => '123.136.62.162',
          'port' => '8080'
        ];
    }

    private $data;


    public function emulate($command, $url, $commands = null)
    {
        $this->data = ['', ''];
        $self = $this;

        $content = $this->getContent('https://erp.theluxuryunlimited.com/api/add-product-images', 'POST');

        $skus = json_decode($content);

        foreach ($skus as $sku) {
            if ($sku->brand != 'PRADA') {
                continue;
            }

            $skux = $sku->sku;
            if (strlen($skux) !== 18) {
                continue;
            }

            $trimmedSkus = [];

            $first = substr($skux, 0, 6);
            $second = substr($skux, 6, 3);
            $third = substr($skux, 13, 5);
            $forth = substr($skux, 9, 1);
            $fifth = substr($skux, 10, 3);

            $trimmedSkus[] = $first .'_'. $second .'_'. $third . '_' . $forth . '_' . $fifth;

            $first = substr($skux, 0, 6);
            $second = substr($skux, 10, 3);
            $third = substr($skux, 13, 5);
            $forth = substr($skux, 6, 1);
            $fifth = substr($skux, 7, 3);

            $trimmedSkus[] = $first .'_'. $second .'_'. $third . '_' . $forth . '_' . $fifth;

            foreach ($trimmedSkus as $trimmedSku) {
                $defaultUrl = "https://www.prada.com/it/it/search.$trimmedSku.html";
                try {
                    $this->manager->browse($command, static function ($browser) use ($defaultUrl, $self, $sku) {
                        try {
                            $browser->visit($defaultUrl);
                            $html = $browser->element('#application')->getAttribute('innerHTML');
                            $c = new HtmlPageCrawler($html);
                            $link = $c->filter('.slider .component-productBoxSliderSlide a');
                            if (count($link)) {
                                $url = 'https://www.prada.com' . $link->getAttribute('href');

                                $browser->visit($url);
                                $data = $browser->element('#application')->getAttribute('innerHTML');
                                $c = new HtmlPageCrawler($data);
                                $images = $c->filter('.component-productImageGrid img')->getIterator();
                                $mainImage = $c->filter('.product-detail-images img')->getAttribute('src');
                                $imagesTosave = [];
                                foreach ($images as $image) {
                                    $imagesTosave[] = str_replace('580x580', '1280x1280', $image->getAttribute('src'));
                                }
                                $imagesTosave[] = $mainImage;

                                $client = new Client();
                                $response = $client->request('POST', 'https://erp.theluxuryunlimited.com/api/save-product-images', [
                                    'form_params' => [
                                        'id' => $sku->id,
                                        'website' => 'prada',
                                        'images' => $imagesTosave,
                                    ],
                                    'headers' => [
                                        'Accept' => 'application/json',
                                    ]
                                ]);

                                echo $response->getBody()->getContents();
                                echo "\n";
                            }

                        } catch (Exception $exception) {
                            $self->data = false;
                        }
                    });
                } catch (\Exception $exception) {
                    $self->data = false;
                }
            }


        }

        return $this->data;
    }

    public function prepare(): void
    {
        $driver = new Chrome($this->getSelectedProxy());


        $this->manager = new Manager(
            $driver
        );
    }


    public function getProxyList(): \Illuminate\Support\Collection
    {
        return $this->proxyList;
    }
}
