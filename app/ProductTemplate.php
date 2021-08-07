<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Plank\Mediable\Mediable;

class ProductTemplate extends Model
{
         /**
     * @var string
     * @SWG\Property(property="template_no",type="string")
     * @SWG\Property(property="product_title",type="string")
     * @SWG\Property(property="text",type="string")
     * @SWG\Property(property="font_style",type="string")
     * @SWG\Property(property="font_size",type="string")
     * @SWG\Property(property="background_color",type="string")
     * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="price",type="float")
     * @SWG\Property(property="discounted_price",type="float")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="is_processed",type="integer")
     * @SWG\Property(property="store_website_id",type="integer")
     */
    use Mediable;
    protected $fillable = [
        'template_no',
        'product_title',
        'text',
        'font_style',
        'font_size',
        'background_color',
        'color',
        'brand_id',
        'currency',
        'price',
        'discounted_price',
        'product_id',
        'is_processed',
        'template_status',
        'image_url',
        'uid',
        'store_website_id'
    ];

    public function brand()
    {
        return $this->hasOne("\App\Brand", "id", "brand_id");
    }

    public function category()
    {
        return $this->hasOne("\App\Category", "id", "category_id");
    }

    public function template()
    {
        return $this->hasOne("\App\Template", "id", "template_no");
    }

    public function storeWebsite()
    {
        return $this->hasOne(\App\StoreWebsite::class, 'id', 'store_website_id');
    }

}
