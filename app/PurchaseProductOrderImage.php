<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductOrderImage extends Model
{
    //
    protected $fillable = [
        'order_product_id', 'order_id', 'file_name', 'user_id'
    ];
}
