<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateImageBarcodeGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'barcode-generator-product:update {product_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Barcode into product';

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

        return true;
    }
}
