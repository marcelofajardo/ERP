<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierOrderInquiryData extends Model
{
    //
    protected $fillable = [ 'supplier_id', 'product_id', 'type', 'count_number' ];
}
