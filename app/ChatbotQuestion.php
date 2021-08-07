<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotQuestion extends Model
{
   /**
     * @var string
     * @SWG\Property(property="value",type="string")
     * @SWG\Property(property="workspace_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     * @SWG\Property(property="keyword_or_question",type="string")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="sending_time",type="datetime")
     * @SWG\Property(property="repeat",type="string")
     * @SWG\Property(property="is_active",type="boolean")
     * @SWG\Property(property="erp_or_watson",type="string")
     * @SWG\Property(property="suggested_reply",type="string")
     * @SWG\Property(property="auto_approve",type="sting")
     * @SWG\Property(property="chat_message_id",type="integer")
     * @SWG\Property(property="task_category_id",type="integer")
     * @SWG\Property(property="assigned_to",type="sting")
     * @SWG\Property(property="task_description",type="sting")
     * @SWG\Property(property="task_type",type="sting")
     * @SWG\Property(property="repository_id",type="sting")
     * @SWG\Property(property="module_id",type="integer")
     * @SWG\Property(property="dynamic_reply",type="sting")
     */
    protected $fillable = [
        'value', 'workspace_id', 'created_at', 'updated_at', 'keyword_or_question', 'category_id',
        'sending_time','repeat','is_active','erp_or_watson','suggested_reply','auto_approve','chat_message_id','task_category_id','assigned_to','task_description','task_type','repository_id','module_id','dynamic_reply','watson_account_id', 'watson_status'
    ];

    public function chatbotQuestionExamples()
    {
    	return $this->hasMany("App\ChatbotQuestionExample","chatbot_question_id","id");
    }
    public function chatbotErrorLogs()
    {
    	return $this->hasMany("App\ChatbotErrorLog","chatbot_question_id","id");
    }
    
    public function chatbotKeywordValues()
    {
        return $this->hasMany("App\ChatbotKeywordValue", "chatbot_keyword_id", "id");
    }

    public function chatbotQuestionReplies()
    {
    	return $this->hasMany("App\ChatbotQuestionReply","chatbot_question_id","id");
    }
}
