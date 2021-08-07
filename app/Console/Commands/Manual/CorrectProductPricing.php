<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CorrectProductPricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:correctPricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct the pricing in the product table based on the scraped pricing';

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

            $sql = "
            SELECT
                p.id,
                p.sku,
                p.price*1 as price,
                MIN(sp.price_eur) AS sp_price_min,
                MAX(sp.price_eur) AS sp_price_max,
                GREATEST(p.price*1,MIN(sp.price_eur),MAX(sp.price_eur)) AS new_price
            FROM
                products p
            JOIN
                scraped_products sp
            ON
                p.id=sp.product_id
            WHERE
                discounted_price IS NOT NULL AND
                p.price!=sp.price_eur
            GROUP BY
                p.id
            HAVING
                MIN(sp.price_eur) > 0 AND
                MAX(sp.price_eur) > 0
            ORDER BY
                p.id
        ";
            $results = DB::select(DB::raw($sql));

            // Loop over result
            foreach ($results as $result) {
                // Get product to update
                $product = Product::find($result->id);

                // Log info and output info
                $info = "Product " . $product->id . " with SKU " . $product->sku . " updated from EUR " . $product->price . " to EUR " . $result->new_price;
                Log::channel('productUpdates')->info($info);
                dump($info);

                // Update the price
                $product->price = $result->new_price;
                $product->save();
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
