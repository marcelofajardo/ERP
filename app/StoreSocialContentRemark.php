<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreSocialContentRemark extends Model
{
	/**
     * @var string
      * @SWG\Property(property="store_social_content_id",type="integer")
      * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="remarks",type="string")
     */
    protected $fillable = ['remarks', 'store_social_content_id', 'user_id'];
}
