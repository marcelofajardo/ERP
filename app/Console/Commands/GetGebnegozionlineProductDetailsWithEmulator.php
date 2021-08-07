<?php

namespace App\Console\Commands;

use App\Brand;
use App\CronJobReport;
use App\Product;
use App\ScrapedProducts;
use App\Services\Bots\WebsiteEmulator;
use App\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetGebnegozionlineProductDetailsWithEmulator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gnb:update-price-via-dusk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $country;
    protected $IP;

    public function handle(): void
    {
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $letters = env('SCRAP_ALPHAS', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
            if (strpos($letters, 'G') === false) {
                return;
            }
            // $products = ScrapEntries::where('is_scraped', 0)->where('is_product_page', 1)->where('site_name', 'GNB')->take(250)->get();
            $products = ScrapedProducts::where('website', 'GNB')->get();
            foreach ($products as $product) {
                $this->runFakeTraffic($product->url);
            }

            $report->update(['end_time' => Carbon::now()]);

        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    public function doesProductExist($url)
    {
        $duskShell = new WebsiteEmulator();
        $this->setCountry('IT');
        $duskShell->prepare();

        try {
            $content = $duskShell->emulate($this, $url, '');
        } catch (Exception $exception) {
            $content = ['', ''];
        }

        if (strlen($content[0]) > 3 && strlen($content[1]) > 4) {
            return true;
        }

        return false;
    }

    private function runFakeTraffic($url): void
    {
        $url       = explode('/category', $url);
        $url       = $url[0];
        $duskShell = new WebsiteEmulator();
//        $duskShell->setProxyList();
        $this->setCountry('IT');
        $duskShell->prepare();

        try {
            $content = $duskShell->emulate($this, $url, '');
        } catch (\Exception $exception) {
            $content = ['', ''];
        }

        if ($content === ['', '']) {
            return;
        }

        $image        = ScrapedProducts::where('sku', $content[1])->first();
        $image->price = $content[0];
        $image->save();
        if (!$image) {
            return;
        }

        if ($image->is_updated_on_server == 1) {
            return;
        }

        $this->updateDataOnProductsTable($image);

    }

    private function setCountry(): void
    {

        $this->country = 'IT';
    }

    private function updateProductOnServer(ScrapedProducts $image)
    {

        $this->info('here saving to server');
        $client   = new Client();
        $response = $client->request('POST', 'http://erp.sololuxury.co.in/api/sync-product', [
//        $response = $client->request('POST', 'https://erp.sololuxury.co.in/api/sync-product', [
            'form_params' => [
                'sku'                 => $image->sku,
                'website'             => $image->website,
                'has_sku'             => $image->has_sku,
                'title'               => $image->title,
                'brand_id'            => $image->brand_id,
                'description'         => $image->description,
//                'images' => $this->imagesToDownload,
                'price'               => $image->price,
                'properties'          => $image->properties,
                'url'                 => $image->url,
                'is_property_updated' => 0,
                'is_price_updated'    => 1,
                'is_enriched'         => 0,
                'can_be_deleted'      => 0,
            ],
        ]);

        if (!$response) {
            dd($response->getBody()->getContents());
        }

        $image->is_updated_on_server = 1;
        $image->save();
    }

    private function setIP(): void
    {
        $this->IP = '5.61.4.70  ' . ':' . '8080';
    }

    private function updateDataOnProductsTable($image)
    {
        //get product by sku...
        //now in scraped images its in euros, update that price...
        //
        if ($product = Product::where('sku', $image->sku)->first()) {
            $brand = Brand::find($image->brand_id);

            if (strpos($image->price, ',') !== false) {
                if (strpos($image->price, '.') !== false) {
                    if (strpos($image->price, ',') < strpos($image->price, '.')) {
                        $final_price = str_replace(',', '', $image->price);
                    }
                } else {
                    $final_price = str_replace(',', '.', $image->price);
                }
            } else {
                $final_price = $image->price;
            }

            $price = round(preg_replace('/[\&euro;€,]/', '', $final_price));

            $product->price = $price;

            if (!empty($brand->euro_to_inr)) {
                $product->price_inr = $brand->euro_to_inr * $product->price;
            } else {
                $product->price_inr = Setting::get('euro_to_inr') * $product->price;
            }

            $product->price_inr         = round($product->price_inr, -3);
            $product->price_inr_special = $product->price_inr - ($product->price_inr * $brand->deduction_percentage) / 100;

            $product->price_inr_special = round($product->price_inr_special, -3);

            $product->save();
        }
    }
}
