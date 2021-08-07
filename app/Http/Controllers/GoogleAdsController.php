<?php

namespace App\Http\Controllers;

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\Ad;
use Google\AdsApi\AdWords\v201809\cm\AdGroup;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAd;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAdOperation;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAdService;
use Google\AdsApi\AdWords\v201809\cm\AdGroupAdStatus;
use Google\AdsApi\AdWords\v201809\cm\AdType;
use Google\AdsApi\AdWords\v201809\cm\ExpandedTextAd;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Predicate;
use Google\AdsApi\AdWords\v201809\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Illuminate\Http\Request;
use Exception;

class GoogleAdsController extends Controller
{
    const PAGE_LIMIT = 500;
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
    
    public function index(Request $request, $campaignId, $adGroupId) {
        /* $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile(storage_path('adsapi_php.ini'))->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile(storage_path('adsapi_php.ini'))->withOAuth2Credential($oAuth2Credential)->build();

        $adsInfo = $this->getAds(new AdWordsServices(), $session, $adGroupId);

        return view('googleads.index',
            ['ads' => $adsInfo['ads'], 'totalNumEntries' => $adsInfo['totalNumEntries'],
                'campaignId' => $campaignId, 'adGroupId' => $adGroupId]); */

                $groupDetail=\App\GoogleAdsGroup::where('google_adgroup_id',$adGroupId)->first();
                $query=\App\GoogleAd::query();

                if($request->headline){
                    $query = $query->where(function($q) use($request){
                        $q->where('headline1', 'LIKE','%'.$request->headline.'%')->orWhere('headline2', 'LIKE', '%'.$request->headline.'%')
                            ->orWhere('headline3', 'LIKE', '%'.$request->headline.'%');
                    });
                }

                if($request->description){
                    $query =$query->where(function($q) use($request){
                        $q->where('description1', 'LIKE','%'.$request->description.'%')->orWhere('description2', 'LIKE', '%'.$request->description.'%');
                    });
                }

                if($request->path){
                    $query = $query->where(function($q) use($request){
                        $q->where('path1', 'LIKE','%'.$request->path.'%')->orWhere('path2', 'LIKE', '%'.$request->path.'%');
                    });
                }

                if($request->final_url){
                    $query = $query->where('final_url','LIKE','%'.$request->final_url.'%');
                }
           
                if($request->ads_status){
                    $query = $query->where('status', $request->ads_status);
                }
                
                $query->where('adgroup_google_campaign_id',$campaignId)->where('google_adgroup_id',$adGroupId);
                $adsInfo=$query->orderby('id','desc')->paginate(25)->appends(request()->except(['page']));
                if ($request->ajax()) {
                    return response()->json([
                        'tbody' => view('googleads.partials.list-ads', ['ads' => $adsInfo,'campaignId' => $campaignId,'adGroupId' => $adGroupId])->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                        'links' => (string)$adsInfo->render(),
                        'count' => $adsInfo->total(),
                    ], 200);
                }

                $totalEntries=$adsInfo->total();
                return view('googleads.index',['ads' => $adsInfo, 'totalNumEntries' => $totalEntries,'campaignId' => $campaignId, 'adGroupId' => $adGroupId,'groupname'=>@$groupDetail->ad_group_name]);

    }

    // getting ads
    public function getAds(AdWordsServices $adWordsServices, AdWordsSession $session, $adGroupId) {
        $adGroupAdService = $adWordsServices->get($session, AdGroupAdService::class);

        // Create a selector to select all ads for the specified ad group.
        $selector = new Selector();
        $selector->setFields(
            ['Id', 'Status', 'HeadlinePart1', 'HeadlinePart2', 'Description']
        );
        $selector->setOrdering([new OrderBy('Id', SortOrder::ASCENDING)]);
        $selector->setPredicates(
            [
                new Predicate('AdGroupId', PredicateOperator::IN, [$adGroupId]),
                new Predicate(
                    'AdType',
                    PredicateOperator::IN,
                    [AdType::EXPANDED_TEXT_AD]
                ),
                new Predicate(
                    'Status',
                    PredicateOperator::IN,
                    [AdGroupAdStatus::ENABLED, AdGroupAdStatus::PAUSED]
                )
            ]
        );

        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));

        $totalNumEntries = 0;
        $ads = [];
        do {
            // Retrieve ad group ads one page at a time, continuing to request pages
            // until all ad group ads have been retrieved.
            $page = $adGroupAdService->get($selector);

            // Print out some information for each ad group ad.
            if ($page->getEntries() !== null) {
                $totalNumEntries = $page->getTotalNumEntries();
                foreach ($page->getEntries() as $adGroupAd) {
                    $ad = $adGroupAd->getAd();
                    $ads[] = [
                        'adId' => $ad->getId(),
                        'status' => $adGroupAd->getStatus(),
                        'headlinePart1' => $ad->getHeadlinePart1(),
                        'headlinePart2' => $ad->getHeadlinePart2(),
                        'description' => $ad->getDescription(),
                        'type' => $ad->getAdType()
                    ];
                }
            }

            $selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + self::PAGE_LIMIT
            );
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return [
            'ads' => $ads,
            'totalNumEntries' => $totalNumEntries
        ];
    }

    // go to ad create page
    public function createPage($campaignId, $adGroupId) {
        //
        return view('googleads.create', ['campaignId' => $campaignId, 'adGroupId' => $adGroupId]);
    }

    // create ad
    public function createAd(Request $request, $campaignId, $adGroupId) {
        //create account
        $this->validate($request, [
            'headlinePart1' => 'required|max:25',
            'headlinePart2' => 'required|max:25',
            'headlinePart3' => 'required|max:25',
            'description1' => 'required|max:200',
            'description2' => 'required|max:200',
            'finalUrl' => 'required|max:200',
        ]);

        $acDetail=$this->getAccountDetail($campaignId);
        $account_id=$acDetail['account_id'];
        $storagepath=$this->getstoragepath($account_id);

        $adsArray=array();
        $adStatuses = ['ENABLED', 'PAUSED', 'DISABLED'];
        $headlinePart1 = $request->headlinePart1;
        $headlinePart2 = $request->headlinePart2;
        $headlinePart3 = $request->headlinePart3;
        $description1 = $request->description1;
        $description2 = $request->description2;
        $finalUrl = $request->finalUrl;
        $path1 = $request->path1;
        $path2 = $request->path2;
        $adStatus = $adStatuses[$request->adStatus];
        $adsArray['adgroup_google_campaign_id']=$campaignId;
        $adsArray['google_adgroup_id']=$adGroupId;
        $adsArray['headline1']=$headlinePart1;
        $adsArray['headline2']=$headlinePart2;
        $adsArray['headline3']=$headlinePart3;
        $adsArray['description1']=$description1;
        $adsArray['description2']=$description2;
        $adsArray['final_url']=$finalUrl;
        $adsArray['path1']=$path1;
        $adsArray['path2']=$path2;
        $adsArray['status']=$adStatus;
        try{
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adGroupAdService = (new AdWordsServices())->get($session, AdGroupAdService::class);

        $operations = [];
        // Create an expanded text ad.
        $expandedTextAd = new ExpandedTextAd();
        $expandedTextAd->setHeadlinePart1($headlinePart1);
        $expandedTextAd->setHeadlinePart2($headlinePart2);
        $expandedTextAd->setHeadlinePart3($headlinePart3);
        $expandedTextAd->setDescription($description1);
        $expandedTextAd->setDescription2($description2);
        $expandedTextAd->setFinalUrls([$finalUrl]);
        $expandedTextAd->setPath1($path1);
        $expandedTextAd->setPath2($path2);

        // Create ad group ad.
        $adGroupAd = new AdGroupAd();
        $adGroupAd->setAdGroupId($adGroupId);
        $adGroupAd->setAd($expandedTextAd);

        // Optional: Set additional settings.
        $adGroupAd->setStatus($adStatus);

        // Create ad group ad operation and add it to the list.
        $operation = new AdGroupAdOperation();
        $operation->setOperand($adGroupAd);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;

        // Add expanded text ads on the server.
        $result = $adGroupAdService->mutate($operations);
        $addedAds=$result->getValue();
        $addedAdsId=$addedAds[0]->getAd()->getId();
        $adsArray['google_ad_id']=$addedAdsId;
        $adsArray['ads_response']=json_encode($addedAds[0]);
        \App\GoogleAd::create($adsArray);
        return redirect('google-campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/ads')->with('actSuccess', 'Ads created successfully');
        }
        catch(Exception $e) {
            return redirect('google-campaigns/' . $campaignId . '/adgroups/'.$adGroupId.'/ads/create')->with('actError', $this->exceptionError);
          }
    }

    // go to ad update page
    public function updatePage() {

    }

    // update ad
    public function updateAd() {

    }

    // delete ad
    public function deleteAd(Request $request, $campaignId, $adGroupId, $adId) {
        
        $acDetail=$this->getAccountDetail($campaignId);
        $account_id=$acDetail['account_id'];
        $storagepath=$this->getstoragepath($account_id);

        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile($storagepath)->build();

        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        try{
        $session = (new AdWordsSessionBuilder())->fromFile($storagepath)->withOAuth2Credential($oAuth2Credential)->build();

        $adGroupAdService = (new AdWordsServices())->get($session, AdGroupAdService::class);

        $operations = [];
        // Create ad using an existing ID. Use the base class Ad instead of TextAd
        // to avoid having to set ad-specific fields.
        $ad = new Ad();
        $ad->setId($adId);

        // Create ad group ad.
        $adGroupAd = new AdGroupAd();
        $adGroupAd->setAdGroupId($adGroupId);
        $adGroupAd->setAd($ad);

        // Create ad group ad operation and add it to the list.
        $operation = new AdGroupAdOperation();
        $operation->setOperand($adGroupAd);
        $operation->setOperator(Operator::REMOVE);
        $operations[] = $operation;

        // Remove the ad on the server.
        $result = $adGroupAdService->mutate($operations);
        \App\GoogleAd::where('adgroup_google_campaign_id',$campaignId)->where('google_adgroup_id',$adGroupId)->where('google_ad_id',$adId)->delete();
        return redirect('google-campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/ads')->with('actSuccess', 'Ads deleted successfully');
        }
        catch(Exception $e) {
            return redirect('google-campaigns/' . $campaignId . '/adgroups/' . $adGroupId . '/ads')->with('actError', $this->exceptionError);
          }
    }
}
