<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class EmailRunHistories extends Model
{

	  /**
     * @var string
     * @SWG\Property(property="email_run_histories",type="string")
     * @SWG\Property(property="email_address_id",type="integer")
     * @SWG\Property(property="is_success",type="boolean")
     * @SWG\Property(property="message",type="string")
     */
    
    protected $table = 'email_run_histories';

    protected $fillable = [
        'email_address_id', 'is_success','message'
    ];

    
    public function email_address() {
        return $this->belongsTo(EmailAddress::class,'email_address_id','id');
    }
}
