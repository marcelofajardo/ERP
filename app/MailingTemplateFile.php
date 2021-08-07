<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class MailingTemplateFile extends Model
{
	/**
     * @var string
     * @SWG\Property(property="mailing_id",type="integer")
   * @SWG\Property(property="path",type="string")
     */
    protected $fillable = ['mailing_id', 'path'];
}
