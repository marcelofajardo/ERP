<?php

namespace App;

use Plank\Mediable\Mediable;
use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatMessagesQuickData extends Model
{
	
    protected $table = "chat_messages_quick_datas";
    
    /**
     * @var string
      * @SWG\Property(property="chat_messages_quick_datas",type="string")
      * @SWG\Property(property="model",type="string")
     * @SWG\Property(property="model_id",type="integer")
     * @SWG\Property(property="last_unread_message",type="string")
     * @SWG\Property(property="last_communicated_messagelast_communicated_message",type="string")
     * @SWG\Property(property="last_communicated_message_at",type="string")
     * @SWG\Property(property="last_unread_message_at",type="datetime")
       * @SWG\Property(property="last_unread_message_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */

    protected $fillable = ['model', 'model_id', 'last_unread_message', 'last_unread_message_at', 'last_communicated_messagelast_communicated_message', 'last_communicated_message_at','last_unread_message_id','last_communicated_message_id'];

   
    protected $dates = ['created_at', 'updated_at'];

}
