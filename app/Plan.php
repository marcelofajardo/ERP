<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';

    function subList($id)
    {
    	return $this->where('parent_id',$id)->get();
    }
}
