<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Supplier;

class ProductSizes extends Model
{
     /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="supplier_id",type="integer")
     * @SWG\Property(property="quantity",type="string")
     * @SWG\Property(property="size",type="string")
     */
    protected $fillable = ['product_id', 'supplier_id', 'quantity' , 'size'];

    public function supplier()
    {
        return $this->hasOne(\App\Supplier::class,'id','supplier_id');
    }
}
