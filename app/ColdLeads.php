<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ColdLeads extends Model
{
    public function threads() {
        return $this->hasOne(InstagramThread::class, 'cold_lead_id', 'id');
    }

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function whatsapp() {
        return $this->belongsTo(Customer::class, 'platform_id', 'phone');
    }
}
