<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Illuminate\Console\Command;
use Carbon\Carbon;

class FetchCompositionToProductsIfTheyAreScraped extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composition:pull-if-in-scraped';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            Product::where('composition', '')->orWhereNull('composition')->orderBy('id', 'DESC')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    dump('On -- ' . $product->id);
                    $scrapedProducts = $product->many_scraped_products;
                    dump(count($scrapedProducts));
                    if (!count($scrapedProducts)) {
                        continue;
                    }

                    foreach ($scrapedProducts as $scrapedProduct) {
                        $property    = $scrapedProduct->properties;
                        $composition = $property['composition'] ?? '';
                        if ($composition) {
                            dump($composition);
                            $product->composition = $composition;
                            $product->save();
                            break;
                        }
                        $composition = $property['material_used'] ?? '';
                        if ($composition) {
                            dump($composition);
                            $product->composition = $composition;
                            $product->save();
                            break;
                        }
                        $composition = $property['Details'] ?? '';
                        if ($composition) {
                            dump($composition);
                            $product->composition = $composition;
                            $product->save();
                            break;
                        }
                    }

                }
            });

            Product::where('short_description', '')
                ->orWhereNull('short_description')
                ->orderBy('id', 'DESC')
                ->chunk(1000, function ($products) {
                    foreach ($products as $product) {
                        dump('On -- ' . $product->id);
                        $scrapedProducts = $product->many_scraped_products;
                        dump(count($scrapedProducts));
                        if (!count($scrapedProducts)) {
                            continue;
                        }

                        foreach ($scrapedProducts as $scrapedProduct) {
                            dump('here desc');
                            $description = $scrapedProduct->descriptionn;
                            $description = $description ?? '';
                            if ($description) {
                                dump($description);
                                $product->short_description = $description;
                                $product->save();
                                break;
                            }
                        }

                    }
                });

            Product::where('color', '')
                ->orWhereNull('color')
                ->orderBy('id', 'DESC')
                ->chunk(1000, function ($products) {
                    foreach ($products as $product) {
                        dump('On -- ' . $product->id);
                        $scrapedProducts = $product->many_scraped_products;
                        dump(count($scrapedProducts));
                        if (!count($scrapedProducts)) {
                            continue;
                        }

                        foreach ($scrapedProducts as $scrapedProduct) {
                            dump('here..color..');
                            $property = $scrapedProduct->properties;
                            $color    = $property['color'] ?? '';
                            if ($color && strlen($color) < 16) {
                                dump($color);
                                $product->color = $color;
                                $product->save();
                                break;
                            }
                            $color = $property['colors'] ?? '';
                            if ($color && strlen($color) < 16) {
                                dump($color);
                                $product->color = $color;
                                $product->save();
                                break;
                            }
                        }

                    }
                });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
