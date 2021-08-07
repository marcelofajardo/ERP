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
use App\GoogleTranslate;

use GetOpt\GetOpt;
// use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
// use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V6\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V6\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V6\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V6\ResourceNames;
use Google\Ads\GoogleAds\V6\Enums\KeywordPlanNetworkEnum\KeywordPlanNetwork;
use Google\Ads\GoogleAds\V6\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V6\Services\GenerateKeywordIdeaResult;
use Google\Ads\GoogleAds\V6\Services\KeywordAndUrlSeed;
use Google\Ads\GoogleAds\V6\Services\KeywordSeed;
use Google\Ads\GoogleAds\V6\Services\UrlSeed;
use Google\ApiCore\ApiException;

class googleAddsV6Controller extends Controller
{

	const PAGE_LIMIT = 100;

	// private const CUSTOMER_ID = 7191785193;
    // private const CUSTOMER_ID = 9081153891;
    private const CUSTOMER_ID = 5155361013;
    // Location criteria IDs. For example, specify 21167 for New York. For more information
    // on determining this value, see
    // https://developers.google.com/adwords/api/docs/appendix/geotargeting.
    private const LOCATION_ID_1 = '1234567890';
    private const LOCATION_ID_2 = '1234567890';

    // A language criterion ID. For example, specify 1000 for English. For more information
    // on determining this value, see
    // https://developers.google.com/adwords/api/docs/appendix/codes-formats#languages.
    private const LANGUAGE_ID = 1000;

    private const KEYWORD_TEXT_1 = 'fruit';
    private const KEYWORD_TEXT_2 = 'mobile';

    // Optional: Specify a URL string related to your business to generate ideas.
    private const PAGE_URL = null;

	public function main( Request $request )
    {   
            $google_redirect_url = route('google-keyword-search-v6');
            $gClient = new \Google_Client();
            $gClient->setApplicationName(env('GOOGLE_ADS_CLIENT_APPLICATION_NAME',null));
            $gClient->setClientId(env('GOOGLE_ADS_CLIENT_ID',null));
            $gClient->setClientSecret(env('GOOGLE_ADS_CLIENT_SECRET',null));
            $gClient->setDeveloperKey(env('GOOGLE_ADS_DEVELOPER_KEY',null));
            $gClient->setRedirectUri($google_redirect_url);
            $gClient->setScopes(array(
                'https://www.googleapis.com/auth/doubleclicksearch',
                'https://www.googleapis.com/auth/dfp',
                'https://www.googleapis.com/auth/adwords',
                'https://www.googleapis.com/auth/webmasters',
                'https://www.googleapis.com/auth/webmasters.readonly',
            ));
            $gClient->setAccessType("offline");          
			$google_oauthV2 = new \Google_Service_Oauth2($gClient);
			if ($request->get('code')){
				$gClient->authenticate($request->get('code'));
			}
			if ($gClient->getAccessToken()){
				$file      = file(storage_path('google_ads_php.ini'));
				$edit_file = str_replace( $file[30], 'refreshToken = "'.$gClient->getAccessToken()['refresh_token'].'"'.PHP_EOL.'', file_get_contents( storage_path('google_ads_php.ini') ));
				file_put_contents( storage_path('google_ads_php.ini'), $edit_file );
				return redirect()->route('google-keyword-search-v6')->with('success','New token generated successfully');
			}else{
				if( request('reauth') == 'true' ){
					$authUrl = $gClient->createAuthUrl();
					return redirect()->to($authUrl);
				}
			}

        // Either pass the required parameters for this example on the command line, or insert them
        // into the constants above.
        // Generate a refreshable OAuth2 credential for authentication.
        if (!$request->ajax()) {
            $languages = $this->getGoogleLanguages();
		    $locations = $this->getGooglelocations();
            return view( 'google.google-adds.index-v6',compact('languages','locations') );
        }

        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile(storage_path('google_ads_php.ini'))->build();

        // Construct a Google Ads client configured from a properties file and the
        // OAuth2 credentials above.
        $googleAdsClient = (new GoogleAdsClientBuilder())->fromFile(storage_path('google_ads_php.ini'))->withOAuth2Credential($oAuth2Credential)->build();
        
        try {
            
			if( $request->location ){
				return $result =  self::runExample($googleAdsClient,self::CUSTOMER_ID,[ $request->location ],$request->language ??self::LANGUAGE_ID,[$request->keyword],self::PAGE_URL);
			}else{
				return $result =  self::runExample($googleAdsClient,self::CUSTOMER_ID,[],$request->language ??self::LANGUAGE_ID,[$request->keyword],self::PAGE_URL);
			}
            
			
        } catch (GoogleAdsException $googleAdsException) {
            printf(
                "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
                $googleAdsException->getRequestId(),
                PHP_EOL,
                PHP_EOL
            );
            foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
                /** @var GoogleAdsError $error */
                printf(
                    "\t%s: %s%s",
                    $error->getErrorCode()->getErrorCode(),
                    $error->getMessage(),
                    PHP_EOL
                );
            }
            exit(1);
        } catch (ApiException $apiException) {
            printf(
                "ApiException was thrown with message '%s'.%s",
                $apiException->getMessage(),
                PHP_EOL
            );
            exit(1);
        }
    }

    /**
     * Runs the example.
     *
     * @param GoogleAdsClient $googleAdsClient the Google Ads API client
     * @param int $customerId the customer ID
     * @param int[] $locationIds the location IDs
     * @param int $languageId the language ID
     * @param string[] $keywords the list of keywords to use as a seed for ideas
     * @param string|null $pageUrl optional URL related to your business to use as a seed for ideas
     */
    // [START GenerateKeywordIdeas]
    public static function runExample(GoogleAdsClient $googleAdsClient,int $customerId,array $locationIds,int $languageId,array $keywords,?string $pageUrl) {

		$keywordPlanIdeaServiceClient = $googleAdsClient->getKeywordPlanIdeaServiceClient();
		// dd( $customerId );
        // Make sure that keywords and/or page URL were specified. The request must have exactly one
        // of urlSeed, keywordSeed, or keywordAndUrlSeed set.
        if (empty($keywords) && is_null($pageUrl)) {
            throw new \InvalidArgumentException(
                'At least one of keywords or page URL is required, but neither was specified.'
            );
        }

        // Specify the optional arguments of the request as a keywordSeed, urlSeed,
        // or keywordAndUrlSeed.
        $requestOptionalArgs = [];
        if (empty($keywords)) {
            // Only page URL was specified, so use a UrlSeed.
            $requestOptionalArgs['urlSeed'] = new UrlSeed(['url' => $pageUrl]);
        } elseif (is_null($pageUrl)) {
            // Only keywords were specified, so use a KeywordSeed.
            $requestOptionalArgs['keywordSeed'] = new KeywordSeed(['keywords' => $keywords]);
        } else {
            // Both page URL and keywords were specified, so use a KeywordAndUrlSeed.
            $requestOptionalArgs['keywordAndUrlSeed'] =
                new KeywordAndUrlSeed(['url' => $pageUrl, 'keywords' => $keywords]);
        }

        // Create a list of geo target constants based on the resource name of specified location
        // IDs.
        $geoTargetConstants =  array_map(function ($locationId) {
            return ResourceNames::forGeoTargetConstant($locationId);
        }, $locationIds);

        // Generate keyword ideas based on the specified parameters.
        $response = $keywordPlanIdeaServiceClient->generateKeywordIdeas(
            [
                // Set the language resource using the provided language ID.
                'language' => ResourceNames::forLanguageConstant($languageId),
                'customerId' => $customerId,
                // Add the resource name of each location ID to the request.
                'geoTargetConstants' => $geoTargetConstants,
                // Set the network. To restrict to only Google Search, change the parameter below to
                // KeywordPlanNetwork::GOOGLE_SEARCH.
                'keywordPlanNetwork' => KeywordPlanNetwork::GOOGLE_SEARCH_AND_PARTNERS
            ] + $requestOptionalArgs
        );

        $finalData = [];
        // Iterate over the results and print its detail.
		$googleTranslate = new GoogleTranslate();

        foreach ($response->iterateAllElements() as $result) {
            /** @var GenerateKeywordIdeaResult $result */
            // dd( $result );
            // Note that the competition printed below is enum value.
            // For example, a value of 2 will be returned when the competition is 'LOW'.
            // A mapping of enum names to values can be found at KeywordPlanCompetitionLevel.php.

			$translateText = '--';
			if(strlen($result->getText()) != mb_strlen($result->getText(), 'utf-8'))
			{ 	
				$translateText = $googleTranslate->translate( 'en', $result->getText() );
			}
            $finalData[] = [
                'keyword' => $result->getText(),
                // 'monthly_search_volumes' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->MonthlySearchVolume(),
                'avg_monthly_searches' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getAvgMonthlySearches(),
                'competition' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getCompetition(),
                'low_top' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getLowTopOfPageBidMicros(),
                'high_top' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getHighTopOfPageBidMicros(),
                'translate_text' => $translateText,
                // 'getMonthlySearches' => is_null($result->getKeywordIdeaMetrics()) ? 0 : $result->getKeywordIdeaMetrics()->getMonthlySearches(),
                
            ];
        }
        return $finalData;
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
