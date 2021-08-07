<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SiteCroppedImages extends Model
{
	     /**
     * @var string
     * @SWG\Property(property="website_id",type="insteger")
     * @SWG\Property(property="product_id",type="insteger")
     */
    //
    protected $fillable = [
        'website_id', 'product_id',
    ];

}
