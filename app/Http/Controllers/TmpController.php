<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Plank\Mediable\Media;
use Illuminate\Http\Request;

class TmpController extends Controller
{

    public function updateImageDirectory( Request $request)
    {
        $mediaArr = Media::paginate($request->get('limit', 1000));
        foreach ($mediaArr as $media) {
            if (empty($media->directory) && $media->fileExists()) {
                $mediables = DB::table('mediables')->where('media_id', $media->id)->first();
                if ($mediables) {
                    $table = strtolower(str_replace('App\\', '', $mediables->mediable_type));
                    
                    if (!empty($mediables->mediable_id) && $mediables->mediable_id > 1) {
                        $key   = floor($mediables->mediable_id/10000);
                    } else {
                        $key   = strtolower(substr($media->basename,0,1).'/'.substr($media->basename,1,1));
                    }

                    if ($media->getDiskPath() != $table.'/'.$key.'/'.ltrim($media->basename, '/')) {
                        $media->move($table.'/'.$key);
                    }
                }
            }
        }
    }
}
