<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotDialog extends Model
{
        /**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="parent_id",type="integer")
     * @SWG\Property(property="match_condition",type="string")
     * @SWG\Property(property="workspace_id",type="integer")
     * @SWG\Property(property="previous_sibling",type="string")
     * @SWG\Property(property="metadata",type="string")
     */
    protected $fillable = [
        'name', 'title', 'parent_id', 'match_condition', 'workspace_id', 'previous_sibling', 'metadata','response_type','dialog_type'
    ];

    public function response()
    {
        return $this->hasMany("App\ChatbotDialogResponse", "chatbot_dialog_id", "id");
    }

    public function parentResponse()
    {
        return $this->hasMany("App\ChatbotDialog", "parent_id", "id");
    }

    public function previous()
    {
        return $this->hasOne("App\ChatbotDialog", "id", "previous_sibling");
    }

    public function parent()
    {
        return $this->hasOne("App\ChatbotDialog", "id", "parent_id");
    }

    public function singleResponse()
    {
        return $this->hasOne("App\ChatbotDialogResponse", "chatbot_dialog_id", "id");
    }

    public function getPreviousSiblingName()
    {
        return ($this->previous) ? $this->previous->name : null;
    }

    public function getParentName()
    {
        return ($this->parent) ? $this->parent->name : null;
    }

    public function multipleCondition()
    {
        return $this->hasMany("App\ChatbotDialog", "parent_id", "id");
    }

    public static function allSuggestedOptions()
    {
        $question = ChatbotQuestion::where('keyword_or_question','intent')->select(\DB::raw("concat('#','',value) as value"))->get()->pluck("value", "value")->toArray();
        $keywords = ChatbotQuestion::where('keyword_or_question','entity')->select(\DB::raw("concat('@','',value) as value"))->get()->pluck("value", "value")->toArray();

        // $keywords = ChatbotKeyword::select(\DB::raw("concat('@','',keyword) as keyword"))->get()->pluck("keyword", "keyword")->toArray();
        return $question + $keywords;
    }

}
