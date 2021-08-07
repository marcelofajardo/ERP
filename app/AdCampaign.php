<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AdCampaign extends Model
{
	/**
     * @var string     
     * @SWG\Property(property="goal",type="string")
     * @SWG\Property(property="type",type="text")
     * @SWG\Property(property="campaign_name",type="string")
     * @SWG\Property(property="data",type="text")
     * @SWG\Property(property="campaign_budget_id",type="integer")
     * @SWG\Property(property="campaign_id",type="integer")
     * @SWG\Property(property="campaign_response",type="string")
     */
    protected $fillable = [
        'goal', 'type', 'campaign_name', 'data','campaign_budget_id','campaign_id','campaign_response',
    ];
}
