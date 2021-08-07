<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixSpecialCharactersInDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fix-special-characters';

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

            Product::where('is_approved', 0)->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    dump($product->id);
                    $description                = str_replace(['&nbsp;', '\n', "\n", '&eacute;', '&egrave;', '&Egrave;'], ' ', $product->short_description);
                    $composition                = str_replace(['&nbsp;', '\n', "\n", '&eacute;', '&egrave;', '&Egrave;'], ' ', $product->composition);
                    $product->short_description = $description;
                    $product->composition       = $composition;
                    $product->save();
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
