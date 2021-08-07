<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponCodeRules extends Model
{
    protected $table = "coupon_code_rules";

    public function store_labels(){
        return $this->hasMany('App\WebsiteStoreViewValue','rule_id');
    }
}
