<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ImageSchedule extends Model
{
	   /**
     * @var string
     * @SWG\Property(property="scheduled_for",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'scheduled_for'
    ];

    public function image() {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }
}
