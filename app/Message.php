<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\Mediable;


class Message extends Model
{
		/**
     * @var string
   * @SWG\Property(property="body",type="string")
     * @SWG\Property(property="subject",type="string")
     * @SWG\Property(property="moduletype",type="string")
        * @SWG\Property(property="userid",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="assigned_to",type="integer")
     * @SWG\Property(property="status",type="string")
     */
    use Mediable;

    protected $fillable = ['body','subject','moduletype','userid', 'customer_id', 'assigned_to','status','moduleid'];
    public function user()
	{
	   return $this->belongsTo(User::class);
	}
}
