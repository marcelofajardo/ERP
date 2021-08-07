<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\AdGroupService;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\v201809\cm\AdGroup;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAdRotationMode;
use Google\AdsApi\AdWords\v201809\cm\AdGroupOperation;
use Google\AdsApi\AdWords\v201809\cm\AdGroupStatus;
use Google\AdsApi\AdWords\v201809\cm\AdRotationMode;
use Google\AdsApi\AdWords\v201809\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201809\cm\CpcBid;
use Google\AdsApi\AdWords\v201809\cm\CriterionTypeGroup;
use Google\AdsApi\AdWords\v201809\cm\Money;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\cm\TargetingSetting;
use Google\AdsApi\AdWords\v201809\cm\TargetingSettingDetail;
use Exception;
class GoogleAdGroupController extends Controller
{
    const PAGE_LIMIT = 500;
    const CPC_BID_MICRO_AMOUNT = null;
    public $exceptionError="Something went wrong";
    // show campaigns in main page
    public function getstoragepath($account_id)
    {
        $result = \App\GoogleAdsAccount::find($account_id);
        if (\Storage::disk('adsapi')->exists($account_id . '/' . $result->config_file_path)) {
            $storagepath = \Storage::disk('adsapi')->url($account_id . '/' . $result->config_file_path);
            $storagepath = storage_path('app/adsapi/' . $account_id . '/' . $result->config_file_path);
            /* echo $storagepath; exit;
        echo storage_path('adsapi_php.ini'); exit; */
            /* echo '<pre>' . print_r($result, true) . '</pre>';
            die('developer working'); */
            return $storagepath;
        } else {
            abort(404,"Please add adspai_php.ini file");
        }
    }

    public function getAccountDetail($campaignId){
        $campaignDetail=\App\GoogleAdsCampaign::where('google_campaign_id',$campaignId)->first();
        if($campaignDetail->exists()>0){
          return array(
            'account_id'=>$campaignDetail->account_id,
            'campaign_name'=>$campaignDetail->campaign_name
          );
        }else{
            abort(404,"Invalid account!");
        }
    }

    public function index(Request $request, $campaignId) {
        /* // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile(storage_path('adsapi_php.ini'))->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile(storage_path('adsapi_php.ini'))->withOAuth2Credential($oAuth2Credential)->build();

        $adGroups = $this->getAdGroups(new AdWordsServices(), $session, $campaignId);
        return view('googleadgroups.index', ['adGroups' => $adGroups['adGroups'], 'totalNumEntries' => $adGroups['totalNumEntries'], 'campaignId' => $campaignId]); */
        $acDetail=$this->getAccountDetail($campaignId);
        $campaign_account_id=$acDetail['account_id'];
        $campaign_name=$acDetail['campaign_name'];

        $query=\App\GoogleAdsGroup::query();

        if($request->googlegroup_name){
			$query = $query->where('ad_group_name','LIKE','%'.$request->googlegroup_name.'%');
        }

        if($request->bid){
            $query = $query->where('bid','LIKE','%'.$request->bid.'%');
        }
        
        if($request->googlegroup_id){
			$query = $query->where('google_adgroup_id', $request->googlegroup_id);
        }
   
        if($request->adsgroup_status){
			$query = $query->where('status', $request->adsgroup_status);
        }

        $query->where('adgroup_google_campaign_id',$campaignId);
        $adGroups = $query->orderby('id','desc')->paginate(25)->appends(request()->except(['page']));

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('googleadgroups.partials.list-adsgroup', ['adGroups' => $adGroups,'campaignId' => $campaignId])->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$adGroups->render(),
                'count' => $adGroups->total(),
            ], 200);
        }

        $totalEntries=$adGroups->total();
        return view('googleadgroups.index', ['adGroups' => $adGroups, 'totalNumEntries' => $totalEntries, 'campaignId' => $campaignId,'campaign_name'=>$campaign_name,'campaign_account_id'=>$campaign_account_id]);
    }

    // getting all Ad Groups of specific campaign
    public function getAdGroups(AdWordsServices $adWordsServices, AdWordsSession $session, $campaignId) {
        $adGroupService = $adWordsServices->get($session, AdGroupService::class);

        // Create a selector to select all ad groups for the specified campaign.
        $selector = new Selector();
        $selector->setFields(['Id', 'Name', 'Status', 'CpcBid']);
        $selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
        $selector->setPredicates(
            [new Predicate('CampaignId', PredicateOperator::IN, [$campaignId])]
        );
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));

        $totalNumEntries = 0;
        $adGroups = [];
        do {
            // Retrieve ad groups one page at a time, continuing to request pages
            // until all ad groups have been retrieved.
            $page = $adGroupService->get($selector);

            // Print out some information for each ad group.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                foreach ($page->getEntries() as $adGroup) {
                    $adGroups[] = [
                        'adGroupId' => $adGroup->getId(),
                        'name' => $adGroup->getName(),
                        'status' => $adGroup->getStatus(),
                        'bidAmount' => $adGroup->getBiddingStrategyConfiguration()->getBids()[0]->getBid()->getMicroAmount() / 1000000
                    ];
                }
            }

            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + self::PAGE_LIMIT
            );
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return [
            'totalNumEntries' => $totalNumEntries,
            'adGroups' => $adGroups
        ];
    }

    // got to ad group create page
    public function createPage($campaignId) {
        //
        $acDetail=$this->getAccountDetail($campaignId);
        $campaign_name=$acDetail['campaign_name'];
        return view('googleadgroups.create', ['campaignId' => $campaignId,'campaign_name'=>$campaign_name]);
    }

    // create ad group
    public function createAdGroup(Request $request, $campaignId) {

        try{
        $this->validate($request, [
            'adGroupName' => 'required|max:55',
            'microAmount' => 'required',
        ]);

        $adGroupStatusArr = ['UNKNOWN', 'ENABLED', 'PAUSED', 'REMOVED'];
//        $criterionTypeGroups = ['KEYWORD', 'USER_INTEREST_AND_LIST', 'VERTICAL', 'GENDER', 'AGE_RANGE', 'PLACEMENT', 'PARENT', 'INCOME_RANGE', 'NONE', 'UNKNOWN'];
//        $adRotationModes = ['UNKNOWN', 'OPTIMIZE', 'ROTATE_FOREVER'];
        $addgroupArray=array();    
        $adGroupName = $request->adGroupName;
        $microAmount = $request->microAmount * 1000000;
        $adGroupStatus = $adGroupStatusArr[$request->adGroupStatus];
        $acDetail=$this->getAccountDetail($campaignId);
        $account_id=$acDetail['account_id'];
        $storagepath=$this->getstoragepath($account_id);
        $addgroupArray['adgroup_google_campaign_id']=$campaignId;
        $addgroupArray['ad_group_name']=$adGroupName;
        $addgroupArray['bid']=$request->microAmount;
        $addgroupArray['status']=$adGroupStatus;
//        $criterionTypeGroup = $criterionTypeGroups[$request->criterionTypeGroup];
//        $adRotationMode = $adRotationModes[$request->adRotationMode];
    
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adGroupService = (new AdWordsServices())->get($session, AdGroupService::class);

        $operations = [];

        /// Create an ad group with required settings and specified status.
        $adGroup = new AdGroup();
        $adGroup->setCampaignId($campaignId);
        $adGroup->setName($adGroupName);

        // Set bids (required).
        $bid = new CpcBid();
        $money = new Money();
        $money->setMicroAmount($microAmount);
        $bid->setBid($money);
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBids([$bid]);
        $adGroup->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

        $adGroup->setStatus($adGroupStatus);

        // Create an ad group operation and add it to the operations list.
        $operation = new AdGroupOperation();
        $operation->setOperand($adGroup);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;


        // Create the ad groups on the server
        $result = $adGroupService->mutate($operations);
        $addedGroup=$result->getValue();
        $addedGroupId=$addedGroup[0]->getId();
        $addgroupArray['google_adgroup_id']=$addedGroupId;
        $addgroupArray['adgroup_response']=json_encode($addedGroup[0]);
        \App\GoogleAdsGroup::create($addgroupArray);
        return redirect('google-campaigns/' . $campaignId . '/adgroups')->with('actSuccess', 'Adsgroup added successfully');
        }
        catch(Exception $e) {
            return redirect('google-campaigns/' . $campaignId . '/adgroups/create')->with('actError', $this->exceptionError);
          }
    }

    // go to update page
    public function updatePage(Request $request, $campaignId, $adGroupId) {
        
        $acDetail=$this->getAccountDetail($campaignId);
        $account_id=$acDetail['account_id'];
        $storagepath=$this->getstoragepath($account_id);
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adGroupService = (new AdWordsServices())->get($session, AdGroupService::class);

        // Create a selector to select all ad groups for the specified campaign.
        /* $selector = new Selector();
        $selector->setFields(['Id', 'Name', 'Status', 'CampaignId', 'CampaignName', 'CpcBid']);
        $selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
        $selector->setPredicates(
            [new Predicate('CampaignId', PredicateOperator::IN, [$campaignId]),
             new Predicate('Id', PredicateOperator::IN, [$adGroupId])]
        );
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));

        $page = $adGroupService->get($selector);
        $pageEntries = $page->getEntries();

        $adGroup = [];
        if ($pageEntries !== null) {
            $adGroup = $pageEntries[0];
        }

        $adGroup = [
            'adGroupId' => $adGroup->getId(),
            'name' => $adGroup->getName(),
            'status' => $adGroup->getStatus(),
            'bidAmount' => $adGroup->getBiddingStrategyConfiguration()->getBids()[0]->getBid()->getMicroAmount() / 1000000
        ]; */
        $adGroup=\App\GoogleAdsGroup::where('google_adgroup_id',$adGroupId)->where('adgroup_google_campaign_id',$campaignId)->first();
        return view('googleadgroups.update', ['adGroup' => $adGroup, 'campaignId' => $campaignId]);
    }

    // update ad group
    public function updateAdGroup(Request $request, $campaignId) {

        $this->validate($request, [
            'adGroupName' => 'required|max:55',
            'cpcBidMicroAmount' => 'required',
        ]);
        try {
        $acDetail=$this->getAccountDetail($campaignId);
        $account_id=$acDetail['account_id'];
        $storagepath=$this->getstoragepath($account_id);
        $addgroupArray=array();
        $adGroupStatusArr = ['UNKNOWN', 'ENABLED', 'PAUSED', 'REMOVED'];
        $adGroupId = $request->adGroupId;
        $adGroupName = $request->adGroupName;
        $cpcBidMicroAmount = $request->cpcBidMicroAmount * 1000000;
        $adGroupStatus = $adGroupStatusArr[$request->adGroupStatus];
        
        $addgroupArray['ad_group_name']=$adGroupName;
        $addgroupArray['bid']=$request->cpcBidMicroAmount;
        $addgroupArray['status']=$adGroupStatus;
        
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adGroupService = (new AdWordsServices())->get($session, AdGroupService::class);

        $operations = [];
        // Create ad group with the specified ID.
        $adGroup = new AdGroup();
        $adGroup->setId($adGroupId);
        $adGroup->setName($adGroupName);
        $adGroup->setStatus($adGroupStatus);

        // Update the CPC bid if specified.
        if (!is_null($cpcBidMicroAmount)) {
            $bid = new CpcBid();
            $money = new Money();
            $money->setMicroAmount($cpcBidMicroAmount);
            $bid->setBid($money);
            $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
            $biddingStrategyConfiguration->setBids([$bid]);
            $adGroup->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
        }

        // Create ad group operation and add it to the list.
        $operation = new AdGroupOperation();
        $operation->setOperand($adGroup);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        // Update the ad group on the server.
        $result = $adGroupService->mutate($operations);
        $adGroupUpdate=\App\GoogleAdsGroup::where('google_adgroup_id',$adGroupId)->where('adgroup_google_campaign_id',$campaignId)->update($addgroupArray);
        return redirect('google-campaigns/' . $campaignId . '/adgroups')->with('actSuccess', 'Adsgroup updated successfully');
    }
    catch(Exception $e) {
        return redirect('google-campaigns/' . $campaignId . '/adgroups/update/'.$request->adGroupId)->with('actError', $this->exceptionError);
      }
      
    }

    // delete ad group
    public function deleteAdGroup(Request $request, $campaignId, $adGroupId) {
        
        $acDetail=$this->getAccountDetail($campaignId);
        $account_id=$acDetail['account_id'];
        $storagepath=$this->getstoragepath($account_id);
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        try {
        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adWordsServices = new AdWordsServices();

        $adGroupService = $adWordsServices->get($session, AdGroupService::class);

        $operations = [];
        // Create ad group with REMOVED status.
        $adGroup = new AdGroup();
        $adGroup->setId($adGroupId);
        $adGroup->setStatus(AdGroupStatus::REMOVED);

        // Create ad group operation and add it to the list.
        $operation = new AdGroupOperation();
        $operation->setOperand($adGroup);
        $operation->setOperator(Operator::SET);
        $operations[] = $operation;

        // Remove the ad group on the server.
        $result = $adGroupService->mutate($operations);

        $adGroup = $result->getValue()[0];
        \App\GoogleAdsGroup::where('google_adgroup_id',$adGroupId)->where('adgroup_google_campaign_id',$campaignId)->delete();
        return redirect('google-campaigns/' . $campaignId . '/adgroups')->with('actSuccess', 'Adsgroup deleted successfully');
        }
        catch(Exception $e) {
            return redirect('google-campaigns/' . $campaignId . '/adgroups')->with('actError', $this->exceptionError);
          }

    }
}
