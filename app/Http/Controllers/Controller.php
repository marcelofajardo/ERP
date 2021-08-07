<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\ApiResponseMessage;
use App\GoogleTranslate;
use App\WebsiteStoreView;
use App\Language;


/**
 * @SWG\Swagger(
 *	 schemes={"http" , "https"},
 *   basePath="/api" ,
 *   @SWG\Info(
 *     title="API Documentation",
 *     version="1.0",
 *	   
 *     description="This API documentation list all API endpoints available in applicaiton. Documentation also list Modules and variable available in each Modules",
 *     @SWG\Contact(
 *         email="xyz@xyz.com"
 *     )
 *   )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function generate_erp_response($key, $store_website_id, $default = "", $lang_code = null){
        $return_message = ApiResponseMessage::where('store_website_id',$store_website_id)->where('key',$key)->first();
        $message = $default;
        if(!empty($return_message)){
            $message = $return_message->value;
        }

        if( !empty( $lang_code ) ){
            
            $lan_name = WebsiteStoreView::where('code', $lang_code )->first();
            if ( isset($lan_name->name) ) {
                $local_code = Language::where('name',$lan_name->name )->first();
                if( isset( $local_code->locale ) ){
                    $googleTranslate   = new GoogleTranslate();
                    $translationString = GoogleTranslateController::translateProducts( $googleTranslate, $local_code->locale, [ $message ] );
                    if( $translationString ){
                        $message = $translationString;
                    }

                }
            }
        }
        return $message;
    }
}
