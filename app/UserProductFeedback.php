<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class UserProductFeedback extends Model
{
		     /**
     * @var string
      * @SWG\Property(property="content",type="string")

     */
    protected $casts = [
        'content' => 'array'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
