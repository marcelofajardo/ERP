<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class waybillTrackHistories extends Model
{

	  /**
     * @var string
      * @SWG\Property(property="waybill_track_histories",type="string")
      * @SWG\Property(property="waybill_id",type="integer")
      * @SWG\Property(property="comment",type="string")
      * @SWG\Property(property="dat",type="string")
      * @SWG\Property(property="order_status_id",type="integer")
     */
    protected $table = 'waybill_track_histories';

    protected $fillable = ['waybill_id','comment','dat','order_status_id'];

    public function waybill(){
    	return $this->belongsto('App\Waybill');
    }
}
