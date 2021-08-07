<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class MailinglistEmail extends Model
{
	  /**
     * @var string
     * @SWG\Property(property="mailinglist_id",type="integer")

     * @SWG\Property(property="template_id",type="integer")
       * @SWG\Property(property="html",type="string")
     * @SWG\Property(property="scheduled_date",type="datetime")
     * @SWG\Property(property="api_template_id",type="integer")
     * @SWG\Property(property="subject",type="string")
     * @SWG\Property(property="progress",type="string")
     */
    protected $fillable = [ 'mailinglist_id', 'template_id', 'html', 'scheduled_date','api_template_id', 'subject', 'progress'];

    public function audience()
    {
        return $this->hasOne(Mailinglist::class, 'id', 'mailinglist_id');
    }
    public function template()
    {
        return $this->hasOne(MailinglistTemplate::class, 'id', 'template_id');
    }
}
