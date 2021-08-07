<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreWebsiteCategorySeosHistories extends Model
{
    protected $fillable = ['store_website_cate_seos_id','old_keywords','new_keywords','old_description','new_description','user_id'];
}
