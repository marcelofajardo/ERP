<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierTranslateHistory extends Model
{
    protected $table = 'supplier_translate_history';
    

    protected $fillable = [ 
        'msg_id',
        'supplier_id',
        'original_msg',
        'translate_msg',
        'error_log'
    ];
}
