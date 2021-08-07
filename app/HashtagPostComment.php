<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class HashtagPostComment extends Model
{
    public function post() {
        return $this->belongsTo(HashtagPosts::class, 'hashtag_post_id', 'id');
    }

}
