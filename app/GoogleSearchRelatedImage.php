<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleSearchRelatedImage extends Model
{
    protected $fillable = [
		'google_search_image_id', 'google_image','image_url',
	];

    public function googleSearchImage()
    {
        return $this->belongsTo(GoogleSearchImage::class);
    }
}
