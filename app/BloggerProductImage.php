<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BloggerProductImage extends Model
{
	   /**
     * @var string
     * @SWG\Property(property="file_name",type="string")
     * @SWG\Property(property="blogger_product_id",type="integer")
     * @SWG\Property(property="other",type="string")
     */
    protected $fillable = ['file_name','blogger_product_id','other'];
}
