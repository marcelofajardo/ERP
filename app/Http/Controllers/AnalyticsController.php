<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Analytics;
use App\AnalyticsSummary;
use App\AnalyticsCustomerBehaviour;
use App\StoreWebsiteAnalytic;
// use App\GoogleAnalytics;
use App\GoogleAnalyticsPageTracking;
use App\GoogleAnalyticsPlatformDevice;
use App\GoogleAnalyticsGeoNetwork;
use App\GoogleAnalyticsUser;
use App\GoogleAnalyticsAudience;
use App\Setting;
use App\GoogleAnalyticsHistories;
use Spatie\Analytics\Period;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DB;
use function Opis\Closure\unserialize;
use App\LinksToPost;
use App\ArticleCategory;
use Response;

class AnalyticsController extends Controller
{
    public function showData(Request $request)
    {   

        $website_list = StoreWebsiteAnalytic::all()->toArray();

        $pageTrackingData = GoogleAnalyticsPageTracking::select('google_analytics_page_tracking.*','store_website_analytics.website')
                            ->leftJoin('store_website_analytics','google_analytics_page_tracking.website_analytics_id','=','store_website_analytics.id');

        $PlatformDeviceData = GoogleAnalyticsPlatformDevice::select('google_analytics_platform_device.*','store_website_analytics.website')
                                ->leftJoin('store_website_analytics','google_analytics_platform_device.website_analytics_id','=','store_website_analytics.id');

        $geoNetworkData = GoogleAnalyticsGeoNetwork::select('google_analytics_geo_network.*','store_website_analytics.website')
                            ->leftJoin('store_website_analytics','google_analytics_geo_network.website_analytics_id','=','store_website_analytics.id');

        $usersData = GoogleAnalyticsUser::select('google_analytics_user.*','store_website_analytics.website')
                            ->leftJoin('store_website_analytics','google_analytics_user.website_analytics_id','=','store_website_analytics.id');

        $audienceData = GoogleAnalyticsAudience::select('google_analytics_audience.*','store_website_analytics.website')
                            ->leftJoin('store_website_analytics','google_analytics_audience.website_analytics_id','=','store_website_analytics.id');
    
        /** Filter */

        if( $request->start_date  && $request->end_date ){
            $pageTrackingData->whereBetween( 'google_analytics_page_tracking.created_at', [ $request->start_date, $request->end_date ] );
            $PlatformDeviceData->whereBetween( 'google_analytics_platform_device.created_at', [ $request->start_date, $request->end_date ] );
            $geoNetworkData->whereBetween( 'google_analytics_geo_network.created_at', [ $request->start_date, $request->end_date ] );
            $usersData->whereBetween( 'google_analytics_user.created_at', [ $request->start_date, $request->end_date ] );
            $audienceData->whereBetween( 'google_analytics_audience.created_at', [ $request->start_date, $request->end_date ] );
        }

        if( $request->website ){
            $pageTrackingData->where( 'google_analytics_page_tracking.website_analytics_id', $request->website );
            $PlatformDeviceData->where( 'google_analytics_platform_device.website_analytics_id', $request->website );
            $geoNetworkData->where( 'google_analytics_geo_network.website_analytics_id', $request->website );
            $usersData->where( 'google_analytics_user.website_analytics_id', $request->website );
            $audienceData->where( 'google_analytics_audience.website_analytics_id', $request->website );
        }

        $pageTrackingData   = $pageTrackingData->orderBy('google_analytics_page_tracking.created_at','DESC')->paginate(Setting::get('pagination'),['*'],'tracking-per-page');
        $PlatformDeviceData = $PlatformDeviceData->orderBy('google_analytics_platform_device.created_at','DESC')->paginate(Setting::get('pagination'));
        $geoNetworkData     = $geoNetworkData->orderBy('google_analytics_geo_network.created_at','DESC')->paginate(Setting::get('pagination'),['*'],'geo-network');
        $usersData          = $usersData->orderBy('google_analytics_user.created_at','DESC')->paginate(Setting::get('pagination'),['*'],'user-per-page');
        $audienceData       = $audienceData->orderBy('google_analytics_audience.created_at','DESC')->paginate(Setting::get('pagination'),['*'],'audience-per-page');

        return View('analytics.index-new', compact('pageTrackingData','PlatformDeviceData','geoNetworkData','usersData','website_list','audienceData'));
    }

    public function analyticsDataSummary(Request $request)
    {
        $brands = [
            'balenciaga' => 'Balenciaga',
            'gucci' => 'Gucci',
            'jimmy-choo' => 'Jimmy Choo',
            'saint-laurent' => 'Saint Laurent',
            'givenchy' => 'Givenchy',
            'christian-dior' => 'Christian Dior',
            'prada' => 'Prada',
            'dolce-gabbana' => 'Dolce Gabbana',
            'fendi' => 'Fendi',
            'bottega-veneta' => 'Bottega Vneta',
            'burberry' => 'Burberry',
        ];
        $genders = [
            'mens' => 'Mens',
            'women' => 'Womens',
        ];
        if (!empty($_GET[ 'location' ])) {
            $location = $_GET[ 'location' ];
            $data = AnalyticsSummary::where('country', 'like', '%' . $location . '%')->get()->toArray();
        } elseif (!empty($_GET[ 'brand' ])) {
            $data = AnalyticsSummary::where('brand_name', $request[ 'brand' ])->get()->toArray();
        } elseif (!empty($_GET[ 'gender' ])) {
            $data = AnalyticsSummary::where('gender', $request[ 'gender' ])->get()->toArray();
        } else {
            include(app_path() . '/Functions/Analytics.php');
        }
        return View('analytics.summary', compact('data', 'brands', 'genders'));
    }

    public function displayLinksToPostData()
    {
        $data = LinksToPost::orderBy('id', 'desc')->paginate(15);
        $category = ArticleCategory::all();
        return View('analytics.linkstopost', compact('data', 'category'));
    }

    public function updateCategoryPost(Request $request)
    {

        $post = LinksToPost::findorfail($request->link_id);
        $post->category_id = $request->id;
        $post->save();

        return Response::json(array(
            'success' => true,
            'message' => 'Post Updated'
        ));

    }

    public function addArticleCategory(Request $request)
    {
        $category = new ArticleCategory;
        $category->name = $request->name;
        $category->save();

        return redirect()->back()->with(['message', 'Category Saved', 'success', 'true']);

    }

    /**
     * Customer Behaviour By Page
     */
    public function customerBehaviourByPage(Request $request)
    {
        $pages = AnalyticsCustomerBehaviour::select('ID', 'pages')->pluck('pages', 'ID')->toArray();
        if (!empty($request[ 'page' ])) {
            $data = AnalyticsCustomerBehaviour::where('pages', $request[ 'page' ])->get()->toArray();
        } else { 
            include(app_path() . '/Functions/Analytics.php');
        }
        return View('analytics.customer-behaviour', compact('data', 'pages'));
    }

    public function cronShowData(){

        \Log::channel('daily')->info("Google Analytics Started running ...");
        $analyticsDataArr = [];

        include(app_path() . '/Functions/Analytics.php');
        $data = StoreWebsiteAnalytic::all()->toArray();
        foreach ($data as $value) {

            $ERPlogArray = [
                'model_id' => $value['id'],
                'url'      => 'https://www.googleapis.com/auth/analytics.readonly',
                'model'    => StoreWebsiteAnalytic::class,
                'type'     => 'success',
                'request'  => $value,
            ];

            try {
                
                $response   = getReport($analytics, $value);
                $resultData = printResults($response);

                if(!empty($resultData)) {
                    foreach ($resultData as $new_item) {
                         $analyticsDataArr = [
                                "operatingSystem" => $new_item['operatingSystem'],
                                "user_type" => $new_item['user_type'],
                                "time" => $new_item['time'],
                                "page_path" => $value['website'].$new_item['page_path'],
                                "country" => $new_item['country'],
                                "city" => $new_item['city'],
                                "social_network" => $new_item['social_network'],
                                "date" => $new_item['date'],
                                "device_info" => $new_item['device_info'],
                                "sessions" => $new_item['sessions'],
                                "pageviews" => $new_item['pageviews'],
                                "bounceRate" => $new_item['bounceRate'],
                                "avgSessionDuration" => $new_item['avgSessionDuration'],
                                "timeOnPage" => $new_item['timeOnPage'],

                          ];
                         Analytics::insert($analyticsDataArr);
                    }
                }

                $ERPlogArray['request'] = $value;
                $ERPlogArray['response'] = $resultData;

            }catch(\Exception  $e) {
                $ERPlogArray['type']    = 'error';
                $ERPlogArray['response'] = $e->getMessage();
            }
            storeERPLog($ERPlogArray);
        }
    }

    public function history( Request $request ){
        $history = GoogleAnalyticsHistories::orderBy("id","desc")->take(50)->get();
        return response()->json( ["code" => 200 , "data" => $history] );
    }

    public function cronGetUserShowData(){

        \Log::channel('daily')->info("Google Analytics Cron running ...");
        $analyticsDataArr = [];

        include(app_path() . '/Functions/Analytics_user.php');
        $data = StoreWebsiteAnalytic::all()->toArray();
        

        foreach ($data as $value) {

            $ERPlogArray = [
                'model_id' => $value['id'],
                'url'      => 'https://www.googleapis.com/auth/analytics.readonly',
                'model'    => StoreWebsiteAnalytic::class,
                'type'     => 'success',
                'request'  => $value,
            ];

            try {
                
                if( !empty($value['view_id']) && !empty($value['google_service_account_json']) ){
                    $response   = getReportRequest($analytics, $value);
                    extract($response);

                    $resultData             = getPageTrackingData( $analyticsObj ,$requestObj);
                    $resultPageTrackingData = printPageTrackingResults( $resultData , $value['id']);

                    $resultData           = getPlatformDeviceData( $analyticsObj ,$requestObj);
                    $ResultPlatformDevice = printPlatformDeviceResults( $resultData , $value['id']);

                    $resultData             = getGeoNetworkData( $analyticsObj ,$requestObj);
                    $ResultGeoNetworkDevice = printGeoNetworkResults( $resultData , $value['id']);

                    $resultData  = getUsersData( $analyticsObj ,$requestObj);
                    $UsersDevice = printUsersResults( $resultData , $value['id']);

                    $resultData = getAudiencesData( $analyticsObj ,$requestObj);
                    $Audiences  = printAudienceResults( $resultData , $value['id']);

                    \Log::info('google-analytics :: Daily run success');

                    $history = array(
                        'website'     => $value['website'], 
                        'account_id'  => $value['id'],
                        'title'       => 'success',
                        'description' => 'Data fetched successfully'
                    );
                    GoogleAnalyticsHistories::insert($history);
                }else{
                    $history = array(
                        'website'     => $value['website'], 
                        'account_id'  => $value['id'],
                        'title'       => 'error',
                        'description' => 'Please add auth json file and view id',
                    );
                    GoogleAnalyticsHistories::insert($history);
                }
                
            }catch(\Exception  $e) {

                $history = array(
                    'website'     => $value['website'], 
                    'account_id'  => $value['id'],
                    'title'       => 'error',
                    'description' => $e->getMessage()
                );
                GoogleAnalyticsHistories::insert($history);
                \Log::error('google-analytics :: '.$e->getMessage());
            }
        }
        return;
    }
}
