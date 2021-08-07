<?php

namespace App\Console\Commands\Manual;

use App\Jobs\PushToMagento;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ManualQueueForMagento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:queue-manually';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue all products manually to be pushed to Magento';

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
        // Set memory limit
        ini_set('memory_limit', '2048M');
        try {
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            // Get all products queued for AI
            $products = Product::where('status_id', '=', 9)->where('stock', '>', 0)->get();

            // Loop over products
            foreach ($products as $product) {
                // Output product ID
                echo $product->id . "\n";

                // Queue for AI
                PushToMagento::dispatch($product)->onQueue('magento');
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
