<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use Google\AdsApi\AdWords\v201809\cm\BudgetService;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\v201809\cm\Budget;
use Google\AdsApi\AdWords\v201809\cm\AdvertisingChannelType;
use Google\AdsApi\AdWords\v201809\cm\AdvertisingChannelSubType;
use Google\AdsApi\AdWords\v201809\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201809\cm\BiddingStrategyType;
use Google\AdsApi\AdWords\v201809\cm\BudgetBudgetDeliveryMethod;
use Google\AdsApi\AdWords\v201809\cm\BudgetOperation;
use Google\AdsApi\AdWords\v201809\cm\Campaign;
use Google\AdsApi\AdWords\v201809\cm\CampaignOperation;
use Google\AdsApi\AdWords\v201809\cm\CampaignStatus;
use Google\AdsApi\AdWords\v201809\cm\FrequencyCap;
use Google\AdsApi\AdWords\v201809\cm\GeoTargetTypeSetting;
use Google\AdsApi\AdWords\v201809\cm\GeoTargetTypeSettingNegativeGeoTargetType;
use Google\AdsApi\AdWords\v201809\cm\GeoTargetTypeSettingPositiveGeoTargetType;
use Google\AdsApi\AdWords\v201809\cm\Level;
use Google\AdsApi\AdWords\v201809\cm\ManualCpcBiddingScheme;
use Google\AdsApi\AdWords\v201809\cm\TargetRoasBiddingScheme;
use Google\AdsApi\AdWords\v201809\cm\MaximizeConversionValueBiddingScheme;
use Google\AdsApi\AdWords\v201809\cm\TargetCpaBiddingScheme;
use Google\AdsApi\AdWords\v201809\cm\TargetSpendBiddingScheme;
use Google\AdsApi\AdWords\v201809\cm\ManualCpmBiddingScheme;
use Google\AdsApi\AdWords\v201809\cm\AdServingOptimizationStatus;
use Google\AdsApi\AdWords\v201809\cm\TargetingSetting;
use Google\AdsApi\AdWords\v201809\cm\TargetingSettingDetail;
use Google\AdsApi\AdWords\v201809\cm\ShoppingSetting;
use Google\AdsApi\AdWords\v201809\cm\Money;
use Google\AdsApi\AdWords\v201809\cm\NetworkSetting;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\cm\TimeUnit;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;
use Exception;

class GoogleCampaignsController extends Controller
{
    // show campaigns in main page
    public $exceptionError="Something went wrong";
    public function getstoragepath($account_id)
    {
        $result = \App\GoogleAdsAccount::find($account_id);
        if (isset($result->config_file_path) && $result->config_file_path!='' && \Storage::disk('adsapi')->exists($account_id . '/' . $result->config_file_path)) {
            $storagepath = \Storage::disk('adsapi')->url($account_id . '/' . $result->config_file_path);
            $storagepath = storage_path('app/adsapi/' . $account_id . '/' . $result->config_file_path);
            /* echo $storagepath; exit;
        echo storage_path('adsapi_php.ini'); exit; */
            /* echo '<pre>' . print_r($result, true) . '</pre>';
            die('developer working'); */
            return $storagepath;
        } else {
            return redirect()->to('/google-campaigns?account_id=null')->with('actError', 'Please add adspai_php.ini file');
        }
    }

    
    public function index(Request $request)
    {
        if($request->get('account_id')){
            $account_id=$request->get('account_id');
        }else{
            return redirect()->to('/google-campaigns?account_id=null')->with('actError', 'Please add adspai_php.ini file');
        }
        $storagepath = $this->getstoragepath($account_id);
        //echo $storagepath; exit;
        //echo $storagepath; exit;
        /* $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile($storagepath)
            ->build();

        $session = (new AdWordsSessionBuilder())
            ->fromFile($storagepath)
            ->withOAuth2Credential($oAuth2Credential)
            ->build(); */

        $query=\App\GoogleAdsCampaign::query();
        if($request->googlecampaign_id){
			$query = $query->where('google_campaign_id', $request->googlecampaign_id);
        }
        if($request->googlecampaign_name){
			$query = $query->where('campaign_name','LIKE','%'.$request->googlecampaign_name.'%');
        }

        if($request->googlecampaign_budget){
            $query = $query->where('budget_amount','LIKE','%'.$request->googlecampaign_budget.'%');
        }
        if($request->start_date){
            $query = $query->where('start_date','LIKE','%'.$request->start_date.'%');
        }
        if($request->end_date){
            $query = $query->where('end_date','LIKE','%'.$request->end_date.'%');
        }
        if($request->budget_uniq_id){
			$query = $query->where('budget_uniq_id', $request->budget_uniq_id);
        }

        if($request->campaign_status){
			$query = $query->where('status', $request->campaign_status);
        }
        
        
        $query->where('account_id',$account_id);
        $campInfo=$query->orderby('id','desc')->paginate(25)->appends(request()->except(['page']));
        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('googlecampaigns.partials.list-adscampaign', ['campaigns' => $campInfo])->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$campInfo->render(),
                'count' => $campInfo->total(),
            ], 200);
        }

        $totalEntries=$campInfo->count();
        
        return view('googlecampaigns.index', ['campaigns' => $campInfo, 'totalNumEntries' => $totalEntries]);
        /*$adWordsServices = new AdWordsServices();
         $campInfo = $this->getCampaigns($adWordsServices, $session);
        return view('googlecampaigns.index', ['campaigns' => $campInfo['campaigns'], 'totalNumEntries' => $campInfo['totalNumEntries']]); */
    }

    // get campaigns and total count
    public function getCampaigns(AdWordsServices $adWordsServices, AdWordsSession $session)
    {
        $campaignService = $adWordsServices->get($session, CampaignService::class);


        // Create selector.
        $campaignSelector = new Selector();
        $campaignSelector->setFields(['Id', 'Name', 'Status', 'BudgetId', 'BudgetName', 'Amount']);
        $campaignSelector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
        $campaignSelector->setPaging(new Paging(0, 10));

        $adGroupService = $adWordsServices->get($session, AdGroupService::class);


        // Create a selector to select all ad groups for the specified campaign.
        $groupSelector = new Selector();
        $groupSelector->setFields(['Id', 'Name']);
        $groupSelector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
        $groupSelector->setPaging(new Paging(0, 10));

        //        $budgetService = $adWordsServices->get($session, BudgetService::class);
        $totalNumEntries = 0;
        $campaigns = [];
        do {
            // Make the get request.
            $page = $campaignService->get($campaignSelector);
            // Display results.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                foreach ($page->getEntries() as $campaign) {
                    // getting campaign's adgroups
                    $groupSelector->setPredicates(
                        [new Predicate('CampaignId', PredicateOperator::IN, [$campaign->getId()])]
                    );
                    $adGroupPage = $adGroupService->get($groupSelector);
                    $adGroups = [];
                    if ($adGroupPage->getEntries() !== null) {
                        //                        $totalNumEntries = $page->getTotalNumEntries();
                        foreach ($adGroupPage->getEntries() as $adGroup) {
                            $adGroups[] = [
                                'adGroupId' => $adGroup->getId(),
                                'adGroupName' => $adGroup->getName()
                            ];
                        }
                    }
                    // getting budget
                    $campaignBudget = $campaign->getBudget();
                    // adding new campaign
                    $campaigns[] = [
                        "campaignId" => $campaign->getId(),
                        "campaignGroups" => $adGroups,
                        "name" => $campaign->getName(),
                        "status" => $campaign->getStatus(),
                        "budgetId" => $campaignBudget->getBudgetId(),
                        "budgetName" => $campaignBudget->getName(),
                        "budgetAmount" => $campaignBudget->getAmount()->getMicroAmount() / 1000000
                    ];
                }
            }

            // Advance the paging index.
            $campaignSelector->getPaging()->setStartIndex(
                $campaignSelector->getPaging()->getStartIndex() + 10
            );
        } while ($campaignSelector->getPaging()->getStartIndex() < $totalNumEntries);

        return [
            "totalNumEntries" => $totalNumEntries,
            "campaigns" => $campaigns
        ];
    }

    // go to create page
    public function createPage()
    {
        $biddingStrategyTypes=$this->getBiddingStrategyTypeArray();
        return view('googlecampaigns.create',compact('biddingStrategyTypes'));
    }

    // create campaign
    public function createCampaign(Request $request)
    {
        
        /*  $this->validate($request, [
			'campaignName' => 'required',
			'budgetAmount' => 'required|integer',
			'start_date' => 'required',
			'end_date' => 'required',
			'campaignStatus' => 'required',
        ]); */
        $this->validate($request, [
            'campaignName' => 'required|max:55',
            'budgetAmount' => 'required|max:55',
            'start_date' => 'required|max:15',
            'end_date' => 'required|max:15',
        ]);
        try{
        $campaignArray = array();
        $campaignStatusArr = ['UNKNOWN', 'ENABLED', 'PAUSED', 'REMOVED'];
        $budgetAmount = $request->budgetAmount * 1000000;
        $campaignName = $request->campaignName;
        $campaign_start_date = $request->start_date;
        $campaign_end_date = $request->end_date;
        $campaignStatus = $campaignStatusArr[$request->campaignStatus];
        
        //start creating array to store data into database
        $account_id = $request->account_id;
        $campaignArray['account_id'] = $account_id;
        $storagepath = $this->getstoragepath($account_id);
        $campaignArray['campaign_name'] = $campaignName;
        $campaignArray['budget_amount'] = $request->budgetAmount;
        $campaignArray['start_date'] = $campaign_start_date;
        $campaignArray['end_date'] = $campaign_end_date;
        $campaignArray['status'] = $campaignStatus;
        if($request->channel_type){
            $channel_type=$request->channel_type;
        }else{
            $channel_type='SEARCH';
        }
        $campaignArray['channel_type']=$channel_type;

        if($request->channel_sub_type){
            $channel_sub_type=$request->channel_sub_type;
        }else{
            $channel_sub_type='UNKNOWN';
        }
        $campaignArray['channel_sub_type']=$channel_sub_type;

        if($request->biddingStrategyType){
            $bidding_strategy_type=$request->biddingStrategyType;
        }else{
            $bidding_strategy_type='UNKNOWN';
        }
        $campaignArray['bidding_strategy_type']=$bidding_strategy_type;

        if($request->txt_target_cpa){
            $txt_target_cpa=$request->txt_target_cpa;
        }else{
            $txt_target_cpa=0.0;
        }
        $campaignArray['target_cpa_value']=$txt_target_cpa;
        
        if($request->txt_target_roas){
            $txt_target_roas=$request->txt_target_roas;
        }else{
            $txt_target_roas=0.0;
        }
        $campaignArray['target_roas_value']=$txt_target_roas;

        if($request->txt_maximize_clicks){
            $txt_maximize_clicks=$request->txt_maximize_clicks;
        }else{
            $txt_maximize_clicks='';
        }
        $campaignArray['maximize_clicks']=$txt_maximize_clicks;

        if($request->ad_rotation){
            $ad_rotation=$request->ad_rotation;
        }else{
            $ad_rotation='';
        }
        $campaignArray['ad_rotation']=$ad_rotation;
        
        if($request->tracking_template_url){
            $tracking_template_url=$request->tracking_template_url;
        }else{
            $tracking_template_url='';
        }
        $campaignArray['tracking_template_url']=$tracking_template_url;

        if($request->final_url_suffix){
            $final_url_suffix=$request->final_url_suffix;
        }else{
            $final_url_suffix='';
        }
        $campaignArray['final_url_suffix']=$final_url_suffix;

        if($request->merchant_id){
            $merchant_id=$request->merchant_id;
        }else{
            $merchant_id='';
        }
        $campaignArray['merchant_id']=$merchant_id;

        if($request->sales_country){
            $sales_country=$request->sales_country;
        }else{
            $sales_country='';
        }
        $campaignArray['sales_country']=$sales_country  ;
        
        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile($storagepath)
            ->build();

        $session = (new AdWordsSessionBuilder())
            ->fromFile($storagepath)
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        $adWordsServices = new AdWordsServices();

        $budgetService = $adWordsServices->get($session, BudgetService::class);

        // Create the shared budget (required).
        $uniq_id = uniqid();
        $campaignArray['budget_uniq_id'] = $uniq_id;
        $budget = new Budget();
        $budget->setName('Interplanetary Cruise Budget #' . $uniq_id);

        $money = new Money();
        $money->setMicroAmount($budgetAmount);
        $budget->setAmount($money);
        $budget->setDeliveryMethod(BudgetBudgetDeliveryMethod::STANDARD);

        $operations = [];

        // Create a budget operation.
        $operation = new BudgetOperation();
        $operation->setOperand($budget);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;

        // Create the budget on the server.
        $result = $budgetService->mutate($operations);

        $budget = $result->getValue()[0];
        
        $campaignService = $adWordsServices->get($session, CampaignService::class);

        $operations = [];

        // Create a campaign with required and optional settings.
        $campaign = new Campaign();
        $campaign->setName($campaignName);

        //$campaign->setAdvertisingChannelType(AdvertisingChannelType::SEARCH); //set channel Type
        $campaign->setAdvertisingChannelType($this->getAdvertisingChannelType($channel_type)); //set channel Type
        //$campaign->setAdvertisingChannelSubType($this->getAdvertisingChannelSubType($channel_sub_type));
        
        // Set shared budget (required).
        $campaignArray['budget_id'] = $budget->getBudgetId();
        $campaign->setBudget(new Budget());
        $campaign->getBudget()->setBudgetId($budget->getBudgetId());

        // Set bidding strategy (required).
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPC);
        $biddingScheme = new ManualCpcBiddingScheme();
        
        if($bidding_strategy_type=="MAXIMIZE_CONVERSIONS"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MAXIMIZE_CONVERSIONS);
            $biddingScheme = new MaximizeConversionValueBiddingScheme();
        }

        if($bidding_strategy_type=="MANUAL_CPM"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPM);
            $biddingScheme = new ManualCpmBiddingScheme();
        }

        if($bidding_strategy_type=="TARGET_SPEND"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_SPEND);
            $biddingScheme = new TargetSpendBiddingScheme();
            if(isset($maximize_clicks) && $maximize_clicks!=""){
                $biddingScheme->setSpendTarget($maximize_clicks);
            }
        }

        if($bidding_strategy_type=="TARGET_CPA"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_CPA);
            $biddingScheme = new TargetCpaBiddingScheme();
            $biddingScheme->setTargetCpa($txt_target_cpa);
            
        }

        if($bidding_strategy_type=="TARGET_ROAS"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_ROAS);
            $biddingScheme = new TargetRoasBiddingScheme();
            $biddingScheme->setTargetRoas($txt_target_roas);
        }
        
        // You can optionally provide a bidding scheme in place of the type.
        $biddingStrategyConfiguration->setBiddingScheme($biddingScheme);

        $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
        
         /* if($ad_rotation=="OPTIMIZE"){
            $campaign->setAdServingOptimizationStatus(AdServingOptimizationStatus::OPTIMIZE);
        }else if($ad_rotation=="ROTATE_INDEFINITELY"){
            $campaign->setAdServingOptimizationStatus(AdServingOptimizationStatus::ROTATE_INDEFINITELY);
        }else if($ad_rotation=="CONVERSION_OPTIMIZE" && ($channel_sub_type=="UNIVERSAL_APP_CAMPAIGN" ||($channel_type=="DISPLAY" && $channel_sub_type=="DISPLAY_SMART_CAMPAIGN") ) ){
            $campaign->setAdServingOptimizationStatus(AdServingOptimizationStatus::CONVERSION_OPTIMIZE);
        }else if($ad_rotation=="ROTATE"){
            $campaign->setAdServingOptimizationStatus(AdServingOptimizationStatus::ROTATE);
        }  */
        
        // Set network targeting (optional).
        $networkSetting = new NetworkSetting();
        if($channel_type=="SEARCH" || $channel_type=="MULTI_CHANNEL" || ($channel_type=="DISPLAY" && $channel_sub_type=="SHOPPING_GOAL_OPTIMIZED_ADS")){
            $networkSetting->setTargetGoogleSearch(true);
        }else if($channel_type=="MULTI_CHANNEL" || $channel_sub_type=="SHOPPING_GOAL_OPTIMIZED_ADS"){
            $networkSetting->setTargetSearchNetwork(true);
        }if($channel_type=="MULTI_CHANNEL" || $channel_type=="MULTI_CHANNEL" || ($channel_type=="DISPLAY" && $channel_sub_type=="DISPLAY_SMART_CAMPAIGN") || $channel_sub_type=="SHOPPING_GOAL_OPTIMIZED_ADS"){
            $networkSetting->setTargetContentNetwork(true);
        }if($channel_type=="MULTI_CHANNEL"){
            $networkSetting->setTargetPartnerSearchNetwork(false);
        }
        
        $campaign->setNetworkSetting($networkSetting);
        // Set network targeting (optional).
        /* $networkSetting = new NetworkSetting();
        $networkSetting->setTargetGoogleSearch(true);
        $networkSetting->setTargetSearchNetwork(true);
        $networkSetting->setTargetContentNetwork(true);
        $campaign->setNetworkSetting($networkSetting); */

        // Set additional settings (optional).
        // Recommendation: Set the campaign to PAUSED when creating it to stop
        // the ads from immediately serving. Set to ENABLED once you've added
        // targeting and the ads are ready to serve.
        $campaign->setStatus($campaignStatus); //CampaignStatus::ENABLED);
        // $campaign->setStartDate(date('Ymd', strtotime('+1 day')));
        // $campaign->setEndDate(date('Ymd', strtotime('+1 month')));
        $campaign->setStartDate($campaign_start_date);
        $campaign->setEndDate($campaign_end_date);
        if($tracking_template_url!="" && $channel_sub_type!="UNIVERSAL_APP_CAMPAIGN"){
            //$campaign->setTrackingUrlTemplate($tracking_template_url);
        } 
        
        if($final_url_suffix!="" && $channel_type!="MULTI_CHANNEL"){
            $campaign->setFinalUrlSuffix($final_url_suffix);
        } 
        // Set frequency cap (optional).
        $frequencyCap = new FrequencyCap();
        $frequencyCap->setImpressions(5);
        $frequencyCap->setTimeUnit(TimeUnit::DAY);
        $frequencyCap->setLevel(Level::ADGROUP);
        $campaign->setFrequencyCap($frequencyCap);

        // Set advanced location targeting settings (optional).
        $geoTargetTypeSetting = new GeoTargetTypeSetting();
        $geoTargetTypeSetting->setPositiveGeoTargetType(
            GeoTargetTypeSettingPositiveGeoTargetType::DONT_CARE
        );
        $geoTargetTypeSetting->setNegativeGeoTargetType(
            GeoTargetTypeSettingNegativeGeoTargetType::DONT_CARE
        );
        /* $targetSetting=new TargetingSetting();
        $targetSettingDetails=new TargetingSettingDetail();
        $criteriaTargetingSetting=$targetSettingDetails->setCriterionTypeGroup('erp.theluxuryunlimited.com');
        $targetSettingData=$targetSetting->setDetails([$criteriaTargetingSetting]); */
        $shoppingSetting=array();
        if($channel_type==="SHOPPING"){
            $shoppingSetting=new ShoppingSetting();
            $shoppingSetting->setMerchantId($merchant_id);
            $shoppingSetting->setSalesCountry($sales_country);
        }

        $campaign->setSettings([$geoTargetTypeSetting]);

        // Create a campaign operation and add it to the operations list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;
        
        // Create the campaign on the server
        $result = $campaignService->mutate($operations);
        $addedCampaign = $result->getValue();
        $addedCampaignId = $addedCampaign[0]->getId();
        $campaignArray['google_campaign_id'] = $addedCampaignId;
        $campaignArray['campaign_response'] = json_encode($addedCampaign);
        \App\GoogleAdsCampaign::create($campaignArray);
        /* return redirect()->route('googlecampaigns.index'); */
        return redirect()->to('google-campaigns?account_id='.$account_id)->with('actSuccess', 'Campaign created successfully');
    }
    catch(Exception $e) {
        //echo'<pre>'.print_r($e,true).'</pre>'; exit;
        return redirect()->to('google-campaigns/create?account_id='.$request->account_id)->with('actError', $e->getMessage());
      }
    }

    // go to update page
    public function updatePage(Request $request, $campaignId)
    {
        
        /* $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile(storage_path('adsapi_php.ini'))
            ->build();

        $session = (new AdWordsSessionBuilder())
            ->fromFile(storage_path('adsapi_php.ini'))
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        $adWordsServices = new AdWordsServices();

        $campaignService = $adWordsServices->get($session, CampaignService::class);

        // Create selector.
        $campaignSelector = new Selector();
        $campaignSelector->setFields(['Id', 'Name', 'Status']);
        //        $campaignSelector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
        //        $campaignSelector->setPaging(new Paging(0, 10));
        $campaignSelector->setPredicates(
            [new Predicate('Id', PredicateOperator::IN, [$campaignId])]
        );

        $page = $campaignService->get($campaignSelector);
        $pageEntries = $page->getEntries();

        if ($pageEntries !== null) {
            $campaign = $pageEntries[0];
        }
        $campaign = [
            "campaignId" => $campaign->getId(),
            //            "campaignGroups" => $adGroups,
            "name" => $campaign->getName(),
            "status" => $campaign->getStatus(),
            //                        "budgetId" => $campaignBudget->getBudgetId(),
            //                        "budgetName" => $campaignBudget->getName(),
            //                        "budgetAmount" => $campaignBudget->getAmount()
        ];
        // */
        $biddingStrategyTypes=$this->getBiddingStrategyTypeArray();
        $campaign=\App\GoogleAdsCampaign::where('google_campaign_id',$campaignId)->first();
        return view('googlecampaigns.update', ['campaign' => $campaign,'biddingStrategyTypes'=>$biddingStrategyTypes]);
    }

    // save campaign's changes
    public function updateCampaign(Request $request)
    {
        $this->validate($request, [
            'campaignName' => 'required|max:55',
            'budgetAmount' => 'required|max:55',
            'start_date' => 'required|max:15',
            'end_date' => 'required|max:15',
        ]);
        
        $campaignDetail=\App\GoogleAdsCampaign::where('google_campaign_id',
        $request->campaignId)->first();
        $account_id=$campaignDetail->account_id;
        try{
        $storagepath = $this->getstoragepath($account_id);
        $campaignStatusArr = ['UNKNOWN', 'ENABLED', 'PAUSED', 'REMOVED'];
        $campaignId = $request->campaignId;
        $campaignName = $request->campaignName;
        $campaignStatus = $campaignStatusArr[$request->campaignStatus];
        
        $campaignArray = array();
        $budgetAmount = $request->budgetAmount * 1000000;
        $campaign_start_date = $request->start_date;
        $campaign_end_date = $request->end_date;
       
        //start creating array to store data into database
        
        $campaignArray['campaign_name'] = $campaignName;
        $campaignArray['budget_amount'] = $request->budgetAmount;
        $campaignArray['start_date'] = $campaign_start_date;
        $campaignArray['end_date'] = $campaign_end_date;
        $campaignArray['status'] = $campaignStatus;

        if($request->biddingStrategyType){
            $bidding_strategy_type=$request->biddingStrategyType;
        }else{
            $bidding_strategy_type='UNKNOWN';
        }
        $campaignArray['bidding_strategy_type']=$bidding_strategy_type;


        if($request->txt_target_cpa){
            $txt_target_cpa=$request->txt_target_cpa;
        }else{
            $txt_target_cpa=0.0;
        }
        $campaignArray['target_cpa_value']=$txt_target_cpa;
        
        if($request->txt_target_roas){
            $txt_target_roas=$request->txt_target_roas;
        }else{
            $txt_target_roas=0.0;
        }
        $campaignArray['target_roas_value']=$txt_target_roas;

        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile($storagepath)
            ->build();

        $session = (new AdWordsSessionBuilder())
            ->fromFile($storagepath)
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        $adWordsServices = new AdWordsServices();

        $campaignService = $adWordsServices->get($session, CampaignService::class);

        // Create the shared budget (required).
        $uniq_id = uniqid();
        //$campaignArray['budget_uniq_id'] = $uniq_id;
        $budget = new Budget();
        $budget->setBudgetId($campaignDetail->budget_id);
        //$budget->setName('Interplanetary Cruise Budget #' . $uniq_id);

        $money = new Money();
        $money->setMicroAmount($budgetAmount);
        $budget->setAmount($money);
        $budget->setDeliveryMethod(BudgetBudgetDeliveryMethod::STANDARD);

        
        $operations = [];
        // Create a campaign with ... status.
        $campaign = new Campaign();
        $campaign->setId($campaignId);
        $campaign->setName($campaignName);
        $campaign->setStatus($campaignStatus);
        $campaign->setStartDate($campaign_start_date);
        $campaign->setEndDate($campaign_end_date);


        // Set bidding strategy (required).
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPC);
        $biddingScheme = new ManualCpcBiddingScheme();
        
        if($bidding_strategy_type=="MAXIMIZE_CONVERSIONS"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MAXIMIZE_CONVERSIONS);
            $biddingScheme = new MaximizeConversionValueBiddingScheme();
        }

        if($bidding_strategy_type=="MANUAL_CPM"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPM);
            $biddingScheme = new ManualCpmBiddingScheme();
        }

        if($bidding_strategy_type=="TARGET_SPEND"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_SPEND);
            $biddingScheme = new TargetSpendBiddingScheme();
            if(isset($maximize_clicks) && $maximize_clicks!=""){
                $biddingScheme->setSpendTarget($maximize_clicks);
            }
        }

        if($bidding_strategy_type=="TARGET_CPA"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_CPA);
            $biddingScheme = new TargetCpaBiddingScheme();
            $biddingScheme->setTargetCpa($txt_target_cpa);
            
        }

        if($bidding_strategy_type=="TARGET_ROAS"){
            $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_ROAS);
            $biddingScheme = new TargetRoasBiddingScheme();
            $biddingScheme->setTargetRoas($txt_target_roas);
        }
        
        // You can optionally provide a bidding scheme in place of the type.
        $biddingStrategyConfiguration->setBiddingScheme($biddingScheme);

        $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

        // Create a campaign operation and add it to the list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        // Update the campaign on the server.
        $result = $campaignService->mutate($operations);
        $addedCampaign = $result->getValue();
        $addedCampaignId = $addedCampaign[0]->getId();
        $campaignArray['google_campaign_id'] = $addedCampaignId;
        $campaignArray['campaign_response'] = json_encode($addedCampaign);
        \App\GoogleAdsCampaign::whereId($campaignDetail->id)->update($campaignArray);  
        //return redirect()->route('googlecampaigns.index');
        return redirect()->to('google-campaigns?account_id='.$account_id)->with('actSuccess', 'Campaign updated successfully');
        }
        catch(Exception $e) {
            return redirect()->to('google-campaigns/update/'.$request->campaignId.'?account_id='.$account_id)->with('actError', $e->getMessage());
          }
        
    }

    // delete campaign
    public function deleteCampaign(Request $request, $campaignId)
    {
        try{
        $account_id=$request->delete_account_id;
        $storagepath = $this->getstoragepath($account_id);
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adWordsServices = new AdWordsServices();

        $campaignService = $adWordsServices->get($session, CampaignService::class);

        $operations = [];
        // Create a campaign with REMOVED status.
        $campaign = new Campaign();
        $campaign->setId($campaignId);
        $campaign->setStatus(CampaignStatus::REMOVED);

        // Create a campaign operation and add it to the list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        // Remove the campaign on the server.
        $result = $campaignService->mutate($operations);
        //delete from database
        \App\GoogleAdsCampaign::where('account_id',$account_id)->where('google_campaign_id',$campaignId)->delete();
        /* return redirect()->route('googlecampaigns.index'); */
        return redirect()->to('google-campaigns?account_id='.$account_id)->with('actSuccess', 'Campaign deleted successfully');
        }
        catch(Exception $e) {
            return redirect()->to('google-campaigns?account_id='.$account_id)->with('actError', $this->exceptionError);
          }
    }

    //function to retrieve data from library 
    //get advertising channel type
    public function getAdvertisingChannelType($v){
        switch ($v) {
            case "SEARCH":
                return AdvertisingChannelType::SEARCH;
              break;

            case "DISPLAY":
                return AdvertisingChannelType::DISPLAY;
              break;

            case "SHOPPING":
                return AdvertisingChannelType::SHOPPING;
              break;

            case "MULTI_CHANNEL":
                return AdvertisingChannelType::MULTI_CHANNEL;
              break;
            
            case "UNKNOWN":
                return AdvertisingChannelType::UNKNOWN;
            break;

            default:
                return AdvertisingChannelType::SEARCH;
          }

    }

    //get advertising sub type
    public function getAdvertisingChannelSubType($v){
        switch ($v) {
            case "UNKNOWN":
                return AdvertisingChannelSubType::UNKNOWN;
              break;

            case "SEARCH_MOBILE_APP":
                return AdvertisingChannelSubType::SEARCH_MOBILE_APP;
              break;

            case "DISPLAY_MOBILE_APP":
                return AdvertisingChannelSubType::DISPLAY_MOBILE_APP;
              break;

            case "SEARCH_EXPRESS":
                return AdvertisingChannelSubType::SEARCH_EXPRESS;
              break;
            
            case "DISPLAY_EXPRESS":
                return AdvertisingChannelSubType::DISPLAY_EXPRESS;
            break;
            case "DISPLAY_SMART_CAMPAIGN":
                return AdvertisingChannelSubType::DISPLAY_SMART_CAMPAIGN;
            break;
            case "SHOPPING_GOAL_OPTIMIZED_ADS":
                return AdvertisingChannelSubType::SHOPPING_GOAL_OPTIMIZED_ADS;
            break;
            case "DISPLAY_GMAIL_AD":
                return AdvertisingChannelSubType::DISPLAY_GMAIL_AD;
            break;

            default:
                return AdvertisingChannelSubType::UNKNOWN;
          }

    }

    public function getBiddingStrategyTypeArray(){
        return ['MANUAL_CPC'=>'Manually set bids','MANUAL_CPM'=>'Viewable CPM','PAGE_ONE_PROMOTED'=>'Page one promoted','TARGET_SPEND'=>'Maximize clicks','TARGET_CPA'=>'Target CPA','TARGET_ROAS'=>'Target Roas','MAXIMIZE_CONVERSIONS'=>'max conv','MAXIMIZE_CONVERSION_VALUE'=>'Automatically maximize conversions','TARGET_OUTRANK_SHARE'=>'Target outrank sharing','NONE'=>'None','UNKNOWN'=>'Unknown'];
    }
}
