<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class Contact extends Model
{

	/**
     * @var string
   
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="phone",type="string")
     * @SWG\Property(property="category",type="string")

     */
  protected $fillable = [
    'user_id', 'name', 'phone', 'category'
  ];
}
