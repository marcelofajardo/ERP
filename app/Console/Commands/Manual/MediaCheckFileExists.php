<?php

namespace App\Console\Commands\Manual;

use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MediaCheckFileExists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:check-file-exists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check if images for products exist on the disk and remove media from database if it doesn't exist";

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
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            // Set empty cnt
            $cnt = 0;

            // Get all products
            $products = Product::all();

            // Loop over products
            foreach ($products as $product) {
                // Check for media
                if ($product->hasMedia(config('constants.media_tags'))) {
                    $medias = $product->getMedia(config('constants.media_tags'));

                    if ($medias != null) {
                        foreach ($medias as $media) {
                            $file = public_path() . '/' . $media->disk . (!empty($media->directory) ? '/' . $media->directory : '') . '/' . $media->filename . '.' . $media->extension;
                            if (!file_exists($file)) {
                                // Delete media and mediables
                                $product->detachMedia($media);
                                $media->delete();

                                echo "REMOVED " . $file . " WITH ID " . $media->id . " FROM DATABASE FOR PRODUCT " . $product->id . "\n";
                                $cnt++;
                            }
                        }
                    }
                }
            }

            // Output result
            echo "\n" . $cnt . " file(s) deleted\n";
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
