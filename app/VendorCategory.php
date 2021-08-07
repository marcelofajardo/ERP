<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
	     /**
     * @var string
      * @SWG\Property(property="title",type="string")
   

     */
  protected $fillable = [
    'title'
  ];
}
