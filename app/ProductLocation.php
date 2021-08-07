<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProductLocation extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="product_location",type="string")
     * @SWG\Property(property="name",type="string")
        */
    public $table = "product_location";
    protected $fillable = [
        'name',
    ];
}
