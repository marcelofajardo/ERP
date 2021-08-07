<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class GoogleAd extends Model
{
	   /**
     * @var string
     * @SWG\Property(property="googleads",type="string")
       * @SWG\Property(property="adgroup_google_campaign_id",type="integer")
     * @SWG\Property(property="google_adgroup_id",type="integer")
     * @SWG\Property(property="google_ad_id",type="integer")
     * @SWG\Property(property="headline1",type="string")
     * @SWG\Property(property="headline2",type="string")
     * @SWG\Property(property="headline3",type="string")
     * @SWG\Property(property="description1",type="string")
     * @SWG\Property(property="description2",type="string")
     * @SWG\Property(property="final_url",type="string")
     * @SWG\Property(property="path1",type="string")
     * @SWG\Property(property="path2",type="string")
     * @SWG\Property(property="ads_resposne",type="string")
     * @SWG\Property(property="status",type="string")
   
     */
    protected $table='googleads';
    protected $fillable=['adgroup_google_campaign_id','google_adgroup_id','google_ad_id','headline1','headline2','headline3','description1','description2','final_url','path1','path2','ads_resposne','status'];
}
