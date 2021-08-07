<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class MailingRemark extends Model
{
	/**
     * @var string
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="user_id",type="integer")
   * @SWG\Property(property="text",type="string")
     * @SWG\Property(property="user_name",type="string")
     */

    protected $fillable = ['customer_id', 'user_id', 'text', 'user_name'];

}
