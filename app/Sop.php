<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\PurchaseProductOrderLog;

class Sop extends Model
{
    protected $table ="sops";
     protected $fillable = ['name','content'];
 
    public function purchaseProductOrderLogs(){
        return $this->hasOne(PurchaseProductOrderLog::class, 'purchase_product_order_id', 'id');
    }
}
