<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class GoogleAdsCampaign extends Model
{
    /**
     * @var string
    
     * @SWG\Property(property="google_campaign_id",type="integer")
     * @SWG\Property(property="default_phone",type="integer")
     * @SWG\Property(property="campaign_name",type="string")
     * @SWG\Property(property="budget_amount",type="string")
     * @SWG\Property(property="start_date",type="datetime")
     * @SWG\Property(property="end_date",type="datetime")
     * @SWG\Property(property="budget_uniq_id",type="integer")

     * @SWG\Property(property="budget_id",type="integer")
     * @SWG\Property(property="merchant_id",type="integer")

     * @SWG\Property(property="sales_country",type="sting")
     * @SWG\Property(property="channel_type",type="sting")
     * @SWG\Property(property="channel_sub_type",type="sting")
     * @SWG\Property(property="bidding_strategy_type",type="sting")
     * @SWG\Property(property="target_cpa_value",type="sting")
     * @SWG\Property(property="target_roas_value",type="sting")
     * @SWG\Property(property="maximize_clicks",type="sting")
     * @SWG\Property(property="ad_rotation",type="sting")
     * @SWG\Property(property="campaign_response",type="sting")
     * @SWG\Property(property="status",type="sting")
     */
    protected $table    = 'googlecampaigns';
    
    protected $fillable = [
        'account_id',
        'google_campaign_id',
        'campaign_name',
        'budget_amount',
        'start_date',
        'end_date',
        'budget_uniq_id',
        'budget_id',
        'merchant_id',
        'sales_country',
        'channel_type',
        'channel_sub_type',
        'bidding_strategy_type',
        'target_cpa_value',
        'target_roas_value',
        'maximize_clicks',
        'ad_rotation',
        'campaign_response',
        'status',
    ];

    const CAHANNEL_TYPE = [
        "UNKNOWN"       => "Unknown",
        "SEARCH"        => "SEARCH",
        "DISPLAY"       => "DISPLAY",
        "SHOPPING"      => "SHOPPING",
        "MULTI_CHANNEL" => "MULTI_CHANNEL",
    ];

    const CAHANNEL_SUB_TYPE = [
        "UNKNOWN"                   => "Unknown",
        "SEARCH_MOBILE_APP"         => "Search mobile app",
        "DISPLAY_MOBILE_APP"        => "Display mobile app",
        "SEARCH_EXPRESS"            => "Search Express",
        "DISPLAY_EXPRESS"           => "Display Express",
        "UNIVERSAL_APP_CAMPAIGN"    => "Universal app campaign",
        "DISPLAY_SMART_CAMPAIGN"    => "Display smart campaign",
        "DISPLAY_GMAIL_AD"          => "Display gmail ad",
    ];

}
