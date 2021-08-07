<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\HashTag;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class Affiliates extends Model
{
    /**
     * @var string
     * @SWG\Property(property="location",type="string")
     * @SWG\Property(property="hashtag_id",type="integer")
     * @SWG\Property(property="caption",type="string")
     * @SWG\Property(property="posted_at",type="datetime")
     * @SWG\Property(property="source",type="string")
     * @SWG\Property(property="address",type="textarea")
     * @SWG\Property(property="facebook",type="string")
     * @SWG\Property(property="facebook_followers",type="string")
     * @SWG\Property(property="instagram",type="string")
     * @SWG\Property(property="instagram_followers",type="string")
     * @SWG\Property(property="twitter",type="string")
     * @SWG\Property(property="twitter_followers",type="string")
     * @SWG\Property(property="youtube",type="string")
     * @SWG\Property(property="youtube_followers",type="string")
     * @SWG\Property(property="linkedin",type="string")
     * @SWG\Property(property="linkedin_followers",type="string")
     * @SWG\Property(property="pinterest",type="string")
     * @SWG\Property(property="pinterest_followers",type="string")
     * @SWG\Property(property="phone",type="string")
     * @SWG\Property(property="emailaddress",type="email")
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="is_flagged",type="boolean")
     * @SWG\Property(property="first_name",type="string")
     * @SWG\Property(property="last_name",type="string")
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="website_name",type="string")
     * @SWG\Property(property="unique_visitors_per_month",type="string")
     * @SWG\Property(property="page_views_per_month",type="string")
     * @SWG\Property(property="worked_on",type="string")
     * @SWG\Property(property="city",type="string")
     * @SWG\Property(property="postcode",type="string")
     * @SWG\Property(property="country",type="string")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="store_website_id",type="integer")
     */
    protected $fillable = [
        'location',
        'hashtag_id',
        'location',
        'caption',
        'posted_at',
        'source',
        'address',
        'facebook',
        'facebook_followers',
        'instagram',
        'instagram_followers',
        'twitter',
        'twitter_followers',
        'youtube',
        'youtube_followers',
        'linkedin',
        'linkedin_followers',
        'pinterest',
        'pinterest_followers',
        'phone',
        'emailaddress',
        'title',
        'is_flagged',
        'first_name',
        'last_name',
        'url',
        'website_name',
        'unique_visitors_per_month',
        'page_views_per_month',
        'worked_on',
        'city',
        'postcode',
        'country',
        'type',
        'store_website_id'
    ];

    public function hashTags()
    {
        return $this->belongsTo(HashTag::class, 'hashtag_id');
    }
}
