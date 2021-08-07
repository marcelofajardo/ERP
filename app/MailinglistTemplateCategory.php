<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class MailinglistTemplateCategory extends Model
{
	/**
     * @var string
     * @SWG\Property(property="mailinglist_template_categories",type="string")
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="user_id",type="integer")

     */

    protected $table  = 'mailinglist_template_categories';

    protected $fillable = [
        'title', 'user_id',
    ];

}
