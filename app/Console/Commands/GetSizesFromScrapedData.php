<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetSizesFromScrapedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'size:extract-from-raw-data';

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

            Product::where(function ($q) {
                $q->where('size', '')
                    ->orWhereNull('size')
                ;
            })->where('is_approved', 0)->where('is_crop_ordered', '1')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    dump($product->id);
                    $scrapedProducts = $product->many_scraped_products;
                    foreach ($scrapedProducts as $scrapedProduct) {
                        $properties = $scrapedProduct->properties;
                        $size       = $properties['size'] ?? '';
                        if (is_array($size)) {
                            $size = implode(',', $size);
                        }
                        $size = $this->getSizeFromStr($size);

                        if ($size) {
                            $product->size = $size;
                            $product->save();
                            break;
                        }

                        $size = $properties['sizes'] ?? '';
                        dump($size);

                        if (is_array($size)) {
                            $size = implode(',', $size);
                        }
                        $size = $this->getSizeFromStr($size);

                        if ($size) {
                            dump($size);
                            $product->size = $size;
                            $product->save();
                            break;
                        }

                        $size = $properties['sizes_prop'] ?? '';

                        if (is_array($size)) {
                            $size = implode(',', $size);
                        }
                        $size = $this->getSizeFromStr($size);

                        if ($size) {
                            dump($size);
                            $product->size = $size;
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

    private function getSizeFromStr($sizes)
    {
        if (strlen($sizes) < 60) {
            return str_replace(['/2', '+', '½'], '.5', $sizes);
        }

        return '';
    }
}
