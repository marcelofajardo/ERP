<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProductVerifyingUser extends Model
{
     /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
   
     */

    protected $fillable = [
        'product_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

}
