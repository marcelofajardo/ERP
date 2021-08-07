<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class Influencers extends Model
{
    public function message() {
        return $this->hasOne(InfluencersDM::class, 'influencer_id', 'id');
    }
}
