<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixWirdSizesForAllProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sizes:fix-weird-sizes';

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

            Product::where('is_approved', 0)->orderBy('updated_at', 'DESC')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    dump('Updating..' . $product->id);
                    $product->short_description = str_replace([' ', '/', ';', '-', "\n", '\n', '_', "\\"], ' ', $product->short_description);
                    $product->composition       = str_replace([' ', '/', ';', '-', "\n", '\n', '_', "\\", 'Made in', 'Made In', 'Italy', 'France', 'Portugal'], ' ', $product->composition);
                    $product->save();
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
