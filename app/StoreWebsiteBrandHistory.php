<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteBrandHistory extends Model
{
	/**
     * @var string
      * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'brand_id','store_website_id','type','message','created_by','created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->hasOne(\App\User::class,'id','created_by');
    }

}
