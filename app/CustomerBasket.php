<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="CustomerBasket"))
 */
use Illuminate\Database\Eloquent\Model;

class CustomerBasket extends Model
{
    //

    /**
     * @var string

     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="customer_name",type="string")
     * @SWG\Property(property="customer_email",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="language_code",type="string")
     */
    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_email',
        'store_website_id',
        'language_code',
    ];

    public function basketProducts()
    {
        return $this->hasMany(\App\CustomerBasketProduct::class,'customer_basket_id','id');
    }

    public function storeWebsite()
    {
        return $this->hasOne(\App\StoreWebsite::class, 'id','store_website_id');
    }

}
