<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotKeyword extends Model
{
	/**
     * @var string
 * @SWG\Property(property="keyword",type="string")
     * @SWG\Property(property="workspace_id",type="integer")
     */
    protected $fillable = [
        'keyword', 'workspace_id',
    ];

    public function chatbotKeywordValues()
    {
        return $this->hasMany("App\ChatbotKeywordValue", "chatbot_keyword_id", "id");
    }
}
