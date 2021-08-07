<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class InstagramAutomatedMessages extends Model
{
    public function account() {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function target() {
        return $this->belongsTo(Influencers::class, 'target_id', 'id');
    }
}
