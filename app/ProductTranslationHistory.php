<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ProductTranslationHistory extends Model
{
    protected $guarded = [];

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}
