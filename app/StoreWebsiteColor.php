<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;


class StoreWebsiteColor extends Model
{
		/**
     * @var string
      * @SWG\Property(property="id",type="integer")
      * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="store_color",type="string")
     * @SWG\Property(property="erp_color",type="string")
     */

    public $fillable = [ 'id','store_website_id', 'store_color', 'erp_color'];

    /**
     * Get store categories
     */
    public function storeWebsite()
    {
        return $this->hasOne(\App\StoreWebsite::class, 'id','store_website_id');
    }

}
