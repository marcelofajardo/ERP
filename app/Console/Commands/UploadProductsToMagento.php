<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Jobs\PushToMagento;
use App\MagentoSoapHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UploadProductsToMagento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:upload-products';

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
        return false;
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            // Connect
            $magentoSoapHelper = new MagentoSoapHelper();

            // Get product
            $products = \App\Product::where('isListed', -5)->get();

            // Loop over products
            if ($products !== null) {
                foreach ($products as $product) {
                    // Dispatch
                    PushToMagento::dispatch($product);

                    // Update
                    $product->isListed = 1;
                    $product->save();
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
