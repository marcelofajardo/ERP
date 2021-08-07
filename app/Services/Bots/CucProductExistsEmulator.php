<?php

namespace App\Services\Bots;

use App\Brand;
use App\Console\Commands\Bots\Chrome;
use App\ScrapEntries;
use GuzzleHttp\Client;
use NunoMaduro\LaravelConsoleDusk\Manager;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class CucProductExistsEmulator
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


    public function emulate($command, $url, $commands = null, $product)
    {
        $this->data = false;
        $self = $this;
        try {
            $this->manager->browse($command, static function ($browser) use ($url, $self, $product) {
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


                    $browser->visit($product->url);
                    $detailsHTML = $browser->element('div.onepcssgrid-1200 div.paddingpage .col5')->getAttribute('innerHTML');

                    $detailsHTML = new HtmlPageCrawler($detailsHTML);

                    $sku = strip_tags($detailsHTML->filter('article')->getInnerHtml());
                    $sku = str_replace('Art. (', '', $sku);
                    $sku = str_replace(')', '', $sku);
                    $sku = str_replace('/', '', $sku);


                    $price = $detailsHTML->filter('.prezzidettaglio span')->getInnerHtml();
                    $price = explode(',', $price);
                    $price = str_replace('.', ',', $price[0]).'.'.$price[1];

                    $brand = $detailsHTML->filter('h1 a span')->getInnerHtml();
                    $category = $detailsHTML->filter('h2 a span')->getInnerHtml();

                    if ($sku || $price || $brand || $category) {
                        $self->data = true;
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
