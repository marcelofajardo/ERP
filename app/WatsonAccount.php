<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Library\Watson\Model as WatsonManager;
use App\ChatbotQuestion;
class WatsonAccount extends Model
{

        /**
     * @var string
     
      * @SWG\Property(property="store_website_id",type="integer")
      * @SWG\Property(property="work_space_id",type="integer")
      * @SWG\Property(property="assistant_id",type="integer")
      * @SWG\Property(property="url",type="string")
      * @SWG\Property(property="api_key",type="string")
      * @SWG\Property(property="watson_push",type="string")
      * @SWG\Property(property="is_active",type="integer")
     */
    protected $fillable = [
        'store_website_id',
        'api_key',
        'work_space_id',
        'assistant_id',
        'user_name',
        'password',
        'url',
        'is_active',
        'watson_push'
    ];

    public function storeWebsite()
    {
        return $this->belongsTo('App\StoreWebsite', 'store_website_id');
    }


    public static function getReply($message, $store_website_id = null) {
        if(!$store_website_id) {
            $account = self::where('is_active',1)->where('watson_push',1)->first();
        }
        else {
            $account = self::where('store_website_id',$store_website_id)->where('is_active',1)->first();
        }
        if($account) {
            $replies = ChatbotQuestion::
        join('chatbot_question_examples', 'chatbot_questions.id', 'chatbot_question_examples.chatbot_question_id')
            ->join('chatbot_questions_reply', 'chatbot_questions.id', 'chatbot_questions_reply.chatbot_question_id')
            ->where('chatbot_questions_reply.store_website_id', $account->store_website_id)
            ->select('chatbot_questions.*', 'chatbot_question_examples.question', 'chatbot_questions_reply.suggested_reply')->get();
        }
        else {
            $replies = ChatbotQuestion::
        join('chatbot_question_examples', 'chatbot_questions.id', 'chatbot_question_examples.chatbot_question_id')
            ->join('chatbot_questions_reply', 'chatbot_questions.id', 'chatbot_questions_reply.chatbot_question_id')
            ->select('chatbot_questions.*', 'chatbot_question_examples.question', 'chatbot_questions_reply.suggested_reply')->get();
        }

        
        $result = false;
        foreach ($replies as $reply) {
            if($message != ''){
                $keyword = $reply->question;
                if(($keyword == $message || preg_match("/{$keyword}/i", $message)) && $reply->suggested_reply) {
                    $result = $reply->suggested_reply;
                }
            }
        }
        if(!$result) {
            if($account) {
                $result = WatsonManager::getWatsonReply($message, $account->id);
            }
            else {
                $result = false;  
            }
        }
        return $result;
    }
}
