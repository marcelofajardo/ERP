<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class PriceOverride extends Model
{
     /**
     * @var string
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="brand_segment",type="string")
     * @SWG\Property(property="country_group_id",type="integer")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="calculated",type="string")
     * @SWG\Property(property="value",type="string")
     * @SWG\Property(property="country_code",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */

    protected $fillable = [
        'store_website_id', 
        'brand_id', 
        'brand_segment', 
        'country_group_id', 
        'category_id', 
        'type', 
        'calculated', 
        'value', 
        'country_code', 
        'created_at', 
        'updated_at'
    ];

    public function brand()
    {
        return $this->hasOne(App\Brand::class, "id", "brand_id");
    }

    public function category()
    {
        return $this->hasOne(App\Categor::class, "id", "category_id");
    }

    public function country()
    {
        return $this->hasOne(App\SimplyDutyCoutry::class, "country_code", "country_code");
    }

    public function countryGroup()
    {
        return $this->hasOne(App\CountryGroup::class, "id", "country_group_id");
    }
}
