<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotReply extends Model
{

   /**
     * @var string
     * @SWG\Property(property="question",type="string")
     * @SWG\Property(property="reply",type="string")
     * @SWG\Property(property="chat_id",type="integer")
     * @SWG\Property(property="replied_chat_id",type="integer")
  	 * @SWG\Property(property="answer",type="string")
     * @SWG\Property(property="reply_from",type="string")
     * @SWG\Property(property="is_read",type="integer")
     */
    protected $fillable = [
        'question', 'reply', 'chat_id','replied_chat_id','answer','reply_from','is_read'
    ];
}
