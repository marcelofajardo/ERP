<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProductColorHistory extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="old_color",type="string")
     * @SWG\Property(property="color",type="string")
     * @SWG\Property(property="user_id",type="interger")
     */
    protected $fillable = ['product_id','old_color','color','user_id'];

    public function product()
    {
        return $this->hasOne(\App\Product::class, "id","product_id");
    }
}
