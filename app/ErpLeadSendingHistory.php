<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ErpLeadSendingHistory extends Model
{

	 /**
     * @var string
     * @SWG\Property(property="erp_lead_sending_histories",type="string")
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="lead_id",type="integer")
     */
    protected $table = 'erp_lead_sending_histories';
    protected $fillable = [
        'product_id',
        'customer_id',
        'lead_id',
    ];
}
