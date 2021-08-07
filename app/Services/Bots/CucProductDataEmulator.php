<?php

namespace App\Services\Bots;

use App\Brand;
use App\Console\Commands\Bots\Chrome;
use App\ScrapEntries;
use GuzzleHttp\Client;
use NunoMaduro\LaravelConsoleDusk\Manager;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class CucProductDataEmulator
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
        try {
            $this->manager->browse($command, static function ($browser) use ($url, $self) {
                try {

                    $browser->visit($url)
                        ->pause(200);

                    try {
                        $browser->script('$(document).find("input[type*=email]").val("yogeshmordani@icloud.com");');
                    } catch (\Exception $exception) {

                    }

                    try {
                        $browser->script('$(document).find("input[type*=password]").val("india");');
                    } catch (\Exception $exception) {

                    }

                    $browser->press('Login');

                    $entries = ScrapEntries::where('site_name', 'cuccuini')->get();

                    foreach ($entries as $entry) {
                        $browser->visit($entry->url);
                        $imagesHTML = $browser->element('div.onepcssgrid-1200 div.paddingpage .col7')->getAttribute('innerHTML');
                        $detailsHTML = $browser->element('div.onepcssgrid-1200 div.paddingpage .col5')->getAttribute('innerHTML');


                        $imagesHTML = new HtmlPageCrawler($imagesHTML);
                        $detailsHTML = new HtmlPageCrawler($detailsHTML);


                        $imagesHTMLArray = $imagesHTML->filter('div.dettagli a img');
                        $imagesUrls = [];
                        foreach ($imagesHTMLArray as $image) {
                            $imagesUrls[] = str_replace('thumbs_', '', $image->getAttribute('data-original'));
                        }

                        if ($imagesUrls === []) {
                            $imagesUrls = [str_replace('THUMBS_', '', $imagesHTML->filter('a.iframezoom img')->getAttribute('src'))];
                        }

                        $sku = strip_tags($detailsHTML->filter('article')->getInnerHtml());
                        $sku_original = $sku;
                        $sku = str_replace('Art. (', '', $sku);
                        $sku = str_replace(')', '', $sku);
                        $sku = str_replace('/', '', $sku);


                        $price = $detailsHTML->filter('.prezzidettaglio span')->getInnerHtml();
                        $price = explode(',', $price);
                        $price = str_replace('.', ',', $price[0]).'.'.$price[1];

                        $brand = $detailsHTML->filter('h1 a span')->getInnerHtml();
                        $category = $detailsHTML->filter('h2 a span')->getInnerHtml();

                        $properties = $detailsHTML->filter('div.blacktxt')->getIterator();
                        $values = $detailsHTML->filter('div.col9')->getIterator();

                        $propertiesToSave = [];

                        foreach ($properties as $property) {
                            $key = str_replace(':', '', trim($property->textContent));
                            if ($key == 'ALTRI COLORI') {
                                continue;
                            }
                            $propertiesToSave[] = trim($key);
                        }

                        foreach ($values as $key=>$value) {
                            $propertiesToSave[$propertiesToSave[$key]] = trim($value->textContent);
                            unset($propertiesToSave[$key]);
                        }

                        $sizes = [];

                        $sizesArray = $detailsHTML->filter('.col2 input')->getIterator();

                        foreach ($sizesArray as $item) {
                            $value = $item->getAttribute('value');
                            $sizes[] = trim($value);
                        }




                        $propertiesToSave['sizes'] = implode(',', $sizes);
                        $propertiesToSave['category'] = trim($category);
                        $propertiesToSave['original_sku'] = $sku_original;

                        $title = $category . ' ' . $brand;

                        //Lukas, can you check here, wht this isn't saving?


                        $client = new Client();
                        $response = $client->request('POST', 'https://erp.theluxuryunlimited.com/api/scrap-products/add', [
                            'form_params' => [
                                'sku' => $sku,
                                'website' => 'cuccuini',
                                'has_sku' => $sku ? 1 : 0,
                                'title' => $title,
                                'brand' => $brand,
                                'description' => 'N/A',
                                'images' => $imagesUrls,
                                'price' => $price,
                                'properties' => $propertiesToSave,
                                'url' => $entry->url,
                            ],
                            'headers' => [
                                'Accept' => 'application/json',
                            ]
                        ]);

                        echo $response->getBody()->getContents();
                        echo "\n";

                        if (!$response) {
                            echo $response->getBody()->getContents();
                        }

                    }

                } catch (Exception $exception) {
                    $self->data = false;
                }
            });
        } catch (\Exception $exception) {
            $self->data = false;
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
