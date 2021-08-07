<?php

// Load the Google API PHP Client Library.
require_once __DIR__ . '/../../vendor/autoload.php';
$data      = [];
$analytics = initializeAnalytics();

if (!empty($analytics)) {
    // $response = getReport($analytics, $request = '');
    // $data     = printResults($response);
}


/**
 * Initializes an Analytics Reporting API V4 service object.
 *
 * @return An authorized Analytics Reporting API V4 service object.
 */
function initializeAnalytics()
{

    // Use the developers console and download your service account
    // credentials in JSON format. Place them in this directory or
    // change the key file location if necessary.
    $KEY_FILE_LOCATION = storage_path('app/analytics/sololuxu-7674c35e7be5.json');
    $analytics         = '';
    // if (file_exists($KEY_FILE_LOCATION)) {
    //     // Create and configure a new client object.
    //     $client = new Google_Client();
    //     // $client->setApplicationName("Hello Analytics Reporting");
    //     $client->setAuthConfig($KEY_FILE_LOCATION);
    //     $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
    //     $analytics = new Google_Service_AnalyticsReporting($client);
    // }
    return $analytics;
}

/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */
function getReportRequest($analytics, $request)
{
    // Replace with your view ID, for example XXXX.
    if (isset($request['view_id'])) {
        $view_id = (string) $request['view_id'];
    } else {
        // $view_id = env('ANALYTICS_VIEW_ID');
        $view_id = config('env.ANALYTICS_VIEW_ID');
    }

    if(!empty($request)){
        $analytics = '';
        if(isset($request['google_service_account_json']) && $request['google_service_account_json'] != ''){
            $websiteKeyFile = base_path('resources/assets/analytics_files/'.$request['google_service_account_json']);
        }else{
            $websiteKeyFile = storage_path('app/analytics/sololuxu-7674c35e7be5.json');
        }
        if (file_exists($websiteKeyFile)) {
            $client = new Google_Client();
            $client->setAuthConfig($websiteKeyFile);
            $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
            $analytics = new Google_Service_AnalyticsReporting($client);
        }
    }

    // Create the DateRange object.
    $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    $dateRange->setStartDate('today');
    $dateRange->setEndDate('today');
    // $dateRange->setStartDate("30daysAgo");
    // $dateRange->setEndDate("today");

    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($view_id);
    $request->setDateRanges($dateRange);

    return array('requestObj' => $request,'analyticsObj' => $analytics);
}

function getDimensionWiseData( $analytics, $request, $GaDimension ){

    // Create the Dimensions object.
    $dimension = new Google_Service_AnalyticsReporting_Dimension();
    $dimension->setName($GaDimension);

    $request->setDimensions(array( $dimension ));

    // Create the Metrics object.
    // $metric = new Google_Service_AnalyticsReporting_Metric();
    // $metric->setExpression("ga:avgTimeOnPage");
    // $metric->setAlias("avgTimeOnPage");
    // $request->setMetrics(array($metric));

    $request->setDimensions(array( $dimension ));

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

function getPageTrackingData( $analytics, $request){

    // Create the Dimensions object.
    $dimension = new Google_Service_AnalyticsReporting_Dimension();
    $dimension->setName('ga:pagePath');

    $request->setDimensions(array( $dimension ));
    $request->setDimensions(array( $dimension ));

    // Create the Metrics object.
    $metric = new Google_Service_AnalyticsReporting_Metric();
    $metric->setExpression("ga:avgTimeOnPage");
    $metric->setAlias("avgTimeOnPage");

    $uniquePageviews = new Google_Service_AnalyticsReporting_Metric();
    $uniquePageviews->setExpression("ga:uniquePageviews");
    $uniquePageviews->setAlias("uniquePageviews");

    $pageviews = new Google_Service_AnalyticsReporting_Metric();
    $pageviews->setExpression("ga:pageviews");
    $pageviews->setAlias("pageviews");

    $exitRate = new Google_Service_AnalyticsReporting_Metric();
    $exitRate->setExpression("ga:exitRate");
    $exitRate->setAlias("exitRate");

    $entrances = new Google_Service_AnalyticsReporting_Metric();
    $entrances->setExpression("ga:entrances");
    $entrances->setAlias("entrances");

    $entranceRate = new Google_Service_AnalyticsReporting_Metric();
    $entranceRate->setExpression("ga:entranceRate");
    $entranceRate->setAlias("entranceRate");

    $request->setMetrics( array( $metric, $uniquePageviews, $pageviews, $exitRate, $entrances, $entranceRate ) );


    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

function getPlatformDeviceData( $analytics, $request){

    // Create the Dimensions object.
    $dimension = new Google_Service_AnalyticsReporting_Dimension();
    $dimension->setName('ga:browser');

    $operatingSystem = new Google_Service_AnalyticsReporting_Dimension();
    $operatingSystem->setName('ga:operatingSystem');

    $request->setDimensions(array( $dimension, $operatingSystem));

    // Create the Metrics object.

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

function getGeoNetworkData( $analytics, $request){

    // Create the Dimensions object.
    $dimension = new Google_Service_AnalyticsReporting_Dimension();
    $dimension->setName('ga:country');

    $countryIsoCode = new Google_Service_AnalyticsReporting_Dimension();
    $countryIsoCode->setName('ga:countryIsoCode');


    $request->setDimensions(array( $dimension, $countryIsoCode));

    // Create the Metrics object.

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

function getUsersData( $analytics, $request){

    // Create the Dimensions object.
    $dimension = new Google_Service_AnalyticsReporting_Dimension();
    $dimension->setName('ga:userType');

    $request->setDimensions(array( $dimension ));

    // $newUsers = new Google_Service_AnalyticsReporting_Metric();
    // $newUsers->setExpression("ga:1dayUsers");
    // $newUsers->setAlias("newUsers");

    // $request->setMetrics( array( $newUsers) );


    // Create the Metrics object.

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

function getAudiencesData( $analytics, $request){

    // Create the Dimensions object.
    $dimension = new Google_Service_AnalyticsReporting_Dimension();
    $dimension->setName('ga:userAgeBracket');

    $userGender = new Google_Service_AnalyticsReporting_Dimension();
    $userGender->setName('ga:userGender');

    $request->setDimensions(array( $dimension, $userGender ));

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */
function printResults($reports, $websiteAnalyticsId)
{    
    // dump( $reports );
    for ( $reportIndex = 0; $reportIndex < $reports->count(); $reportIndex++ ) {
        
        $report           = $reports[$reportIndex];
        $header           = $report->getColumnHeader();
        $dimensionHeaders = $header->getDimensions();
        $metricHeaders    = $header->getMetricHeader()->getMetricHeaderEntries();
        $rows             = $report->getData()->getRows();

        for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
            $row        = $rows[ $rowIndex ];
            $dimensions = $row->getDimensions();
            $metrics    = $row->getMetrics();

            for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
                    $data[$rowIndex]['dimensions']      = str_replace('ga:', '', $dimensionHeaders[$i]);
                    $data[$rowIndex]['dimensions_name'] = $dimensions[$i];
                    $data[$rowIndex]['website_analytics_id'] = $websiteAnalyticsId;
            }

            for ($j = 0; $j < count($metrics); $j++) {
                $values = $metrics[$j]->getValues();
                for ($k = 0; $k < count($values); $k++) {
                    $entry = $metricHeaders[$k];
                    $data[$rowIndex]['dimensions_value'] = $values[$k];
                    // $data[$rowIndex]['dimensions_value_type'] = $entry->getName();
                }
            }
        }
        
        if (!empty($data)) {
            return $data;
        } else {
            return;
        }

    }
}

function printPageTrackingResults($reports, $websiteAnalyticsId)
{    
    for ($reportIndex = 0; $reportIndex < $reports->count(); $reportIndex++) {
        $report = $reports[$reportIndex];
        $rows   = $report->getData()->getRows();
        foreach ($rows as $key => $value) {
            $data[$key]['website_analytics_id'] = $websiteAnalyticsId;
            $data[$key]['page'] = $value['dimensions']['0'];
            foreach ($value['metrics'] as $m_key => $m_value) {
                $data[$key]['avg_time_page']     = $m_value['values'][0];
                $data[$key]['unique_page_views'] = $m_value['values'][1];
                $data[$key]['page_views']        = $m_value['values'][2];
                $data[$key]['exit_rate']         = $m_value['values'][3];
                $data[$key]['entrances']         = $m_value['values'][4];
                $data[$key]['entrance_rate']     = $m_value['values'][5];
            }

            \App\GoogleAnalyticsPageTracking::insert( $data );
            $data = null;
        }
        return true;
    }
    
}

function printPlatformDeviceResults($reports, $websiteAnalyticsId)
{
    for ($reportIndex = 0; $reportIndex < $reports->count(); $reportIndex++) {
        $report = $reports[$reportIndex];
        $rows   = $report->getData()->getRows();
        foreach ($rows as $key => $value) {

            $data[$key]['website_analytics_id'] = $websiteAnalyticsId;
            $data[$key]['browser']              = $value['dimensions']['0'];
            $data[$key]['os']                   = $value['dimensions']['1'];

            foreach ($value['metrics'] as $m_key => $m_value) {
                $data[$key]['session']     = $m_value['values'][0];
            }

            \App\GoogleAnalyticsPlatformDevice::insert( $data );
            $data = null;
        }
        return true;
        
    }
}

function printGeoNetworkResults($reports, $websiteAnalyticsId)
{
    for ($reportIndex = 0; $reportIndex < $reports->count(); $reportIndex++) {
        $report = $reports[$reportIndex];
        $rows   = $report->getData()->getRows();
        foreach ($rows as $key => $value) {

            $data[$key]['website_analytics_id'] = $websiteAnalyticsId;
            $data[$key]['country']              = $value['dimensions']['0'];
            $data[$key]['iso_code']             = $value['dimensions']['1'];

            foreach ($value['metrics'] as $m_key => $m_value) {
                $data[$key]['session']     = $m_value['values'][0];
            }

            \App\GoogleAnalyticsGeoNetwork::insert( $data );
            $data = null;
        }
        return true;
        
    }
}

function printUsersResults($reports, $websiteAnalyticsId)
{
    for ($reportIndex = 0; $reportIndex < $reports->count(); $reportIndex++) {
        $report = $reports[$reportIndex];
        $rows   = $report->getData()->getRows();
        foreach ($rows as $key => $value) {

            $data[$key]['website_analytics_id'] = $websiteAnalyticsId;
            $data[$key]['user_type']              = $value['dimensions']['0'];
            // $data[$key]['new_user']             = $value['dimensions']['1'];

            foreach ($value['metrics'] as $m_key => $m_value) {
                $data[$key]['session']     = $m_value['values'][0];
                // $data[$key]['newUsers']     = $m_value['values'][1];
            }

            \App\GoogleAnalyticsUser::insert( $data );
            $data = null;
        }
        return true;
    }
}

function printAudienceResults($reports, $websiteAnalyticsId)
{   
    for ($reportIndex = 0; $reportIndex < $reports->count(); $reportIndex++) {
        $report = $reports[$reportIndex];
        $rows   = $report->getData()->getRows();
        foreach ($rows as $key => $value) {

            $data[$key]['website_analytics_id'] = $websiteAnalyticsId;
            $data[$key]['age']                  = $value['dimensions']['0'];
            $data[$key]['gender']               = $value['dimensions']['1'];

            foreach ($value['metrics'] as $m_key => $m_value) {
                $data[$key]['session']     = $m_value['values'][0];
            }
            \App\GoogleAnalyticsAudience::insert( $data );
            $data = null;
        }
        return true;
    }
}
