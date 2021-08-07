<?php

namespace App\Services\Bots;

use App\Console\Commands\Bots\Chrome;
use NunoMaduro\LaravelConsoleDusk\Manager;

class WebsiteEmulator
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


    public function emulate($command, $url, $commands = null): ?array
    {
        $this->data = ['', ''];
        $self = $this;
        try {
            $this->manager->browse($command, function ($browser) use ($url, $self) {
                try {

                    $price = '';

                    $sku = $browser->visit($url)
                        ->pause(500)
                        ->element('div.product-code div.value p.title')
                        ->getAttribute('innerHTML');

                    if($browser->visit($url)
                        ->pause(500)
                        ->element('span.price')) {

                        $price = $browser->visit($url)
                            ->pause(500)
                            ->element('span.price')
                            ->getAttribute('innerHTML')
                        ;

                    }

                    $sku = str_replace(' ', '', $sku);
                    $price = str_replace('&nbsp;', '', $price);

                    $self->data = [$price, $sku];

                } catch (\Exception $exception) {
                    $self->data = ['', ''];
                }
            });
        } catch (\Exception $exception) {
            $self->data = ['', ''];
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
