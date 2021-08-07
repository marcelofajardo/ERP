<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class HashtagPostHistory extends Model
{
    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function message() {
        return $this->belongsTo(InstagramAutomatedMessages::class, 'instagram_automated_message_id', 'id');
    }
}
