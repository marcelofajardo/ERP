<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class ProductDispatch extends Model
{
     /**
     * @var string
     * @SWG\Property(property="modeof_shipment",type="string")
     * @SWG\Property(property="awb",type="string")
     * @SWG\Property(property="eta",type="string")
     * @SWG\Property(property="delivery_person",type="string")
     * @SWG\Property(property="date_time",type="datetime")
     * @SWG\Property(property="created_by",type="datetime")
     * @SWG\Property(property="product_id",type="interger")
     */
    use Mediable;
    
	public $table  = "product_dispatch";
    protected $fillable = ['modeof_shipment','awb','eta','delivery_person','date_time','product_id','created_by'];

    public function product()
    {
    	return $this->hasOne("\App\Products","id","product_id");
    }

    public function user()
    {
    	return $this->hasOne("\App\User","id","created_by");
    }
}
