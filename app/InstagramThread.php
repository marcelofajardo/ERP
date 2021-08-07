<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class InstagramThread extends Model
{
         /**
     * @var string
     * @SWG\Property(property="scrap_influencer_id",type="integer")

     */
    protected $fillable = ['scrap_influencer_id'];
    public function conversation() {
        return $this->hasMany(ChatMessage::class, 'unique_id', 'thread_id');
    }
    public function influencerConversation() {
        return $this->hasMany(ChatMessage::class, 'instagram_user_id', 'instagram_user_id');
    }
    public function lead() {
        return $this->belongsTo(ColdLeads::class, 'cold_lead_id', 'id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id')->whereNotNull('proxy');
    }

    public function instagramUser()
    {
        return $this->hasOne(InstagramUsersList::class, 'id', 'instagram_user_id');
    
    }

    public function erpUser()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'unique_id', 'thread_id')->orderBy('id','desc')->whereNotNull('message');
    }
    
}
