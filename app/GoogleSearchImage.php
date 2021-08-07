<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleSearchImage extends Model
{
    protected $fillable = [
		'user_id', 'product_id','crop_image',
	];

	public function googleSearchRelatedImages()
    {
        return $this->hasMany(GoogleSearchRelatedImage::class);
    }
}
