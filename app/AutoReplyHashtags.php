<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AutoReplyHashtags extends Model
{
    public function comments() {
        return $this->hasMany(AutoCommentHistory::class, 'auto_reply_hashtag_id', 'id');
    }
}
