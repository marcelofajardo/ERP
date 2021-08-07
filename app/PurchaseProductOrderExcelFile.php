<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductOrderExcelFile extends Model
{
    //
    protected $fillable = [
        'excel_path', 'order_id', 'supplier_id', 'created_by'
    ];
}
