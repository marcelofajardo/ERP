<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrappedFacebookUser extends Model
{
	/**
     * @var string
     * @SWG\Property(property="url",type="string")
     * @SWG\Property(property="owner",type="string")
     * @SWG\Property(property="bio",type="string")
     * @SWG\Property(property="keyword",type="string")
     */
     protected $fillable = ['url','owner','bio','keyword'];
}
