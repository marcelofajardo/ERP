<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotQuestionExample extends Model
{
    public $timestamps  = false;
    /**
     * @var string
      * @SWG\Property(property="question",type="string")
     * @SWG\Property(property="chatbot_question_id",type="integer")
        * @SWG\Property(property="types",type="string")
     */
    protected $fillable = [
        'question', 'chatbot_question_id','types',
    ];

    public function questionModal()
    {
        return $this->hasOne("\App\ChatbotQuestion", "id", "chatbot_question_id");
    }

    public function annotations()
    {
        return $this->hasMany("\App\ChatbotIntentsAnnotation", "question_example_id", "id");
    }

    public function highLightQuestion()
    {
        $getAllLengths = $this->annotations;
        $question = $this->question;
        $selectedAn         = [];
        if (!$getAllLengths->isEmpty()) {
            foreach ($getAllLengths as $lengths) {
                $selectedAn[$lengths->id] = substr($question,$lengths->start_char_range,$lengths->end_char_range);
            }
        }

        return $selectedAn;
    }

    public function chatbotKeywordValueTypes() {
        return $this->hasMany("App\ChatbotKeywordValueTypes", "chatbot_keyword_value_id", "id");
    }
}
