<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductOrder extends Model
{
    //
    protected $fillable = [
        'product_id', 'order_id', 'supplier_id', 'created_by'
    ];
}
