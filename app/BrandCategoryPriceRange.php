<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BrandCategoryPriceRange extends Model
{
    protected $table = 'brand_category_price_range';
    /**
     * @var string
     * @SWG\Property(property="brand_category_price_range",type="integer")
     * @SWG\Property(property="brand_segment",type="string")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="min_price",type="integer")
     * @SWG\Property(property="max_price",type="integer")
     */
    protected $fillable = ['brand_segment', 'category_id', 'min_price', 'max_price'];
}
