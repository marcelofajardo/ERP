<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandWithLogo extends Model
{
    //
    protected $fillable = ['brand_id','brand_logo_image_id','user_id'];
}
