<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeExternalScraperStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external-status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'External status update';

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
        //
        $products = \App\Product::where("status_id",\App\Helpers\StatusHelper::$externalScraperFinished)->get();
        if(!$products->isEmpty()) {
            foreach($products as $product) {
                $product->checkExternalScraperNeed();
            }
        }
    }
}
