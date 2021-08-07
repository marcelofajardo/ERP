<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class InstaMessages extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="number",type="string")
     * @SWG\Property(property="message",type="string")
     * @SWG\Property(property="lead_id",type="integer")
     * @SWG\Property(property="order_id",type="integer")
     * @SWG\Property(property="approved",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="media_url",type="string")
     */
    protected $fillable = ['number', 'message', 'lead_id', 'order_id', 'approved', 'status', 'media_url'];
}
