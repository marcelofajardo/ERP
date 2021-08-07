<?php

namespace App\Observers;

use \Plank\Mediable\Media;
use App\Helpers\CompareImagesHelper;

class MediaObserver
{
    public function created(Media $media)
    { 
        $this->updateBits($media);
    }

    public function updated(Media $media)
    { 
        $this->updateBits($media);
    }

    public function saved(Media $media)
    { 
        $this->updateBits($media);
    }

    public static function updateBits($media)
    { 
        $ref_file = $media->getUrl(); 
        if(@file_get_contents($ref_file) && $media->aggregate_type == "image"){
            $i1 = CompareImagesHelper::createImage($ref_file);
            $i1 = CompareImagesHelper::resizeImage($i1,$ref_file);
            imagefilter($i1, IMG_FILTER_GRAYSCALE);
            $colorMean1 = CompareImagesHelper::colorMeanValue($i1);
            $bits1 = CompareImagesHelper::bits($colorMean1);
            
            Media::where('id', $media->id)->update([
                'bits' => implode($bits1) 
            ]); 
        }
    }
}
