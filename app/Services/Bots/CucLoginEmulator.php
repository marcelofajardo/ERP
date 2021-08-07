<?php

namespace App\Services\Bots;

use App\Brand;
use App\Console\Commands\Bots\Chrome;
use App\ScrapEntries;
use GuzzleHttp\Client;
use NunoMaduro\LaravelConsoleDusk\Manager;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class CucLoginEmulator
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

                    $brands = Brand::whereNull('deleted_at')->get();

                    $pages = [
                        'male' => 'http://shop.cuccuini.it/it/shop_man_designer_all_all_',
                        'female' => 'http://shop.cuccuini.it/it/shop_woman_designer_all_all_',
                        'pagination' => 'http://shop.cuccuini.it/it/shop_woman_designer_all_all?temi={BRAND_NAME}&page={PAGE_NUMBER}',
                        'base' => 'http://shop.cuccuini.it/it/'
                    ];

                    $allUrls = [];

                    foreach ($brands as $brand) {
                        if ($brand->name == 'TODS') {
                            $brand->name = 'tod%27s';
                        }
                        $brandName = str_replace(' ', '+', strtolower(trim($brand->name)));
                        $productListUrl = $pages['female'].$brandName;
                        $browser->visit($productListUrl);
                        $c = new HtmlPageCrawler($browser->element('.colonna_shop')->getAttribute('innerHTML'));
                        $paginationFilter = $c->filter('div.bloccopagine .pagine:last-child a');

                        if (count($paginationFilter) > 0) {
                            foreach ($paginationFilter as $data) {
                                $pageNumber = explode('=', $data->getAttribute('href'));
                                $pageNumber = $pageNumber[count($pageNumber)-1];

                                if ($pageNumber > 50 || $pageNumber < 1) {
                                    $allUrls[$brand->name][] = $productListUrl;
                                }
                                for ($i=1;$i<=$pageNumber;$i++) {
                                    $brandNameForPagination = str_replace(' ', '+', strtoupper($brand->name));
                                    $allUrls[$brand->name][] = str_replace('{PAGE_NUMBER}', $i, str_replace('{BRAND_NAME}', $brandNameForPagination, $pages['pagination']));
                                }
                                break;
                            }
                        } else {
                            $allUrls[$brand->name][] = $productListUrl;
                        }
                    }

                    foreach ($brands as $brand) {
                        if ($brand->name == 'TODS') {
                            $brand->name = 'tod%27s';
                        }
                        $brandName = str_replace(' ', '+', strtolower(trim($brand->name)));
                        $productListUrl = $pages['male'].$brandName;
                        $browser->visit($productListUrl);
                        $c = new HtmlPageCrawler($browser->element('.colonna_shop')->getAttribute('innerHTML'));
                        $paginationFilter = $c->filter('div.bloccopagine .pagine:last-child a');

                        if (count($paginationFilter) > 0) {
                            foreach ($paginationFilter as $data) {
                                $pageNumber = explode('=', $data->getAttribute('href'));
                                $pageNumber = $pageNumber[count($pageNumber)-1];

                                if ($pageNumber > 50 || $pageNumber < 1) {
                                    $allUrls[$brand->name][] = $productListUrl;
                                }
                                for ($i=1;$i<=$pageNumber;$i++) {
                                    $brandNameForPagination = str_replace(' ', '+', strtoupper($brand->name));
                                    $allUrls[$brand->name][] = str_replace('{PAGE_NUMBER}', $i, str_replace('{BRAND_NAME}', $brandNameForPagination, $pages['pagination']));
                                }
                                break;
                            }
                        } else {
                            $allUrls[$brand->name][] = $productListUrl;
                        }
                    }

                    foreach ($allUrls as $key=>$allUrlWithBrand) {
                        foreach ($allUrlWithBrand as $productsUrl) {
                            $browser->visit($productsUrl);
                            $htmlData = $browser->element('.colonna_shop')->getAttribute('innerHTML');
                            $c = new HtmlPageCrawler($htmlData);
                            $data = $c->filter('div.contfoto a')->getIterator();

                            if (count($data) === 0) {
                                $allUrls[$key] = [];
                                continue;
                            }

                            foreach ($data as $datum) {
                                $productUrl = $datum->getAttribute('href');
                                $entry = ScrapEntries::where('url', $productUrl)->first();
                                if (!$entry) {
                                    $entry = new ScrapEntries();
                                }
                                $entry->url = $pages['base'].$productUrl;
                                $entry->title = $productUrl;
                                $entry->is_product_page = 1;
                                $entry->site_name = 'cuccuini';
                                $entry->save();

                            }
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
