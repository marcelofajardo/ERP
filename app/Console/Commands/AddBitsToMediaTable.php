<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Plank\Mediable\Media;
use App\Helpers\CompareImagesHelper;
use Illuminate\Support\Facades\DB;

class AddBitsToMediaTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AddBitsToMediaTable';

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
        DB::table('media')->whereNull('bits')->where('directory', 'like', '%product/%')->orderBy('id')->chunk(100, function($medias)
        {
            foreach ($medias as $m)
            {

                if(! DB::table('mediables')->where('media_id', $m->id)->first()){
                    dump('skip => mediable not exist' . $m->id);
                    Media::where('id', $m->id)->update([
                        'bits' => 1
                    ]);
                    continue;
                }
                $a = 'https://erp.theluxuryunlimited.com/' . $m->disk . '/' . $m->directory . '/' . $m->filename . '.' . $m->extension;
                if (!@file_get_contents($a)) {
                    dump('skip => ' . $a);
                    Media::where('id', $m->id)->update([
                        'bits' => 0
                    ]);
                    continue;
                }
                $i1 = CompareImagesHelper::createImage($a);
                
                $i1 = CompareImagesHelper::resizeImage($i1,$a);
                
                imagefilter($i1, IMG_FILTER_GRAYSCALE);
                
                $colorMean1 = CompareImagesHelper::colorMeanValue($i1);
                
                $bits1 = CompareImagesHelper::bits($colorMean1);
    
                Media::where('id', $m->id)->update([
                    'bits' => implode($bits1) 
                ]);
            }
        });
 

    }
}
