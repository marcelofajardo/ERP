<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemporaryCategoryUpdation extends Model
{
    //
    
    public $fillable = [ 'id','category_name', 'attribute_id','need_to_skip','user_id' ];
}
