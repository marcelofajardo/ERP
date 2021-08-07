<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class RejectedImages extends Model
{
     /**
     * @var string
     * @SWG\Property(property="website_id",type="integer")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="status",type="string")
     */
    protected $fillable = [
        'website_id', 'product_id', 'status'
    ];

}
