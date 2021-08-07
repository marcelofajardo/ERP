<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class HashtagPosts extends Model
{
    public function comments() {
        return $this->hasMany(HashtagPostComment::class, 'hashtag_post_id', 'id');
    }

    public function likes_data() {
        return $this->hasMany(HashtagPostLikes::class, 'hashtag_post_id', 'id');
    }

}
