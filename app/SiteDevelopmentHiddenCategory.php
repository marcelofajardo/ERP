<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SiteDevelopmentHiddenCategory extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'category_id', 'store_website_id','created_at', 'updated_at'
    ];

    public function storeWebsite()
    {
        return $this->hasOne('\App\StoreWebsite','store_website_id','id');
    }
}
