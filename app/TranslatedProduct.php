<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TranslatedProduct extends Model
{
	/**
     * @var string
      * @SWG\Property(property="product_id",type="integer")
      * @SWG\Property(property="language_id",type="integer")
      * @SWG\Property(property="name",type="string")
      * @SWG\Property(property="description",type="string")
      * @SWG\Property(property="short_description",type="string")
 
     */

    protected $fillable = [
    'name', 'product_id','description','short_description','language_id'
  ];
}
