<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductOrderExcelFileVersion extends Model
{
    //
    protected $fillable = [
        'excel_id', 'file_name', 'file_version'
    ];
}
