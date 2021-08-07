<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;
use App\ChatMessage;

class ImQueue extends Model
{
		   /**
     * @var string
          * @SWG\Property(property="id",type="integer")
     * @SWG\Property(property="im_client",type="string")
     * @SWG\Property(property="number_to",type="string")
     * @SWG\Property(property="number_from",type="string")
     * @SWG\Property(property="text",type="string")
     * @SWG\Property(property="image",type="string")
     * @SWG\Property(property="priority",type="string")
     * @SWG\Property(property="send_after",type="datetime")
     * @SWG\Property(property="sent_at",type="datetime")
     * @SWG\Property(property="marketing_message_type_id",type="integer")
     * @SWG\Property(property="broadcast_id",type="integer")


     */
    protected $fillable = ['id', 'im_client','number_to','number_from','text','image','priority','send_after','sent_at','marketing_message_type_id','broadcast_id'];

    public function marketingMessageTypes()
    {
        return $this->hasOne(MarketingMessageType::class,'id','marketing_message_type_id');
    }

}
