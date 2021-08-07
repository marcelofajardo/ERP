<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Activity extends Model
{
	/**
     * @var string
     * @SWG\Property(property="subject_id",type="integer")
     * @SWG\Property(property="subject_type",type="string")
     * @SWG\Property(property="causer_id",type="integer")
     * @SWG\Property(property="description",type="text")
     */
    protected $fillable = ['subject_id','subject_type','causer_id','description'];

    public function causer()
    {
        return $this->belongsTo('App\User');
    }
}
