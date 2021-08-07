<?php
namespace App\Http\Controllers\GoogleAddWord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PushNotification;
use App\SatutoryTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers;
use App\User;
use App\Task;
use App\TaskCategory;
use App\Contact;
use App\Setting;
use App\Remark;
use App\DocumentRemark;
use App\DeveloperTask;
use App\NotificationQueue;
use App\ChatMessage;
use App\DeveloperTaskHistory;
use App\ScheduledMessage;
use App\WhatsAppGroup;
use App\WhatsAppGroupNumber;
use App\PaymentReceipt;
use App\ChatMessagesQuickData;
use App\Hubstaff\HubstaffMember;
use App\Hubstaff\HubstaffTask;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Storage;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use App\Helpers\HubstaffTrait;

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
use Google\AdsApi\AdWords\v201809\cm\AdWordsServicesIntegrationTestProvider;
use Google\AdsApi\Common\OAuth2TokenBuilder;

use Google\AdsApi\AdWords\v201809\cm\Keyword;
use Google\AdsApi\AdWords\v201809\cm\KeywordMatchType;
use Google\AdsApi\AdWords\v201809\cm\Money;
use Google\AdsApi\AdWords\v201809\o\KeywordEstimateRequest;
use Google\AdsApi\AdWords\v201809\o\AdGroupEstimateRequest;
use Google\AdsApi\AdWords\v201809\o\setKeywordEstimateRequests;
use Google\AdsApi\AdWords\v201809\o\TargetingIdeaSelector;
use Google\AdsApi\AdWords\v201809\o\RequestType;
use Google\AdsApi\AdWords\v201809\o\IdeaType;
use Google\AdsApi\AdWords\v201809\o\setRequestedAttributeTypes;
use Google\AdsApi\AdWords\v201809\o\AttributeType;
use Google\AdsApi\AdWords\v201809\o\RelatedToQuerySearchParameter;
use Google\AdsApi\AdWords\v201809\o\TargetingIdeaService;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\o\LanguageSearchParameter;
use App\Http\Controllers\GoogleAdsController;
use Google\AdsApi\Common\AdsSession;
use Google\AdsApi\AdWords\v201809\cm\Language;
use Google\AdsApi\AdWords\v201809\cm\NetworkSetting;
use Google\AdsApi\AdWords\v201809\o\NetworkSearchParameter;
use Google\AdsApi\AdWords\v201809\o\LocationSearchParameter;
use Google\AdsApi\Common\Util\MapEntries;
use Google\AdsApi\AdWords\v201809\cm\Location;
use Google\AdsApi\AdWords\v201809\cm\Gender;
use Google\AdsApi\AdWords\v201809\cm\BiddableAdGroupCriterion;
use Google\AdsApi\AdWords\v201809\cm\AdGroupCriterionOperation;
use Google\AdsApi\AdWords\v201809\o\SeedAdGroupIdSearchParameter;

class googleAddsController extends Controller
{

	const PAGE_LIMIT = 500;


	public function index( Request $request , AdWordsServices $adWordsServices) {
		$title = 'Google Keyword Search';
		$languages = $this->getGoogleLanguages();
		$locations = $this->getGooglelocations();

		if ($request->ajax()) {

			$adGroupId = 795625088;

			$keyword = $request->keyword; 
			$location = $request->location; 
			$language = $request->language; 
			$network = $request->network; 
			$product = $request->product;
			$gender = $request->gender;

			$google_search = ( $request->google_search == 'true' ) ? true : false;
			$search_network = ( $request->search_network == 'true' ) ? true : false;
			$content_network = ( $request->content_network == 'true' ) ? true : false;
			$partner_search_network = ( $request->partner_search_network == 'true' ) ? true : false;


			$oAuth2Credential = (new OAuth2TokenBuilder())
	            ->fromFile(storage_path('adsapi_php.ini'))
	            ->build();

			$session = (new AdWordsSessionBuilder())
	               ->fromFile(storage_path('adsapi_php.ini'))
	               ->withOAuth2Credential($oAuth2Credential)
	               ->build();

			$targetingIdeaService = $adWordsServices->get($session, TargetingIdeaService::class);

			// Create selector.
	        $selector = new TargetingIdeaSelector();
	        $selector->setRequestType(RequestType::IDEAS);
	        $selector->setIdeaType(IdeaType::KEYWORD);
	        $selector->setRequestedAttributeTypes(
	            [
					AttributeType::KEYWORD_TEXT,
					AttributeType::SEARCH_VOLUME,
					AttributeType::AVERAGE_CPC,
					AttributeType::COMPETITION,
	                AttributeType::CATEGORY_PRODUCTS_AND_SERVICES,
					AttributeType::EXTRACTED_FROM_WEBPAGE,
					AttributeType::IDEA_TYPE,
					AttributeType::TARGETED_MONTHLY_SEARCHES
	            ]
	        );

	        $paging = new Paging();
	        $paging->setStartIndex(0);
	        $paging->setNumberResults(10);
	        $selector->setPaging($paging);

	        $searchParameters = [];
	        // Create related to query search parameter.
	        $relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();
	        $relatedToQuerySearchParameter->setQueries(
	            [
	            	$keyword
	            ]
	        );
	        $searchParameters[] = $relatedToQuerySearchParameter;
	        if (!empty($language)) {
		        // Create language search parameter (optional).
		        // The ID can be found in the documentation:
		        // https://developers.google.com/adwords/api/docs/appendix/languagecodes
		        $languageParameter = new LanguageSearchParameter();
		        $listLanguages = $languageParameter->getLanguages();
		        $english = new Language();
		        $english->setId($language);
		        $languageParameter->setLanguages([$english]);
		        $searchParameters[] = $languageParameter;
		    }

	        // Create network search parameter (optional).
	        $networkSetting = new NetworkSetting();
	        $networkSetting->setTargetGoogleSearch($google_search);
	        $networkSetting->setTargetSearchNetwork($search_network);
	        $networkSetting->setTargetContentNetwork($content_network);
	        $networkSetting->setTargetPartnerSearchNetwork($partner_search_network);

	        $networkSearchParameter = new NetworkSearchParameter();
	        $networkSearchParameter->setNetworkSetting($networkSetting);
	        $searchParameters[] = $networkSearchParameter;


	        // Optional: Set additional criteria for filtering estimates.
	        // See http://code.google.com/apis/adwords/docs/appendix/countrycodes.html
	        // for a detailed list of country codes.
	        // Set targeting criteria. Only locations and languages are supported.

	        if (!empty($location)) {
		        // Create language search parameter (optional).
		        // The ID can be found in the documentation:
		        // https://developers.google.com/adwords/api/docs/appendix/languagecodes

		        $locationParameter = new LocationSearchParameter();
		        $listLocation = $locationParameter->getLocations();
		        $unitedStates = new Location();
	        	$unitedStates->setId($location);
		        $locationParameter->setLocations([$unitedStates]);
		        $searchParameters[] = $locationParameter;
		    }
		    if (!empty($gender)) {
		        // Optional: Use an existing ad group to generate ideas.
		        if (!empty($adGroupId)) {
		            $seedAdGroupIdSearchParameter = new SeedAdGroupIdSearchParameter();
		            $seedAdGroupIdSearchParameter->setAdGroupId($adGroupId);
		            $searchParameters[] = $seedAdGroupIdSearchParameter;
		        }


		        $genderTarget = new Gender();
		        // ID for "male" criterion. The IDs can be found here:
		        // https://developers.google.com/adwords/api/docs/appendix/genders
		        $genderTarget->setId($gender);
		        $genderBiddableAdGroupCriterion = new BiddableAdGroupCriterion();
		        $genderBiddableAdGroupCriterion->setAdGroupId($adGroupId);
		        $genderBiddableAdGroupCriterion->setCriterion($genderTarget);

		        // Create an ad group criterion operation and add it to the list.
		        $genderBiddableAdGroupCriterionOperation = new AdGroupCriterionOperation();
		        $genderBiddableAdGroupCriterionOperation->setOperand(
		            $genderBiddableAdGroupCriterion
		        );
		        $genderBiddableAdGroupCriterionOperation->setOperator(Operator::ADD);

		        $searchParameters[] = $genderBiddableAdGroupCriterionOperation;
		    }

	        $selector->setSearchParameters($searchParameters);
	        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));

	        // Get keyword ideas.
	        $page = $targetingIdeaService->get($selector);

	        // Print out some information for each targeting idea.
	        $entries = $page->getEntries();
	        $finalData = array();
	        if ($entries !== null) {
	            foreach ($entries as $targetingIdea) {
	                $data = MapEntries::toAssociativeArray($targetingIdea->getData());
	                $keyword = $data[AttributeType::KEYWORD_TEXT]->getValue();
	                $searchVolume = ($data[AttributeType::SEARCH_VOLUME]->getValue() !== null)
	                    ? $data[AttributeType::SEARCH_VOLUME]->getValue() : 0;
	                $averageCpc = $data[AttributeType::AVERAGE_CPC]->getValue();
	                $competition = $data[AttributeType::COMPETITION]->getValue();
	                $categoryIds = ($data[AttributeType::CATEGORY_PRODUCTS_AND_SERVICES]->getValue() === null)
	                    ? $categoryIds = ''
	                    : implode(
	                        ', ',
	                        $data[AttributeType::CATEGORY_PRODUCTS_AND_SERVICES]->getValue()
	                    );
	                $extractedFromWebpage = $data[AttributeType::EXTRACTED_FROM_WEBPAGE]->getValue();
	                $ideaType = $data[AttributeType::IDEA_TYPE]->getValue();
	                $tragetedMonthlySearches = $data[AttributeType::TARGETED_MONTHLY_SEARCHES]->getValue();

                    $finalData[] = [
                    	'keyword' => $keyword,
                    	'searchVolume' => $searchVolume,
                    	'averageCpc' => ($averageCpc === null) ? 0 : $averageCpc->getMicroAmount(),
                    	'competition' => $competition,
                    	'categoryIds' => $categoryIds,
                    	'extractedFromWebpage' => $extractedFromWebpage,
						'ideaType' => $ideaType,
						'tragetedMonthlySearches' => $tragetedMonthlySearches
                    ];
	            }
	        }

	        if (empty($entries)) {
	            print "No related keywords were found.\n";
	        }
	        // echo "<pre>"; print_r($finalData); die;
	        return $finalData;
	    }else{
	    	return view( 'google.google-adds.index', compact('title','languages','locations') );
	    }
	}

	public function getGoogleLanguages(){

		$language = [
			[
				"language_name" => "Arabic",
				"language_code" => "ar",
				"criterion_id" => 1019
			],
			[
				"language_name" => "Bengali",
				"language_code" => "bn",
				"criterion_id" => 1056
			],
			[
				"language_name" => "Bulgarian",
				"language_code" => "bg",
				"criterion_id" => 1020
			],
			[
				"language_name" => "Catalan",
				"language_code" => "ca",
				"criterion_id" => 1038
			],
			[
				"language_name" => "Chinese (simplified)",
				"language_code" => "zh_CN",
				"criterion_id" => 1017
			],
			[
				"language_name" => "Chinese (traditional)",
				"language_code" => "zh_TW",
				"criterion_id" => 1018
			],
			[
				"language_name" => "Croatian",
				"language_code" => "hr",
				"criterion_id" => 1039
			],
			[
				"language_name" => "Czech",
				"language_code" => "cs",
				"criterion_id" => 1021
			],
			[
				"language_name" => "Danish",
				"language_code" => "da",
				"criterion_id" => 1009
			],
			[
				"language_name" => "Dutch",
				"language_code" => "nl",
				"criterion_id" => 1010
			],
			[
				"language_name" => "English",
				"language_code" => "en",
				"criterion_id" => 1000
			],
			[
				"language_name" => "Estonian",
				"language_code" => "et",
				"criterion_id" => 1043
			],
			[
				"language_name" => "Filipino",
				"language_code" => "tl",
				"criterion_id" => 1042
			],
			[
				"language_name" => "Finnish",
				"language_code" => "fi",
				"criterion_id" => 1011
			],
			[
				"language_name" => "French",
				"language_code" => "fr",
				"criterion_id" => 1002
			],
			[
				"language_name" => "German",
				"language_code" => "de",
				"criterion_id" => 1001
			],
			[
				"language_name" => "Greek",
				"language_code" => "el",
				"criterion_id" => 1022
			],
			[
				"language_name" => "Gujarati",
				"language_code" => "gu",
				"criterion_id" => 1072
			],
			[
				"language_name" => "Hebrew",
				"language_code" => "iw",
				"criterion_id" => 1027
			],
			[
				"language_name" => "Hindi",
				"language_code" => "hi",
				"criterion_id" => 1023
			],
			[
				"language_name" => "Hungarian",
				"language_code" => "hu",
				"criterion_id" => 1024
			],
			[
				"language_name" => "Icelandic",
				"language_code" => "is",
				"criterion_id" => 1026
			],
			[
				"language_name" => "Indonesian",
				"language_code" => "id",
				"criterion_id" => 1025
			],
			[
				"language_name" => "Italian",
				"language_code" => "it",
				"criterion_id" => 1004
			],
			[
				"language_name" => "Japanese",
				"language_code" => "ja",
				"criterion_id" => 1005
			],
			[
				"language_name" => "Kannada",
				"language_code" => "kn",
				"criterion_id" => 1086
			],
			[
				"language_name" => "Korean",
				"language_code" => "ko",
				"criterion_id" => 1012
			],
			[
				"language_name" => "Latvian",
				"language_code" => "lv",
				"criterion_id" => 1028
			],
			[
				"language_name" => "Lithuanian",
				"language_code" => "lt",
				"criterion_id" => 1029
			],
			[
				"language_name" => "Malay",
				"language_code" => "ms",
				"criterion_id" => 1102
			],
			[
				"language_name" => "Malayalam",
				"language_code" => "ml",
				"criterion_id" => 1098
			],
			[
				"language_name" => "Marathi",
				"language_code" => "mr",
				"criterion_id" => 1101
			],
			[
				"language_name" => "Norwegian",
				"language_code" => "no",
				"criterion_id" => 1013
			],
			[
				"language_name" => "Persian",
				"language_code" => "fa",
				"criterion_id" => 1064
			],
			[
				"language_name" => "Polish",
				"language_code" => "pl",
				"criterion_id" => 1030
			],
			[
				"language_name" => "Portuguese",
				"language_code" => "pt",
				"criterion_id" => 1014
			],
			[
				"language_name" => "Romanian",
				"language_code" => "ro",
				"criterion_id" => 1032
			],
			[
				"language_name" => "Russian",
				"language_code" => "ru",
				"criterion_id" => 1031
			],
			[
				"language_name" => "Serbian",
				"language_code" => "sr",
				"criterion_id" => 1035
			],
			[
				"language_name" => "Slovak",
				"language_code" => "sk",
				"criterion_id" => 1033
			],
			[
				"language_name" => "Slovenian",
				"language_code" => "sl",
				"criterion_id" => 1034
			],
			[
				"language_name" => "Spanish",
				"language_code" => "es",
				"criterion_id" => 1003
			],
			[
				"language_name" => "Swedish",
				"language_code" => "sv",
				"criterion_id" => 1015
			],
			[
				"language_name" => "Tamil",
				"language_code" => "ta",
				"criterion_id" => 1130
			],
			[
				"language_name" => "Telugu",
				"language_code" => "te",
				"criterion_id" => 1131
			],
			[
				"language_name" => "Thai",
				"language_code" => "th",
				"criterion_id" => 1044
			],
			[
				"language_name" => "Turkish",
				"language_code" => "tr",
				"criterion_id" => 1037
			],
			[
				"language_name" => "Ukrainian",
				"language_code" => "uk",
				"criterion_id" => 1036
			],
			[
				"language_name" => "Urdu",
				"language_code" => "ur",
				"criterion_id" => 1041
			],
			[
				"language_name" => "Vietnamese",
				"language_code" => "vi",
				"criterion_id" => 1040
			]
		];

		return $language;
	}

	public function getGooglelocations(){
		$file = storage_path('app/GoogleAds/geotargets-2020-11-18.csv');
		$array = [];
		$row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          	$row++;
          	if ($row > 1) {
          		if ( !is_numeric( $data[1] ) ) {
          			$array[$data[3]] = [
						'name' => $data[1],
						'code' => $data[3]
					];
          		}
          		
          	}
          }
          fclose($handle);
        }
        usort($array, function($a, $b) {
		    return $a['name'] <=> $b['name'];
		});

        return $array;
    }
}
