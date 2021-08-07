<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class LeadList extends Model
{
    /**
     * @var string
     * @SWG\Property(property="erp_lead_id",type="integer")
     * @SWG\Property(property="list_id",type="integer")
     * @SWG\Property(property="created_at",type="datetime")

     */
	protected $fillable = ['erp_lead_id', 'list_idlist_id', 'created_at'];
}
