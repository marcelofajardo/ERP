<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Brand;

class SkuFormatHistory extends Model
{
	 /**
     * @var string
	 * @SWG\Property(property="sku_format_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="old_sku_format",type="string")
     * @SWG\Property(property="sku_format",type="string")
     */
    protected $fillable = ['sku_format_id','old_sku_format','sku_format','user_id'];

    public function user()
    {
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function skuFormat()
    {
        return $this->hasOne(\App\SkuFormat::class,'id','sku_format_id');
    }
    
}
