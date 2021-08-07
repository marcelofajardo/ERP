<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScheduleGroup extends Model
{
	/**
     * @var string
     * @SWG\Property(property="images",type="string")
     * @SWG\Property(property="scheduled_for",type="datetime")
     * @SWG\Property(property="timestamps",type="datetime")
     */
    protected $casts = [
        'images' => 'array'
    ];

    protected $dates = ['scheduled_for'];

    public $timestamps = false;

    public function getImagesAttribute($value) {
        return Image::whereIn('id', json_decode($value) ?? []);
    }
}
