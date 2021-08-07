<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use App\ProductReference;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FillProductReference extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:reference';

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

            $products = Product::all();

            foreach ($products as $product) {
                $reference             = new ProductReference;
                $reference->product_id = $product->id;
                $reference->sku        = $product->sku;
                $reference->color      = $product->color;
                $reference->save();

                if (!empty($product->size)) {
                    $sizes = explode(',', $product->size);

                    foreach ($sizes as $size) {
                        $reference             = new ProductReference;
                        $reference->product_id = $product->id;
                        $reference->sku        = $product->sku;
                        $reference->color      = $product->color;
                        $reference->size       = $size;
                        $reference->save();
                        dump($size);
                    }
                }
            }

            dd('stap');

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
