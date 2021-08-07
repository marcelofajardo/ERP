<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProductLocationHistory extends Model
{
        /**
     * @var string
     * @SWG\Property(property="product_location_history",type="string")
     * @SWG\Property(property="location_name",type="string")
     * @SWG\Property(property="courier_name",type="string")
     * @SWG\Property(property="courier_details",type="string")
     * @SWG\Property(property="date_time",type="datetime")
     * @SWG\Property(property="created_by",type="datetime")
     * @SWG\Property(property="product_id",type="interger")
     */
	public $table  = "product_location_history";
    protected $fillable = ['location_name','courier_name','courier_details','date_time','product_id','created_by'];

    public function product()
    {
    	return $this->hasOne("\App\Products","id","product_id");
    }

    public function user()
    {
    	return $this->hasOne("\App\User","id","created_by");
    }
}
