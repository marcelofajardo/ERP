<?php

namespace App\Console\Commands;

use App\AttributeReplacement;
use App\CronJobReport;
use App\Product;
use App\ProductStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReplaceTextsFromProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:replace-text';

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
        return;
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // Return - do not run
            return;

            // Get all replacements
            $replacements = AttributeReplacement::all();

            // Get all products in chunks of 1000 records
            Product::select('products.*', 'product_status.name', 'product_status.value')->leftJoin('product_status', function ($join) {
                $join->on('products.id', '=', 'product_status.product_id')->where('product_status.name', 'ATTRIBUTE_TEXT_REPLACEMENTS');
            })->orderBy('products.id', 'DESC')->chunk(1000, function ($products) use ($replacements) {

                // Loop over products
                foreach ($products as $product) {
                    // Output information
                    echo "Checking product " . $product->id . "\n";

                    // Loop over replacements
                    foreach ($replacements as $replacement) {
                        // Name
                        if ($replacement->field_identifier == 'name') {
                            $product->name = str_replace([$replacement->first_term, title_case($replacement->first_term), strtolower($replacement->first_term), strtoupper($replacement->first_term)], $replacement->replacement_term ?? '', $product->name);
                            $product->name = htmlspecialchars_decode($product->name);
                        }

                        // Composition
                        if ($replacement->field_identifier == 'composition') {
                            $product->composition = str_replace([$replacement->first_term, title_case($replacement->first_term), strtolower($replacement->first_term), strtoupper($replacement->first_term)], $replacement->replacement_term ?? '', $product->composition);
                            $product->composition = htmlspecialchars_decode($product->composition);
                        }

                        // Short description
                        if ($replacement->field_identifier == 'short_description') {
                            $product->short_description = str_replace([$replacement->first_term, title_case($replacement->first_term), strtolower($replacement->first_term), strtoupper($replacement->first_term)], $replacement->replacement_term ?? '', $product->short_description);
                            $product->short_description = htmlspecialchars_decode($product->short_description);
                        }
                    }

                    // Save the product
                    $product->save();

                    // Update the product status
                    ProductStatus::updateStatus($product->id, 'ATTRIBUTE_TEXT_REPLACEMENTS', 1);
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
