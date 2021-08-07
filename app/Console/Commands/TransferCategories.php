<?php

namespace App\Console\Commands;

use App\Category;
use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TransferCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:categories';

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

            $transfers = [
                [90, 51],
                [91, 58],
                [110, 53],
                [111, 55],
                [118, 55],
                [64, 119],
                [121, 115],
                [120, 123],
                [125, 127],
                [126, 62],
                [16, 108],
                [27, 20],
                [29, 28],
                [131, 133],
                [132, 33],
                [15, 108],
            ];

            foreach ($transfers as $transfer) {

                Product::where('category', $transfer[0])->update([
                    'category' => $transfer[1],
                ]);
                Category::find($transfer[0])->delete();
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
