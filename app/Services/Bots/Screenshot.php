<?php

namespace App\Services\Bots;

use App\Console\Commands\Bots\Chrome;
use NunoMaduro\LaravelConsoleDusk\Manager;

class Screenshot
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


    public function emulate($command, $site, $commands = null)
    {
        $link = $site->link;
        $this->manager->browse($command, function ($browser) use ($link, $site) {
            $fn = md5(time());
            $browser->visit($link)->screenshot($fn);
        });
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
