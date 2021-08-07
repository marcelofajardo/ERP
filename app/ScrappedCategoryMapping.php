<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScrappedCategoryMapping extends Model
{
    //
    protected $fillable = [
        'name',
        'category_id',
        'is_skip',
        'is_auto_fix',
        'is_auto_skip'
    ];

}
