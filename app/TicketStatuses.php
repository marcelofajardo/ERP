<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TicketStatuses extends Model
{
	 /**
     * @var string
      * @SWG\Property(property="ticket_statuses",type="string")
      * @SWG\Property(property="name",type="string")
 
     */
     protected $table = 'ticket_statuses';
     protected $fillable = ['name'];
}
