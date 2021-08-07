<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotQuestionErrorLog extends Model
{
	/**
     * @var string
      * @SWG\Property(property="chat_bot_error_logs",type="string")
     */
    protected $table = "chat_bot_error_logs";
 
}
