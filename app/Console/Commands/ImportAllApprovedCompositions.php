<?php

namespace App\Console\Commands;

use App\Compositions;
use App\CronJobReport;
use App\Product;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ImportAllApprovedCompositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:compositions';

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

            $products = Product::where('is_approved', 1)->get();
            foreach ($products as $product) {
                $composition = $product->composition;
                $composition = preg_replace('/[0-9]+/', '', $composition);
                $composition = str_replace(['%', ',', ':', 'Exterior', 'Interior', 'Made In', 'Italy', 'Portugal', 'France', '.'], '', $composition);
                $composition = str_replace(["\n", '\n'], ' ', $composition);
                $composition = explode(' ', trim($composition));

                if (count($composition) > 10) {
                    continue;
                }

                foreach ($composition as $cmp) {
                    $cmpr = Compositions::where('name', trim($cmp))->first();
                    if ($cmpr || strlen($cmp) < 4 || in_array(strtolower($cmp), ['sole', 'and', 'from'], true)) {
                        continue;
                    }

                    dump('Adding ' . $cmp);

                    $cmpr       = new Compositions();
                    $cmpr->name = trim($cmp);
                    $cmpr->save();

                }

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
