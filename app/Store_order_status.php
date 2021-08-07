<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Store_order_status extends Model
{
            /**
     * @var string
      * @SWG\Property(property="order_status_id",type="integer")
      * @SWG\Property(property="store_website_id",type="integer")
      * @SWG\Property(property="store_master_status_id",type="integer")
      * @SWG\Property(property="status",type="string")
     */
    protected $fillable = ['order_status_id','store_website_id','status','store_master_status_id'];

    public function order_status() {
        return $this->belongsTo('App\OrderStatus');
    }

    public function store_website() {
        return $this->belongsTo('App\StoreWebsite');
    }

    public function store_master_status() {
        return $this->belongsTo('App\StoreMasterStatus');
    }
}
