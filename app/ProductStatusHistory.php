<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProductStatusHistory extends Model
{
    /**
     * @var string
     * @SWG\Property(property="product_status_histories",type="string")
     */
    public $table = 'product_status_histories';

    public static function getStatusHistoryFromProductId($product_id)
    {

        $columns = array('old_status','new_status','created_at');

        return \App\ProductStatusHistory::where('product_id',$product_id)->get($columns);
    }

    public static function addStatusToProduct($data)
    {
        \App\ProductStatusHistory::insert($data);
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
