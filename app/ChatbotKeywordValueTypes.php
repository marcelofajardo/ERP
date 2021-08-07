<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotKeywordValueTypes extends Model
{
    public $timestamps = false;
    /**
     * @var string
   * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="chatbot_keyword_value_id",type="integer")

     */
    protected $fillable = [
        'type', 'chatbot_keyword_value_id'
    ];
}
