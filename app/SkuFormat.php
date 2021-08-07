<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Brand;

class SkuFormat extends Model
{
	 /**
     * @var string
	 * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="sku_format",type="string")
     */
    protected $fillable = ['brand_id','category_id','sku_format'];

    public function category(){
        return $this->hasOne(Category::class ,'id','category_id');
    }

    public function brand(){
        return $this->hasOne(Brand::class,'id','brand_id');
    }
}
