<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteSize extends Model
{
    	  /**
     * @var string
    
      * @SWG\Property(property="size_id",type="integer")
   
      * @SWG\Property(property="store_website_id",type="integer")

     */
    protected $fillable = ['size_id', 'store_website_id'];

    public function storeWebsite()
    {
        return $this->hasOne(\App\StoreWebsite::class,'id','store_website_id');
    }
}
