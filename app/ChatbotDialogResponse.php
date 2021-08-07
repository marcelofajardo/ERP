<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotDialogResponse extends Model
{
    public $timestamps = false;
    /**      
     * @var string
     * @SWG\Property(property="response_type",type="string")
     * @SWG\Property(property="value",type="string")
     * @SWG\Property(property="message_to_human_agent",type="string")
     * @SWG\Property(property="chatbot_dialog_id",type="integer")
     */
    protected $fillable = [
        'response_type', 'value', 'message_to_human_agent', 'chatbot_dialog_id',
    ];

    public function dialog(){
        return $this->belongsTo(ChatbotDialog::class, 'chatbot_dialog_id', 'id');
    }
    public function storeWebsite(){
        return $this->belongsTo(StoreWebsite::class, 'store_website_id', 'id');
    }
}
