<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="sales_item",type="string")
     * @SWG\Property(property="images",type="string")
     * @SWG\Property(property="sizes",type="string")
     * @SWG\Property(property="category",type="string")
     */
    protected $table = 'sales_item';

    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'category' => 'array'
    ];
}
