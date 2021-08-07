<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ImQueue;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ColdLeadBroadcasts extends Model
{
    public function lead() {
        return $this->belongsToMany(ColdLeads::class, 'lead_broadcasts_lead', 'lead_broadcast_id', 'lead_id', 'id', 'id');
    }

    public function imQueueBroadcast()
    {
    	return $this->hasMany(ImQueue::class,'broadcast_id','id');
    }

    public function imQueueBroadcastPending()
    {
    	return $this->hasMany(ImQueue::class,'broadcast_id','id')->whereNull('sent_at');
    }

    public function imQueueBroadcastSend()
    {
    	return $this->hasMany(ImQueue::class,'broadcast_id','id')->whereNotNull('sent_at');
    }
}
