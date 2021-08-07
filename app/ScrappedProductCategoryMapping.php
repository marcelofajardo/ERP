<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScrappedProductCategoryMapping extends Model
{
    //
    protected $fillable = [
        'product_id','category_mapping_id'
    ];
}
