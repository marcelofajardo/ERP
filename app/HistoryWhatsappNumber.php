<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class HistoryWhatsappNumber extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="history_whatsapp_number",type="string")
     * @SWG\Property(property="date_time",type="datetime")
     * @SWG\Property(property="object_id",type="integer")
     * @SWG\Property(property="old_number",type="integer")
     * @SWG\Property(property="new_number",type="integer")
     */
    public $timestamps = false;
    public $table = "history_whatsapp_number";
    //
    protected $fillable = ['date_time', 'object', 'object_id', 'old_number', 'new_number'];
}
