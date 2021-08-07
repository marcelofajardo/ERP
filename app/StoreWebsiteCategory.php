<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteCategory extends Model
{
		/**
     * @var string
      * @SWG\Property(property="category_id",type="integer")
      * @SWG\Property(property="remote_id",type="integer")
     * @SWG\Property(property="category_name",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'category_id', 'remote_id', 'store_website_id', 'created_at', 'updated_at', 'category_name'
    ];
}
