<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{

	public static function generateImageName($key){

		$name = Input::file( $key )->getClientOriginalName();
		$extension = Input::file( $key )->getClientOriginalExtension();
		$timestamp = date("Y-m-d-His",time());

		return $name.'-'.$timestamp.'.'.$extension;
	}

	public static function newImage($key = 'image'){

		$image_name = self::generateImageName($key);
		Input::file($key )->move('uploads',$image_name);

		return $image_name;
	}

	public static function replaceImage($imageName,$key = 'image'){

		File::move( public_path().config('constants.uploads_dir').$imageName,
					public_path().config('constants.archive__dir').$imageName
		);
		return self::newImage($key);
	}

	public static function trashImage($imageName){

		$path = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();
		File::move($path.'/'.$imageName,
			$path.'trash/'.$imageName
		);
	}

	public function schedule(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
	    return $this->hasOne(ImageSchedule::class, 'image_id', 'id');
    }

}
