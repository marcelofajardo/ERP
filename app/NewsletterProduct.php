<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;


class NewsletterProduct extends Model
{
	     /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="newsletter_id",type="integer")
     */
    protected $fillable = [
       'product_id' , 'newsletter_id'
    ];

    public function product()
    {
      return $this->hasOne(\App\Product::class, 'id' , 'product_id');
    }
}
