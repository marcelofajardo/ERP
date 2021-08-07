<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ReferralProgram extends Model
{
	/**
     * @var string
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="uri",type="string")
     * @SWG\Property(property="credit",type="string")
     * @SWG\Property(property="currency",type="string")
     * @SWG\Property(property="lifetime_minutes",type="string")
     * @SWG\Property(property="store_website_id",type="interger")
     */
    protected $fillable = ['name','uri','credit','currency','lifetime_minutes','store_website_id'];
}
