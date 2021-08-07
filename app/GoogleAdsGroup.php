<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class GoogleAdsGroup extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="googleadsgroups",type="string")
     * @SWG\Property(property="adgroup_google_campaign_id",type="integer")
     * @SWG\Property(property="google_adgroup_id",type="integer")
     * @SWG\Property(property="ad_group_name",type="string")
     * @SWG\Property(property="bid",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="adgroup_response",type="string")
     */
    protected $table='googleadsgroups';
    protected $fillable=['adgroup_google_campaign_id','google_adgroup_id','ad_group_name','bid','status','adgroup_response'];
}
