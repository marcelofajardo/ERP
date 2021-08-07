<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class WebsiteProduct extends Model
{
	     /**
     * @var string
      * @SWG\Property(property="store_website_id",type="integer")
      * @SWG\Property(property="product_id",type="integer")
      * @SWG\Property(property="is_finished",type="boolean")
     */
	public $timestamps = false;
    //
	public $fillable = [
		"product_id",
		"store_website_id"
	];

}
