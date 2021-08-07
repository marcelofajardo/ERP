<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;

class RestoreCroppedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore:cropped-images';

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

            $products = Product::where('is_image_processed', 1)->get();

            foreach ($products as $product) {
                if ($product->hasMedia(config('constants.media_tags'))) {
                    $this->info($product->id);
                    $tc = count($product->getMedia(config('constants.media_tags')));
                    echo "$tc \n";
                    if ($tc < 8) {
                        $product->is_image_processed = 0;
                        $product->save();
                        continue;
                    }
                    foreach ($product->getMedia(config('constants.media_tags')) as $key => $image) {
                        if ($key + 1 > $tc / 2) {
                            $image_path = $image->getAbsolutePath();

                            echo "DELETED $key \n";

                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }

                            $image->delete();
                        }
                    }

                    $product->is_image_processed = 0;
                    $product->save();

                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
