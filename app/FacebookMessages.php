<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class FacebookMessages extends Model
{
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
