<?php

namespace App\Marketing;

use App\MarketingMessageType;
use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\ImQueue;
use Carbon\Carbon;

class WhatsappConfig extends Model
{
    protected $fillable = ['number', 'provider', 'username', 'password', 'is_customer_support','frequency','send_start','send_end','device_name','simcard_number','simcard_owner','payment','recharge_date','status','sim_card_type','instance_id' , 'token', 'is_default','store_website_id','default_for','is_use_own'];

    public function customer()
    {
        return $this->hasMany(Customer::class, 'broadcast_number', 'number');
    }

    public function customerAttachToday()
    {
        return $this->hasMany(Customer::class, 'broadcast_number', 'number')->whereDate('updated_at', Carbon::today());
    }

    public function imQueueCurrentDateMessageSend()
    {
    	return $this->hasMany(ImQueue::class,'number_from','number')->whereDate('sent_at', Carbon::today())->whereNotNull('sent_at');
    }

    public function  imQueueLastMessageSend()
    {
    	return $this->hasOne(ImQueue::class,'number_from','number')->latest();
    }

    public function imQueueLastMessagePending()
    {
        return $this->hasMany(ImQueue::class,'number_from','number')->whereNull('sent_at');
    }
    public function marketingMessageTypes()
    {
        return $this->hasOne(MarketingMessageType::class,'marketing_message_type_id','id');
    }
}
