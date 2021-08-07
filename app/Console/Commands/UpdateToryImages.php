<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Product;
use App\ScrapedProducts;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class UpdateToryImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tory-images';

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

            $scraped_products = ScrapedProducts::where('website', 'Tory')->get();

            foreach ($scraped_products as $scraped_product) {
                if ($scraped_product->product) {
                    if ($scraped_product->product->hasMedia(config('constants.media_tags'))) {
                        dump('MEDIA');
                    } else {
                        $images = $scraped_product->images;

                        foreach ($images as $image_name) {
                            $path  = public_path('uploads') . '/social-media/' . $image_name;
                            $media = MediaUploader::fromSource($path)
                                ->toDirectory('product/' . floor($scraped_product->product->id / config('constants.image_per_folder')))
                                ->upload();
                            $scraped_product->product->attachMedia($media, config('constants.media_tags'));
                        }
                    }
                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
        dd('stap');
    }
}
