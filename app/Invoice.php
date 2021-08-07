<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="invoice_number",type="integer")
      * @SWG\Property(property="invoice_date",type="datetime")
     */
    protected $fillable = [
        'invoice_number',
        'invoice_date'
    ];

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
