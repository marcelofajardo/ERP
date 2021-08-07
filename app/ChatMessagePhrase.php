<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatMessagePhrase extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    /**
     * @var string
    * @SWG\Property(property="phrase",type="string")
     * @SWG\Property(property="word_id",type="integer")
     * @SWG\Property(property="total",type="string")
     * @SWG\Property(property="chat_id",type="integer")
     * @SWG\Property(property="deleted_at",type="datetime")
     * @SWG\Property(property="deleted_by",type="integer")
     */

    protected $fillable = [
        'phrase', 'total', 'word_id', 'chat_id','deleted_at','deleted_by'
    ];
}
