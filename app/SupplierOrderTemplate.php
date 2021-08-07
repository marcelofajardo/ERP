<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierOrderTemplate extends Model
{
    //
    protected $fillable = [ 'supplier_id', 'template', 'created_by'];
}
