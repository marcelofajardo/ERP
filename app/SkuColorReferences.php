<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SkuColorReferences extends Model
{
	  /**
     * @var string
     * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="color_name",type="string")
     * @SWG\Property(property="color_code",type="string")
    
     */
    protected $fillable = ['brand_id', 'color_name', 'color_code'];

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }
}