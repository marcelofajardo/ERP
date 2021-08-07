<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
	    /**
     * @var string
     * @SWG\Property(property="sender_name",type="string")
     * @SWG\Property(property="sender_email",type="string")
     * @SWG\Property(property="receiver_name",type="string")
     * @SWG\Property(property="receiver_email",type="string")
     * @SWG\Property(property="gift_card_coupon_code",type="string")
     * @SWG\Property(property="gift_card_amount",type="string")
     * @SWG\Property(property="gift_card_message",type="string")
     * @SWG\Property(property="expiry_date",type="datetime")
     * @SWG\Property(property="store_website_id",type="integer")
     */
    protected $fillable = ['sender_name','sender_email','receiver_name','receiver_email','gift_card_coupon_code','gift_card_description','gift_card_amount','gift_card_message','expiry_date','store_website_id'];
}
