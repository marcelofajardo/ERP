<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpLeadStatus extends Model
{
   /**
     * @var string
     * @SWG\Property(property="erp_lead_status",type="string")
     * @SWG\Property(property="name",type="string")
 
     */

    public $table = "erp_lead_status";

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

}
