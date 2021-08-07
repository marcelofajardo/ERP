<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\SkuColorReferences;

class TestTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Console command to test new things';

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
        $skuColorReferences = new SkuColorReferences;
        $skuColorReferences->brand_id = 1;
        $skuColorReferences->color_code = '1000';
        $skuColorReferences->color_name = 'Black';
        $skuColorReferences->save();
    }
}