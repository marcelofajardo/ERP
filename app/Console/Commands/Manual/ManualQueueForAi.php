<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use App\Jobs\ProductAi;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ManualQueueForAi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:queue-manually';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue all products manually';

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
            // Get all products queued for AI
            $products = Product::where('status_id', '>', 2)->where('stock', '>', 0)->limit(10)->get();

            // Loop over products
            foreach ($products as $product) {
                // Output product ID
                echo $product->id . "\n";

                // Queue for AI
                ProductAi::dispatch($product)->onQueue('product');
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
