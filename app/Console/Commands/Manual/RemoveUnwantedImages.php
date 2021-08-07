<?php

namespace App\Console\Commands\Manual;

use App\CronJobReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Plank\Mediable\Media;

class RemoveUnwantedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-unwanted:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Unwanted Images';

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
        $medibles = Media::all();
        foreach($medibles as $media) {
            // check file exist or not if not the delete it
            if(file_exists($media->getAbsolutePath())) {
                // start to found mediable usage
                $mediables = \DB::table("mediables")->where("media_id",$media->id)->get();
                if(!$mediables->isEmpty()) {
                    $recordExist = false;
                    foreach($mediables as $aModal) {
                        $modal = (new $aModal->mediable_type)->find($aModal->mediable_id);
                        if($modal != null) {
                            $recordExist = true;
                            break;
                        }
                    }
                    if($recordExist == false) {
                        \Log::channel('productUpdates')->info($media->getAbsolutePath() . " Deleted With no relation [DELETE_IMAGES]");
                        $media->delete();
                    }
                }else{
                    // check file exist or not 
                    \Log::channel('productUpdates')->info($media->getAbsolutePath() . " Deleted with no relation mediables [DELETE_IMAGES]");
                    $media->delete();
                }
            }else{
                \Log::channel('productUpdates')->info($media->getAbsolutePath() . " Deleted not exist [DELETE_IMAGES]");
                $media->delete();
            }
        }
    }
}
