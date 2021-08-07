<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ReferFriend extends Model
{  /**
     * @var string
     * @SWG\Property(property="referrer_first_name",type="string")
     * @SWG\Property(property="referrer_last_name",type="string")
     * @SWG\Property(property="referrer_email",type="string")
     * @SWG\Property(property="referrer_phone",type="string")
     * @SWG\Property(property="referee_first_name",type="string")
     * @SWG\Property(property="referee_email",type="string")
     * @SWG\Property(property="referee_last_name",type="string")
     * @SWG\Property(property="referee_phone",type="string")
     * @SWG\Property(property="website",type="string")
     * @SWG\Property(property="store_website_id",type="interger")
     */
    protected $fillable = [
    'referrer_first_name',
    'referrer_last_name',
    'referrer_email',
    'referrer_phone',
    'referee_first_name',
    'referee_last_name',
    'referee_email',
    'referee_phone',
    'website',
    'store_website_id'
    ];
    protected $table='refer_friend';
}
