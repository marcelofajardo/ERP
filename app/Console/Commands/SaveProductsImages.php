<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Command;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class SaveProductsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:products-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Saves Products images on ERP server after it has been scraped on another';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Set memory limit
        ini_set('memory_limit', '256M');
        try {

            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // $products = Product::where('is_without_image', 1)->whereNotIn('supplier', ['Tiziana Fausti', 'Giglio Lamezia Terme', "Al Duca d'Aosta", 'Carofiglio Junior'])->get();
            // $products = Product::where('is_without_image', 1)->where('supplier', 'Tiziana Fausti')->get();
            // $products = Product::where('is_without_image', 1)->where('supplier', 'Tessabit')->get();
            // $products = Product::where('is_without_image', 1)->get();

            $products = Product::where('is_without_image', 1)->whereNotIn('supplier', ['MINETTI', 'Spinnaker 101'])->get();
            // $products = Product::where('supplier', "Savannah's")->where('created_at', 'LIKE', "%2019-06-05%")->get();
            // $products = Product::where('is_without_image', 1)->whereIn('supplier', ['Spinnaker 101'])->get();
            // $products = Product::where('is_without_image', 1)->where('sku', '558882W088G9963')->get();

            // $products = Product::where('is_without_image', 1)->where('sku', 'XXM45A00D80RE0')->get();
            // dd(count($products));
            // IGNOR GIGLIO !!!
            // WRONG Tessabit

            // $products = Product::doesnthave('Media')->whereRaw('products.id IN (SELECT product_id FROM product_suppliers WHERE supplier_id = 1)')->get();
            //
            // dd(count($products));

            // dd(count($products));
            // $products = Product::where('is_without_image', 1)->where('sku', 'BE2007E00C001')->get();
            $count      = 0;
            $has_images = 0;
            foreach ($products as $key => $product) {
                echo "$key - Found Product \n";

                if ($product->hasMedia(config('constants.media_tags'))) {
                    dump("Has Linked Images");
                    $has_images++;

                    // continue;
                } else {
                    $count++;
                    // continue;
                }

                // dd($product->many_scraped_products);

                foreach ($product->many_scraped_products as $scraped_product) {
                    dump("$key - Found Scraped Product - " . $product->scraped_products->sku);

                    $images = $scraped_product->images;

                    if ($images != '') {
                        $product->detachMediaTags(config('constants.media_tags'));

                        foreach ($images as $key2 => $image_path) {
                            try {
                                dump("$key2 - Trying save image");

                                if (strpos(strtolower($image_path), 'tiziana') !== false) {
                                    $to_lower = strtolower($image_path);
                                } else {
                                    $to_lower = $image_path;
                                }

                                if ($product->supplier == 'MINETTI' || $product->supplier == 'Tory Burch' || $product->supplier == 'Wise Boutique' || $product->supplier == 'Stilmoda' || $product->supplier == 'Lidia' || $product->supplier == 'Tessabit' || strpos(strtolower($image_path), 'rsc.cdn77') !== false) {
                                    $formatted_final = $to_lower;
                                } else {
                                    if (strpos(strtolower($to_lower), '.jpg') !== false) {
                                        $formatted_final = substr($to_lower, 0, strpos(strtolower($to_lower), '.jpg')) . '.jpg';
                                    } else {
                                        if (strpos(strtolower($to_lower), '.png') !== false) {
                                            $formatted_final = substr($to_lower, 0, strpos(strtolower($to_lower), '.png')) . '.png';
                                        } else {
                                            $formatted_final = $to_lower;
                                        }
                                    }

                                    $formatted_final = str_replace('//foto', '/foto', $formatted_final);

                                    $exploded_url     = explode('/', $formatted_final);
                                    $corrected_pieces = [];
                                    foreach ($exploded_url as $key => $piece) {
                                        if ($key == 0) {
                                            $corrected_pieces[] = $piece;
                                        } else {
                                            $corrected_pieces[] = urlencode($piece);
                                        }
                                    }

                                    $formatted_final = implode('/', $corrected_pieces);

                                    $formatted_final = str_replace('+', '%20', $formatted_final);
                                }

                                if (strpos(strtolower($formatted_final), 'giglio') !== false) {
                                    $cookieJar = CookieJar::fromArray([
                                        '__cfduid' => 'd866a348dc8d8be698f25655b77ada8921560006391',
                                    ], '.giglio.com');

                                    $guzzle = new Client();

                                    $params = [
                                        'cookies' => $cookieJar,
                                    ];

                                    $response  = $guzzle->request('GET', $formatted_final, $params);
                                    $file_path = public_path() . '/uploads/' . '/one.jpg';

                                    file_put_contents($file_path, $response->getBody()->getContents());

                                    $media = MediaUploader::fromSource($file_path)
                                        ->useFilename(uniqid(true))
                                        ->toDirectory('product/' . floor($product->id / config('constants.image_per_folder')))
                                        ->upload();

                                    unlink($file_path);
                                } else {
                                    if (strpos(strtolower($formatted_final), 'lidia') !== false) {
                                        $cookieJar = CookieJar::fromArray([
                                            '__utma' => '219919236.1725201173.1559985148.1560276367.1560276367.1',
                                        ], '.lidiashopping.it');

                                        $guzzle = new Client();

                                        $params = [
                                            'cookies' => $cookieJar,
                                        ];

                                        $response  = $guzzle->request('GET', $formatted_final, $params);
                                        $file_path = public_path() . '/uploads/' . '/one.jpg';

                                        file_put_contents($file_path, $response->getBody()->getContents());

                                        $media = MediaUploader::fromSource($file_path)
                                            ->useFilename(uniqid(true))
                                            ->toDirectory('product/' . floor($product->id / config('constants.image_per_folder')))
                                            ->upload();

                                        unlink($file_path);
                                    } else {
                                        if (strpos(strtolower($formatted_final), 'd1p88qcukgx4cm.cloudfront') !== false) {
                                            $cookieJar = CookieJar::fromArray([
                                                '__gid' => 'GA1.3.1355298.1560441527',
                                            ], '.d1p88qcukgx4cm.cloudfront.net');

                                            $guzzle = new Client();

                                            $params = [
                                                'cookies' => $cookieJar,
                                            ];

                                            $response  = $guzzle->request('GET', $formatted_final, $params);
                                            $file_path = public_path() . '/uploads/' . '/one.jpg';

                                            file_put_contents($file_path, $response->getBody()->getContents());

                                            $media = MediaUploader::fromSource($file_path)
                                                ->useFilename(uniqid(true))
                                                ->toDirectory('product/' . floor($product->id / config('constants.image_per_folder')))
                                                ->upload();

                                            unlink($file_path);
                                        } else {
                                            $media = MediaUploader::fromSource($formatted_final)
                                                ->useFilename(uniqid(true))
                                                ->toDirectory('product/' . floor($product->id / config('constants.image_per_folder')))
                                                ->upload();
                                        }
                                    }
                                }

                                $product->attachMedia($media, config('constants.media_tags'));

                                $product->is_without_image = 0;
                                $product->save();
                            } catch (\Exception $e) {
                                echo "$key - Couldn't upload image " . $e->getMessage() . " - $product->sku \n";
                                echo "$image_path \n";
                            }

                        }

                        if ($product->is_without_image == 0) {
                            break;
                        }
                    }
                }
                // else {
                //   echo "$key - Didn't find match - " . $product->sku . "\n";
                // }
            }

            dump($count, $has_images);

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
