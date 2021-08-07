<?php

namespace App\Services\Search;

use App\Loggers\LogTineye;
use tineye\api\TinEyeApi;

class TinEye
{

    private $_tinEyeApi = null;

    function __construct()
    {
        // Initiate API
        $this->_tinEyeApi = new TinEyeApi(
            \Config::get("tineye.private_api_key"),
            \Config::get("tineye.public_api_key")
        );
    }

    function searchByImage($url = '', $returnAsGoogle = false)
    {
        // Check if construct worked
        if ($this->_tinEyeApi == null || empty($url)) {
            return false;
        }

        // Send URL to API
       try {
           if (substr($url, 0, 4) != 'http' && file_exists($url)) {
               $results = $this->_tinEyeApi->searchData(fopen($url, 'r'), 'filename.jpg');
           } elseif (substr($url, 0, 4) != 'http') {
               $results = $this->_tinEyeApi->searchUrl($url);
           } else {
               $results = [];
           }
       } catch (\Exception $e) {
           // Set empty results
           $results = [];
       }

       if ( count($results)>0) {
           // Store result
           LogTineye::log($url, json_encode($results));
       }

//        For testing
        /*$results = LogTineye::find(26);
        $results = json_decode($results->response);*/

        // TODO: Check for result count
        if (!$returnAsGoogle) {
            return $results;
        } else {
            return $this->_convertResultToMatchGoogleVision($url, json_decode(json_encode($results)));
        }
    }

    private function _convertResultToMatchGoogleVision($url = '', $results = false)
    {
        // Set empty arrays
        $pages = [];
        $pagesMedia = [];

        // Loop over results to find pages
        if (isset($results->results->matches) && count($results->results->matches) > 0) {
            foreach ($results->results->matches as $match) {
                if (isset($match->backlinks)) {
                    foreach ($match->backlinks as $backlink) {
                        $pages[] = $backlink->backlink;
                        $pagesMedia[] = $backlink->url;
                    }
                }
            }
        }

        return [
            'pages' => $pages,
            'pages_media' => $pagesMedia
        ];
    }
}
