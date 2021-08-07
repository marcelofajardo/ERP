<?php

// Load the Google API PHP Client Library.
require_once __DIR__ . '/../../vendor/autoload.php';
$data      = [];
$analytics = initializeAnalytics();

if (!empty($analytics)) {
    $response = getReport($analytics, $request = '');
    $data     = printResults($response);
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
    if (file_exists($KEY_FILE_LOCATION)) {
        // Create and configure a new client object.
        $client = new Google_Client();
        // $client->setApplicationName("Hello Analytics Reporting");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_AnalyticsReporting($client);
    }
    return $analytics;
}

/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */
function getReport($analytics, $request)
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
    $dateRange->setStartDate(!empty($request) && !empty($request['start_date']) ? $request['start_date'] : "2DaysAgo");
    //$dateRange->setEndDate(!empty($request) && !empty($request['end_date']) ? $request['end_date'] : "1DaysAgo");
    $dateRange->setEndDate(!empty($request) && !empty($request['end_date']) ? $request['end_date'] : date('Y-m-d'));

    // Create the Metric objects.
    $sessions = new Google_Service_AnalyticsReporting_Metric();
    $sessions->setExpression("ga:sessions");
    $sessions->setAlias("sessions");
    $pageviews = new Google_Service_AnalyticsReporting_Metric();
    $pageviews->setExpression("ga:pageviews");
    $pageviews->setAlias("pageviews");
    $bounceRate = new Google_Service_AnalyticsReporting_Metric();
    $bounceRate->setExpression("ga:bounceRate");
    $bounceRate->setAlias("bounceRate");
    $avgSessionDuration = new Google_Service_AnalyticsReporting_Metric();
    $avgSessionDuration->setExpression("ga:avgSessionDuration");
    $avgSessionDuration->setAlias("avgSessionDuration");
    $timeOnPage = new Google_Service_AnalyticsReporting_Metric();
    $timeOnPage->setExpression("ga:timeOnPage");
    $timeOnPage->setAlias("timeOnPage");
    $uniquePageviews = new Google_Service_AnalyticsReporting_Metric();
    $uniquePageviews->setExpression("ga:uniquePageviews");
    $uniquePageviews->setAlias("uniquePageviews");
    $entrances = new Google_Service_AnalyticsReporting_Metric();
    $entrances->setExpression("ga:entrances");
    $entrances->setAlias("entrances");
    $exitRate = new Google_Service_AnalyticsReporting_Metric();
    $exitRate->setExpression("ga:exitRate");
    $exitRate->setAlias("exitRate");
    $avgTimeOnPage = new Google_Service_AnalyticsReporting_Metric();
    $avgTimeOnPage->setExpression("ga:avgTimeOnPage");
    $avgTimeOnPage->setAlias("avgTimeOnPage");
    $pageValue = new Google_Service_AnalyticsReporting_Metric();
    $pageValue->setExpression("ga:pageValue");
    $pageValue->setAlias("pageValue");

    // Create the Dimensions object.
    $operatingSystem = new Google_Service_AnalyticsReporting_Dimension();
    $operatingSystem->setName("ga:operatingSystem");
    $user = new Google_Service_AnalyticsReporting_Dimension();
    $user->setName("ga:userType");
    $minute = new Google_Service_AnalyticsReporting_Dimension();
    $minute->setName("ga:minute");
    $pagePath = new Google_Service_AnalyticsReporting_Dimension();
    $pagePath->setName("ga:pagePath");
    $country = new Google_Service_AnalyticsReporting_Dimension();
    $country->setName("ga:country");
    $city = new Google_Service_AnalyticsReporting_Dimension();
    $city->setName("ga:city");
    $socialNetwork = new Google_Service_AnalyticsReporting_Dimension();
    $socialNetwork->setName("ga:socialNetwork");
    $date = new Google_Service_AnalyticsReporting_Dimension();
    $date->setName("ga:date");
    $mobileDeviceInfo = new Google_Service_AnalyticsReporting_Dimension();
    $mobileDeviceInfo->setName("ga:mobileDeviceInfo");

    // Create the ReportRequest object.
    $request = new Google_Service_AnalyticsReporting_ReportRequest();
    $request->setViewId($view_id);
    $request->setDateRanges($dateRange);
    $request->setDimensions(array($operatingSystem, $user, $minute, $pagePath, $country, $city, $socialNetwork, $date, $mobileDeviceInfo));
    $request->setMetrics(array($sessions, $pageviews, $bounceRate, $avgSessionDuration, $timeOnPage, $uniquePageviews, $entrances, $exitRate, $avgTimeOnPage, $pageValue));

    $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    $body->setReportRequests(array($request));
    return $analytics->reports->batchGet($body);
}

/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */
function printResults($reports)
{
    for ($reportIndex = 0; $reportIndex < $reports->count(); $reportIndex++) {
        $report = $reports[$reportIndex];
        $rows   = $report->getData()->getRows();
        foreach ($rows as $key => $value) {
            $data[$key]['operatingSystem'] = $value['dimensions']['0'];
            $data[$key]['user_type']       = $value['dimensions']['1'];
            $data[$key]['time']            = $value['dimensions']['2'];
            $data[$key]['page_path']       = $value['dimensions']['3'];
            $data[$key]['country']         = $value['dimensions']['4'];
            $data[$key]['city']            = $value['dimensions']['5'];
            $data[$key]['social_network']  = $value['dimensions']['6'];
            $data[$key]['date']            = $value['dimensions']['7'];
            $data[$key]['device_info']     = $value['dimensions']['8'];
            foreach ($value['metrics'] as $m_key => $m_value) {
                $data[$key]['sessions']           = $m_value['values'][0];
                $data[$key]['pageviews']          = $m_value['values'][1];
                $data[$key]['bounceRate']         = $m_value['values'][2];
                $data[$key]['avgSessionDuration'] = $m_value['values'][3];
                $data[$key]['timeOnPage']         = $m_value['values'][4];
                $data[$key]['uniquePageviews']    = $m_value['values'][5];
                $data[$key]['entrances']          = $m_value['values'][6];
                $data[$key]['exitRate']           = $m_value['values'][7];
                $data[$key]['avgTimeOnPage']      = $m_value['values'][8];
                $data[$key]['pageValue']          = $m_value['values'][9];
            }
        }
        if (!empty($data)) {
            return $data;
        } else {
            return;
        }

    }
}
