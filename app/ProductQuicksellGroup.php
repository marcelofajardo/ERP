<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductQuicksellGroup extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="quicksell_group_id",type="integer")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="product_quicksell_groups",type="string")
     */
    protected $table = 'product_quicksell_groups';
    protected $fillable = ['quicksell_group_id','product_id'];

    public function products()
    {
    	return $this->belongsTo(Product::class,'product_id','id');
    }

    

}
