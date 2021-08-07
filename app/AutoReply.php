<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AutoReply extends Model
{
    /**
     * @var string
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="keyword",type="string")
     * @SWG\Property(property="reply",type="string")
     */
    protected $fillable = [
        'type', 'keyword', 'reply',
    ];

}
