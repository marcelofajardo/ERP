<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductOrderLog extends Model
{
    //
    protected $fillable = [
        'purchase_product_order_id', 'order_products_id', 'header_name', 'replace_from', 'replace_to','created_by'
    ];
}
