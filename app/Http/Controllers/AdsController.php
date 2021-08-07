<?php

namespace App\Http\Controllers;

use App\GoogleAdsAccount;
use App\GoogleAdsCampaign;
use App\AdCampaign;
use App\AdGroup;
use App\Ad;
use App\AdAccount;

use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Plank\Mediable\Mediable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
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
use Google\AdsApi\AdWords\v201809\cm\AdGroup as GoogleAdGroup;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAdRotationMode;
use Google\AdsApi\AdWords\v201809\cm\AdGroupOperation;
use Google\AdsApi\AdWords\v201809\cm\AdGroupStatus;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use Google\AdsApi\AdWords\v201809\cm\CpcBid;
use Google\AdsApi\AdWords\v201809\cm\ExpandedTextAd;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAd;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAdOperation;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAdService;
use Exception;
use Carbon\Carbon;



class AdsController extends Controller
{
    public function index(Request $request)
    {
        $title  = "Ads";
        $campigns = AdCampaign::select('id','campaign_name')->get();
        $adaccounts = AdAccount::where('status','ENABLED')->get();
        return view("ads.index", compact('title','campigns','adaccounts'));
    }

    public function records(Request $request)
    {
        // $records = GoogleAdsAccount::leftJoin("googlecampaigns as gc","gc.account_id","googleadsaccounts.id")
        // ->leftJoin("googleadsgroups as gg","gg.adgroup_google_campaign_id","gc.google_campaign_id")
        // ->leftJoin("googleads as ga","ga.google_adgroup_id","gg.google_adgroup_id");

        $records = AdAccount::leftJoin("ad_campaigns as gc","gc.ad_account_id","ad_accounts.id")
        ->leftJoin("ad_groups as gg","gg.campaign_id","gc.id")
        ->leftJoin("ads as ga","ga.adgroup_id","gg.id")
        ->select('ad_accounts.id','ad_accounts.account_name','ad_accounts.status','ad_accounts.created_at', 'gc.campaign_name','gc.data', 'ga.headlines');

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("gg.group_name", "LIKE", "%$keyword%")
                ->orWhere("gc.campaign_name", "LIKE", "%$keyword%")
                ->orWhere("ga.headlines", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();
        return response()->json(["code" => 200, "data" => $records, "total" => count($records)]);
    }
    //Ad Account functions start
    public function saveaccount(Request $request){
        $this->validate($request, [
            'account_name'      => 'required',
            'config_file'       => 'required',
            'status'            => 'required',
        ]);

        $accountArray = array(
            'account_name'  => $request->account_name,
            'note'          => $request->note,
            'status'        => $request->status,
        );
        $googleadsAc = AdAccount::create($accountArray);
        $account_id = $googleadsAc->id;
        if($request->file('config_file')){
            $uploadfile = MediaUploader::fromSource($request->file('config_file'))
                ->toDestination('adsapi', $account_id)
                ->upload();
            $getfilename = $uploadfile->filename . '.' . $uploadfile->extension;
            $googleadsAc->config_file = $getfilename;
            $googleadsAc->save();
        }
        return redirect()->to('/ads')->with('actSuccess', 'GoogleAdwords account details added successfully');
    }
    //Ad Account functions end
    public function savecampaign(Request $request){
        $adAccount = AdAccount::find($request->account_id);
        $budgetAmount = isset($request->data['budget_and_bidding']['budget']) ? $request->data['budget_and_bidding']['budget'] * 1000000 : 0;
        $campaignName = isset($request->camp_name) ? $request->camp_name : '';
        $channel_type = isset($request->data['type']) ? strtouper($request->data['type']) : 'SEARCH';
        $budgetName = isset($request->data['budget_and_bidding']['name']) ? $request->data['budget_and_bidding']['name'] : '';
        $bidding_strategy_type = isset($request->data['budget_and_bidding']['bidding']['focus']) ? $request->data['budget_and_bidding']['bidding']['focus'] : ''; 
        $txt_target_cpa = isset($request->data['budget_and_bidding']['bidding']['cpa']) ? $request->data['budget_and_bidding']['bidding']['cpa'] : ''; 
        $txt_target_roas = isset($request->data['budget_and_bidding']['bidding']['roas']) ? $request->data['budget_and_bidding']['bidding']['roas'] : ''; 
        $maximize_clicks = isset($request->data['budget_and_bidding']['bidding']['cpc']) ? $request->data['budget_and_bidding']['bidding']['cpc'] : ''; 

        $campaign_start_date = (isset($request->data['start_end_dated']['startdate']) && !empty($request->data['start_end_dated']['startdate'])) ? $request->data['start_end_dated']['startdate'] : Carbon::now()->format('Y-m-d');
        $campaign_end_date = isset($request->data['start_end_dated']['enddate']) ? $request->data['start_end_dated']['enddate'] : ''; ;
        $tracking_template_url = isset($request->data['campaign_url']['tracking_tamplate']) ? $request->data['campaign_url']['tracking_tamplate'] : '';
        $final_url_suffix = isset($request->data['campaign_url']['final_url_suffix']) ? $request->data['campaign_url']['final_url_suffix'] : '';

        $configFile = storage_path('app/adsapi/' . $request->account_id . '/' . $adAccount->config_file);
        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile($configFile)
            ->build();
        $session = (new AdWordsSessionBuilder())
            ->fromFile($configFile)
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
        $adWordsServices = new AdWordsServices();

        $budgetService = $adWordsServices->get($session, BudgetService::class);

        $uniq_id = uniqid();
        
        $budget = new Budget();
        $budget->setName($budgetName);



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


        $campaign->setAdvertisingChannelType($this->getAdvertisingChannelType($channel_type)); //set channel Type

        $budget_id = $budget->getBudgetId();
        $campaign->setBudget(new Budget());
        $campaign->getBudget()->setBudgetId($budget->getBudgetId());

        // Set bidding strategy (required).
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPC);
        $biddingScheme = new ManualCpcBiddingScheme();

        // if($bidding_strategy_type=="TARGET_CPA"){
        //     $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_CPA);
        //     $biddingScheme = new TargetCpaBiddingScheme();
        //     $biddingScheme->setTargetCpa($txt_target_cpa);
            
        // }

        // if($bidding_strategy_type=="TARGET_ROAS"){
        //     $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_ROAS);
        //     $biddingScheme = new TargetRoasBiddingScheme();
        //     $biddingScheme->setTargetRoas($txt_target_roas);
        // }
        // if($bidding_strategy_type=="TARGET_SPEND"){
        //     $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::TARGET_SPEND);
        //     $biddingScheme = new TargetSpendBiddingScheme();
        //     if(isset($maximize_clicks) && $maximize_clicks!=""){
        //         $biddingScheme->setSpendTarget($maximize_clicks);
        //     }
        // }

        // if($bidding_strategy_type=="MANUAL_CPM"){
        //     $biddingStrategyConfiguration->setBiddingStrategyType(BiddingStrategyType::MANUAL_CPM);
        //     $biddingScheme = new ManualCpmBiddingScheme();
        // }



        // You can optionally provide a bidding scheme in place of the type.
        $biddingStrategyConfiguration->setBiddingScheme($biddingScheme);

        $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);


        $networkSetting = new NetworkSetting();
        $networkSetting->setTargetGoogleSearch(true);
        $campaign->setNetworkSetting($networkSetting);

        $campaign->setStatus('PAUSED');

        $campaign->setStartDate($campaign_start_date);
        if ($request->data['start_end_dated']['type'] == 'date') {
            $campaign->setEndDate($campaign_end_date);
        }

        // $campaign->setTrackingUrlTemplate($tracking_template_url);

        // Set frequency cap (optional).
        $frequencyCap = new FrequencyCap();
        $frequencyCap->setImpressions(5);
        $frequencyCap->setTimeUnit(TimeUnit::DAY);
        $frequencyCap->setLevel(Level::ADGROUP);
        $campaign->setFrequencyCap($frequencyCap);

        // Create a campaign operation and add it to the operations list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;

        $result = $campaignService->mutate($operations);
        $addedCampaign = $result->getValue();
        $addedCampaignId = $addedCampaign[0]->getId();

        $newCapaign = new AdCampaign();
        $newCapaign->ad_account_id  = $request->account_id;
        $newCapaign->goal           = $request->goal;
        $newCapaign->type           = $request->type;
        $newCapaign->campaign_name  = $request->camp_name;
        $newCapaign->data           = json_encode($request->data);
        $newCapaign->campaign_budget_id= $budget_id ;
        $newCapaign->campaign_id= $addedCampaignId;
        $newCapaign->campaign_response= json_encode($addedCampaign);
        $newCapaign->save();
        
        return redirect()->to('/ads')->with('actSuccess', 'Campaign details added successfully');
    }
    public function savegroup(Request $request){
        $adCampaign = AdCampaign::find($request->campaign);
        $adAccount = AdAccount::find($adCampaign->ad_account_id);


        $storagepath = storage_path('app/adsapi/' . $adAccount->id . '/' . $adAccount->config_file);
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adGroupService = (new AdWordsServices())->get($session, AdGroupService::class);



        if (isset($request->adgroup)) {
            foreach ($request->adgroup as $key => $value) {

                $microAmount = $value['budget'] * 1000000;

                /// Create an ad group with required settings and specified status.
                $adGroup = new GoogleAdGroup();
                $adGroup->setCampaignId($adCampaign->campaign_id);
                $adGroup->setName($value['name'].mt_rand());


                // Set bids (required).
                $bid = new CpcBid();
                $money = new Money();
                $money->setMicroAmount($microAmount);
                $bid->setBid($money);
                $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
                $biddingStrategyConfiguration->setBids([$bid]);
                $adGroup->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

                $adGroup->setStatus('PAUSED');

                $operations = [];

                // Create an ad group operation and add it to the operations list.
                $operation = new AdGroupOperation();
                $operation->setOperand($adGroup);
                $operation->setOperator(Operator::ADD);
                $operations[] = $operation;


                $result = $adGroupService->mutate($operations);
                $addedGroup=$result->getValue();
                $addedGroupId=$addedGroup[0]->getId();

                $newGroup = new AdGroup();
                $newGroup->campaign_id = $request->campaign;
                $newGroup->google_campaign_id = $adCampaign->campaign_id;
                $newGroup->type = $request->type;
                $newGroup->group_name = $value['name'];
                $newGroup->url = $value['url'];
                $newGroup->keywords = $value['keywords'];
                $newGroup->budget = $value['budget'];
                $newGroup->google_ad_group_id = $addedGroupId;
                $newGroup->google_ad_group_response = json_encode($addedGroup[0]);;
                $newGroup->save();
            }
            return redirect()->to('/ads')->with('actSuccess', 'Ads group details added successfully');
        }else{
            return redirect('/ads');
        }
    }
    public function getgroups(Request $request){
        $group = AdGroup::where('campaign_id',$request->input('id'))->get();
        return response()->json(['success' => true, 'message' => 'Group retrived', 'data' => $group]);
    }
    public function adsstore(Request $request){
        $adGroupp = AdGroup::find($request->adgroup);
        $adCampaign = AdCampaign::find($adGroupp->campaign_id);
        $adAccount = AdAccount::find($adCampaign->ad_account_id);

        $storagepath = storage_path('app/adsapi/' . $adAccount->id . '/' . $adAccount->config_file);
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adGroupAdService = (new AdWordsServices())->get($session, AdGroupAdService::class);

        $operations = [];

        $expandedTextAd = new ExpandedTextAd();
        $expandedTextAd->setHeadlinePart1(isset($request->headlines[0]) ? $request->headlines[0] : '');
        $expandedTextAd->setHeadlinePart2(isset($request->headlines[1]) ? $request->headlines[1] : '');
        $expandedTextAd->setHeadlinePart3(isset($request->headlines[2]) ? $request->headlines[2] : '');
        $expandedTextAd->setDescription(isset($request->descriptions[0]) ? $request->descriptions[0] : '');
        $expandedTextAd->setDescription2(isset($request->descriptions[1]) ? $request->descriptions[1] : '');
        $expandedTextAd->setFinalUrls([$request->finalurl]);
        $expandedTextAd->setPath1('abc');
        $expandedTextAd->setPath2('def');


        // Create ad group ad.
        $adGroupAd = new AdGroupAd();
        $adGroupAd->setAdGroupId($adGroupp->google_ad_group_id);
        $adGroupAd->setAd($expandedTextAd);

        // Optional: Set additional settings.
        $adGroupAd->setStatus('PAUSED');

        // Create ad group ad operation and add it to the list.
        $operation = new AdGroupAdOperation();
        $operation->setOperand($adGroupAd);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;

        // Add expanded text ads on the server.
        $result = $adGroupAdService->mutate($operations);
        $addedAds=$result->getValue();
        $addedAdsId=$addedAds[0]->getAd()->getId();


        $newAd =  new Ad();
        $newAd->campaign_id = $request->campaign;
        $newAd->adgroup_id = $request->adgroup;
        $newAd->finalurl = $request->finalurl;
        $newAd->displayurl = $request->displayurl;
        $newAd->headlines = isset($request->headlines) ? json_encode($request->headlines) : '[]';
        $newAd->descriptions = isset($request->descriptions) ? json_encode($request->descriptions) : '[]';
        $newAd->tracking_tamplate = $request->tracking_tamplate;
        $newAd->final_url_suffix = $request->final_url_suffix;
        $newAd->customparam = isset($request->customparam) ? json_encode($request->customparam) : '[]';
        $newAd->different_url_mobile = $request->different_url_mobile;
        $newAd->mobile_final_url = $request->mobile_final_url;
        $newAd->ad_id = $addedAdsId;
        $newAd->ad_response = json_encode($addedAds[0]);
        $newAd->save();
        return redirect()->to('/ads')->with('actSuccess', 'Ads details added successfully');
    }
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
}
