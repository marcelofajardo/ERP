<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class InfluencersDM extends Model
{
    public function message() {
        return $this->belongsTo(InstagramAutomatedMessages::class,  'message_id', 'id');
    }

    public function influencer() {
        return $this->belongsTo(Influencers::class, 'influencer_id', 'id');
    }
}
