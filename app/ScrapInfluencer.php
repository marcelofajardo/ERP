<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrapInfluencer extends Model
{

    /**
     * @var string
     * @SWG\Property(property="post_id",type="integer")
     * @SWG\Property(property="post_caption",type="string")
     * @SWG\Property(property="instagram_user_id",type="integer")
     * @SWG\Property(property="post_media_type",type="string")
     * @SWG\Property(property="post_code",type="string")
     * @SWG\Property(property="post_location",type="string")
     * @SWG\Property(property="post_hashtag_id",type="interger")
     * @SWG\Property(property="post_likes",type="string")
     * @SWG\Property(property="post_comments_count",type="string")
     * @SWG\Property(property="post_media_url",type="string")
     * @SWG\Property(property="posted_at",type="datetime")
     * @SWG\Property(property="comment_user_id",type="integer")
     * @SWG\Property(property="comment_user_full_name",type="string")
     * @SWG\Property(property="comment_username",type="string")
     * @SWG\Property(property="instagram_post_id",type="integer")
     * @SWG\Property(property="comment_id",type="integer")
     * @SWG\Property(property="comment",type="string")
     * @SWG\Property(property="comment_profile_pic_url",type="string")
     * @SWG\Property(property="comment_posted_at",type="datetime")
     */

    protected $fillable = [
        'post_id',
        'post_caption',
        'instagram_user_id',
        'post_media_type',
        'post_code',
        'post_location',
        'post_hashtag_id',
        'post_likes',
        'post_comments_count',
        'post_media_url',
        'posted_at',
        'comment_user_id',
        'comment_user_full_name',
        'comment_username',
        'instagram_post_id',
        'comment_id',
        'comment',
        'comment_profile_pic_url',
        'comment_posted_at',
        'profile_pic',
        'friends',
        'cover_photo',
        'interests',
        'work_at',
    ];
}
