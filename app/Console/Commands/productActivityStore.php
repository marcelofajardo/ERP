<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\scraperImags;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use seo2websites\ErpExcelImporter\ErpExcelImporter;
use Illuminate\Support\Facades\File;

class productActivityStore extends Command
{
    //scrappersImagesDelete
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productActivityStore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add product activity data for the day.';

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
        $productStats = DB::table('products')
            ->select('status_id', DB::raw('COUNT(id) as total'))
            ->where('stock', '>=', 1)
            ->groupBy('status_id')
            ->pluck('total', 'status_id')->all();
        foreach ($productStats as $key => $productStat) {
            $productStats = DB::table('productactivities')->insert([
                'status_id' => $key,
                'value' => $productStat,
                'created_at' => DB::raw('CURRENT_TIMESTAMP')
            ]);
        }

        $this->output->write('Cron complated', true);
    }
}