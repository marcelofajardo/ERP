<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    public function account()
    {
        return $this->belongsTo('App\Account');
    }

}
