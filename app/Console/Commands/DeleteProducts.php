<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;

class DeleteProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:products';

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

            $products = Product::withTrashed()->where('supplier', "Les Market")->get();
            // $product = Product::find(127805);
            // dd(count($products));
            foreach ($products as $key => $product) {
                dump("$key - Product");

                if ($product->hasMedia(config('constants.media_tags'))) {
                    dump("$key - Has Images");

                    foreach ($product->getMedia(config('constants.media_tags')) as $image) {
                        $image_path = $image->getAbsolutePath();

                        if (File::exists($image_path)) {
                            dump("$key - Deleting Image on server");
                            File::delete($image_path);
                            // unlink($image_path);
                        }

                        $image->delete();
                    }

                } else {
                    dump("$key - NO IMAGES");
                }

                $product->suppliers()->detach();

                if ($product->user()) {
                    dump('user');
                    $product->user()->detach();
                }

                $product->references()->delete();
                $product->suggestions()->detach();
                $product->forceDelete();
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
