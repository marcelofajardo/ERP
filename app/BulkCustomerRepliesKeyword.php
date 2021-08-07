<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BulkCustomerRepliesKeyword extends Model
{
    public function customers() {
        return $this->belongsToMany(Customer::class, 'bulk_customer_replies_keyword_customer', 'keyword_id', 'customer_id', 'id', 'id');
    }

}
