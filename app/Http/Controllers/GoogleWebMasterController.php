<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GoogleWebMasters;
use App\Http\Controllers\Controller;
use App\Site;
use App\GoogleSearchAnalytics;
use App\WebsiteStoreViewsWebmasterHistory;
use App\WebsiteStoreView;
use App\Setting;
use Spatie\Activitylog\Models\Activity;



use Http;

class GoogleWebMasterController extends Controller
{

	public $sitesCreated=0,$sitesUpdated=0,$searchAnalyticsCreated=0;
	
	public $apiKey='';
	public $googleToken='';
	public $curl_errors_array=array();

	public function index(Request $request) {



		$getSites =  GoogleWebMasters::paginate(Setting::get('pagination'),['*'],'crawls_per_page');

        $sites=Site::select('id','site_url')->get();

        $logs=Activity::where('log_name','v3_sites')->orWhere('log_name','v3_search_analytics')->latest()->paginate(Setting::get('pagination'),['*'],'logs_per_page');

     

        $SearchAnalytics=new GoogleSearchAnalytics;

        $devices=$SearchAnalytics->select('device')->where('device','!=',null)->groupBy('device')->orderBy('device','asc')->get();

        $countries=$SearchAnalytics->select('country')->where('country','!=',null)->groupBy('country')->orderBy('country','asc')->get();

        if($request->site)
        {
           $SearchAnalytics=$SearchAnalytics->where('site_id',$request->site);
        }

         if($request->device)
        {
           $SearchAnalytics=$SearchAnalytics->where('device',$request->device);
        }

         if($request->country !='all')
        {
           $SearchAnalytics=$SearchAnalytics->where('country',$request->country);
        }

        if($request->start_date)
        {

           $SearchAnalytics=$SearchAnalytics->where('date','>=',$request->start_date);
          
        }
        if($request->end_date)
        {

           $SearchAnalytics=$SearchAnalytics->where('date','<=',$request->end_date);
          
        }

        if($request->clicks && ($request->clicks=='asc'|| $request->clicks=='desc'))
        {
            
           $SearchAnalytics=$SearchAnalytics->orderBy('clicks',$request->clicks);

        }

        if($request->position && ($request->position=='asc'|| $request->position=='desc'))
        {
            
           $SearchAnalytics=$SearchAnalytics->orderBy('position',$request->position);

        }

        if($request->ctr && ($request->ctr=='asc'|| $request->ctr=='desc'))
        {
            
           $SearchAnalytics=$SearchAnalytics->orderBy('ctr',$request->ctr);

        }

        if($request->impression && ($request->impression=='asc'|| $request->impression=='desc'))
        {
            
           $SearchAnalytics=$SearchAnalytics->orderBy('impressions',$request->impression);

        }

        if($request->country=='all')
        {
            $search_analytics=$SearchAnalytics->select('*',\DB::raw('sum(clicks) as clicks,sum(impressions) as impressions, avg(position) as position,avg(ctr) as ctr'))->groupBy('country');
        }



        $sitesData=$SearchAnalytics->paginate(Setting::get('pagination'));



       // echo '<pre>';print_r($sitesData->toArray());die;

		return view('google-web-master/index', compact('getSites','sitesData','sites','request','devices','countries','logs'));
		}


     public function googleLogin(Request $request)  {

            $google_redirect_url = route('googlewebmaster.get-access-token');
			

           // echo config('constants.GOOGLE_CLIENT_APPLICATION_NAME');die;

            $gClient = new \Google_Client();

            $gClient->setApplicationName(config('constants.GOOGLE_CLIENT_APPLICATION_NAME'));

            $gClient->setClientId(config('constants.GOOGLE_CLIENT_ID'));

            $gClient->setClientSecret(config('constants.GOOGLE_CLIENT_SECRET'));

            $gClient->setDeveloperKey(config('constants.GOOGLE_CLIENT_KEY'));

            $gClient->setRedirectUri($google_redirect_url);

            $gClient->setScopes(array(
                'https://www.googleapis.com/auth/webmasters',
                // 'https://www.googleapis.com/auth/webmasters.readonly',
            ));          

            $google_oauthV2 = new \Google_Service_Oauth2($gClient);
            if ($request->get('code')){
				$gClient->authenticate($request->get('code'));
				try {
					$path = base_path('.env');
					if (file_exists($path)) {
						file_put_contents($path, str_replace(
							'GOOGLE_CLIENT_ACCESS_TOKEN='.env('GOOGLE_CLIENT_ACCESS_TOKEN'), 'GOOGLE_CLIENT_ACCESS_TOKEN='.$gClient->getAccessToken()['access_token'].'', file_get_contents($path)
						));
					}
					\Artisan::call('cache:clear');
					\Artisan::call('config:clear');
					\Log::info('env update succes');
				} catch (\Throwable $th) {
					\Log::error($th->getMessage());
				}

				$request->session()->put('token', $gClient->getAccessToken());
            }
            if ($request->session()->get('token'))
            {
                $gClient->setAccessToken($request->session()->get('token'));
            }
			
            if ($gClient->getAccessToken())
            {
               
                $details=$this->updateSitesData($request);
              

            	$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://www.googleapis.com/webmasters/v3/sites",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
				    "authorization: Bearer ".$gClient->getAccessToken()['access_token']
				  ),
				));
				$response = curl_exec($curl);
				$err = curl_error($curl);

                if (curl_errno($curl)) {

                  $error_msg = curl_error($curl);

                           }
              //echo '<pre>';print_r($response);die;

            if (isset($error_msg)) {
               $this->curl_errors_array[]=array('key'=>'sites','error'=>$error_msg,'type'=>'sites');

               activity('v3_sites')->log($error_msg);
             }

                 $check_error_response=json_decode($response);

               
                            

				curl_close($curl);

				if(isset($check_error_response->error->message) || $err)
                            {


                                $this->curl_errors_array[]=array('key'=>'sites','error'=>$check_error_response->error->message,'type'=>'sites');
                                activity('v3_sites')->log($check_error_response->error->message);

                                echo $this->curl_errors_array[0]['error'];
                            }else {
					if(is_array( json_decode( $response)->siteEntry ) ){
						foreach(json_decode( $response)->siteEntry as $key=> $site) {
							// Create ot update site url
							GoogleWebMasters::updateOrCreate(['sites'=>$site->siteUrl]);

                            echo "https://www.googleapis.com/webmasters/v3/sites/".urlencode($site->siteUrl)."/sitemaps";
							$curl1 = curl_init();
							//replace website name with code coming form site list
							curl_setopt_array($curl1, array(
							    CURLOPT_URL => "https://www.googleapis.com/webmasters/v3/sites/".urlencode($site->siteUrl)."/sitemaps",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 30,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "GET",
							  CURLOPT_HTTPHEADER => array(
							    "authorization: Bearer ".$gClient->getAccessToken()['access_token']
							  ),
							));

							$response1 = curl_exec($curl1);
							$err = curl_error($curl1);
							if ($err) {

                   activity('v3_sites')->log($err);

				  				echo "cURL Error #:" . $err;
							} else {

								
								if(isset(json_decode($response1)->sitemap) && is_array(json_decode($response1)->sitemap) ){
									foreach(json_decode( $response1)->sitemap as $key=> $sitemap) {
										GoogleWebMasters::where('sites',$site->siteUrl)->update(['crawls' => $sitemap->errors]);
									}
								}
							}
						}
					}else{
						 return redirect()->route('googlewebmaster.index')->with('success',$details['success'])->with('error',$details['error_message']);
					}
				}
                         
             return redirect()->route('googlewebmaster.index')->with('success',$details['success'])->with('error',$details['error_message']);          
            } else
            {
                //For Guest user, get google login url
                $authUrl = $gClient->createAuthUrl();
                return redirect()->to($authUrl);
            }
        }


        public function updateSitesData($request)
        {
        	if(!(isset($request->session()->get('token')['access_token'])))
        	{
                 redirect()->route('googlewebmaster.get-access-token');
        	}
        	
        	$google_keys=explode(',',env('GOOGLE_CLIENT_MULTIPLE_KEYS'));


        	foreach($google_keys as $google_key)
        	{
        		if($google_key)
        		{
                   $this->apiKey=$google_key;
                   $this->apiKey='';

                   $this->googleToken=$request->session()->get('token')['access_token'];

        		$url_for_sites='https://www.googleapis.com/webmasters/v3/sites?key='.$this->apiKey;

        		$curl = curl_init();
							//replace website name with code coming form site list
							curl_setopt_array($curl, array(
							    CURLOPT_URL => $url_for_sites,
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 30,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "GET",
							  CURLOPT_HTTPHEADER => array(
							    "authorization: Bearer ".$this->googleToken
							  ),
							));

							$response = curl_exec($curl);

							$response=json_decode($response);

							if (curl_errno($curl)) {

                  $error_msg = curl_error($curl);

                           }
                curl_close($curl);

            if (isset($error_msg)) {
               $this->curl_errors_array[]=array('key'=>$google_key,'error'=>$error_msg,'type'=>'site_list');

               activity('v3_sites')->log($error_msg);
             }

							if(isset($response->error->message))
							{
								 $this->curl_errors_array[]=array('key'=>$google_key,'error'=>$response->error->message,'type'=>'site_list');
               activity('v3_sites')->log($response->error->message);

							}


							
                           if(isset($response->siteEntry) && count($response->siteEntry))
                           {
                           	  $this->updateSites($response->siteEntry);


                           }
							

							}


        	}

           

        	return array('status'=>1,'sitesUpdated'=>$this->sitesUpdated,'sitesCreated'=>$this->sitesCreated,'searchAnalyticsCreated'=>$this->searchAnalyticsCreated,'success'=>$this->sitesUpdated. ' of sites are updated.','error'=>count($this->curl_errors_array).' error found in this request.','error_message'=>$this->curl_errors_array[0]['error']??'');
        }


        public function updateSites($sites)
        {

        	foreach ($sites as $key => $site) {
        		 
        		 if($siteRow=Site::whereSiteUrl($site->siteUrl)->first())
        		 {
                     $siteRow->update(['permission_level'=>$site->permissionLevel]);

                     $this->sitesUpdated++;
        		 }
        		 else
        		 {
        		 	$siteRow=Site::create(['site_url'=>$site->siteUrl,'permission_level'=>$site->permissionLevel]);

                    $this->sitesCreated++;
        		 }

        		 $this->SearchAnalytics($site->siteUrl,$siteRow->id);

        		 $this->SearchAnalyticsBysearchApperiance($site->siteUrl,$siteRow->id);

        	}


        }


        public function SearchAnalyticsBysearchApperiance($siteUrl,$siteID)
        {
        	$params['startDate']='2000-01-01';
        	$params['endDate']=date("Y-m-d");
        	$params['dimensions']=['searchAppearance'];


        		
             $response=$this->googleResultForAnaylist($siteUrl,$params);

             
            
							
							if(isset($response->rows) && count($response->rows))
							{
								$this->updateSearchAnalyticsForSearchAppearence($response->rows,$siteID);
							}
							
                          

        }


        public function updateSearchAnalyticsForSearchAppearence($rows,$siteID)
        {
             foreach ($rows as $row) {
             	$record=array('clicks'=>$row->clicks,'impressions'=>$row->impressions,'position'=>$row->position,'ctr'=>$row->ctr,'site_id'=>$siteID);

             	
             		$record["search_apperiance"]=$row->keys[0];
             		

             		$rowData=new GoogleSearchAnalytics;

             		foreach($record as $col => $val)
             		{
             			$rowData=$rowData->where($col,$val);
             		}

             		if(!$rowData->first())
             		{
                        GoogleSearchAnalytics::create($record);
                        $this->searchAnalyticsCreated++;
             		}
             	
             }
        }



        public function SearchAnalytics($siteUrl,$siteID)
        {
        	$params['startDate']='2000-01-01';
        	$params['endDate']=date("Y-m-d");
        	$params['dimensions']=['country','device','page','query','date'];


        		
             $response=$this->googleResultForAnaylist($siteUrl,$params);

             
			if(isset($response->rows) && count($response->rows))

							{
								$this->updateSearchAnalytics($response->rows,$siteID);
							}
							
                          

        }


        public function updateSearchAnalytics($rows,$siteID)
        {
             foreach ($rows as $row) {
             	$record=array('clicks'=>$row->clicks,'impressions'=>$row->impressions,'position'=>$row->position,'ctr'=>$row->ctr,'site_id'=>$siteID);

             	
             		$record["country"]=$row->keys[0];
             		$record["device"]=$row->keys[1];
             		$record["page"]=$row->keys[2];
             		$record["query"]=$row->keys[3];
                    $record["date"]=$row->keys[4];


             		$rowData=new GoogleSearchAnalytics;

             		foreach($record as $col => $val)
             		{
             			$rowData=$rowData->where($col,$val);
             		}

             		if(!$rowData->first())
             		{
                        GoogleSearchAnalytics::create($record);
                        $this->searchAnalyticsCreated++;
             		}
             	
             }
        }


        public function googleResultForAnaylist($siteUrl,$params)
        {
        	 $url = 'https://www.googleapis.com/webmasters/v3/sites/'.urlencode($siteUrl).'/searchAnalytics/query';



             $curl = curl_init();
							//replace website name with code coming form site list
							curl_setopt_array($curl, array(
							    CURLOPT_URL => $url,
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 30,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							//  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_POSTFIELDS=>json_encode($params),
							  CURLOPT_HTTPHEADER => array(
							    "authorization: Bearer ".$this->googleToken,
							    "Content-Type:application/json"
							  ),
							));

							$response = curl_exec($curl);

							$response=json_decode($response);

							if(isset($response->error->message))
							{
								 $this->curl_errors_array[]=array('siteUrl'=>$siteUrl,'error'=>$response->error->message,'type'=>'search_analytics');

                                 activity('v3_search_analytics')->log($response->error->message);
							}

					
							

							if (curl_errno($curl)) {
                  $error_msg = curl_error($curl);
                           }
                curl_close($curl);

            if (isset($error_msg)) {
               $this->curl_errors_array[]=array('siteUrl'=>$siteUrl,'error'=>$error_msg,'type'=>'search_analytics');

                                 activity('v3_search_analytics')->log($error_msg);

             }
                          
							return $response;
        }

	/**
     * Show the site submit history.
     * 
     * @return Response
     */
	public function getSiteSubmitHitory(){
		$history = WebsiteStoreViewsWebmasterHistory::orderBy("created_at","desc")->get();
		return response()->json( ["code" => 200 , "data" => $history] );
	}

	/**
     * Re-submit site to webmaster.
     * 
     * @return Response
     */
	public function ReSubmitSiteToWebmaster( Request $request ){

		if( !empty( $request->id ) ){
			$fetchStores = WebsiteStoreView::whereNotNull('website_store_id')
						->where('website_store_views.id',$request->id)
						->join("website_stores as ws", "ws.id", "website_store_views.website_store_id")
						->join("websites as w", "w.id", "ws.website_id")
						->join("store_websites as sw", "sw.id", "w.store_website_id")
						->select("website_store_views.code","website_store_views.id", "sw.website")
						->first();
			
			if( $fetchStores ){
				$websiter = urlencode(utf8_encode($fetchStores->website.'/'.$fetchStores->code));
                $url_for_sites = 'https://searchconsole.googleapis.com/webmasters/v3/sites/'.$websiter;
                $token         = env('GOOGLE_CLIENT_ACCESS_TOKEN');

        		$curl = curl_init();
                //replace website name with code coming form site list
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url_for_sites,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_HTTPHEADER => array( 
                        'Accept: application/json',
                        'Content-length: 0', 
                        "authorization: Bearer ".$token
                    ),
                ));
                $response = curl_exec($curl);
                $response = json_decode($response);

                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }

                curl_close($curl);

                if( !empty($response) ){
                    $history = [
                        'website_store_views_id' => $fetchStores->id,
                        'log' => isset( $response->error->message ) ? $response->error->message : 'Error'
                    ];
                    WebsiteStoreViewsWebmasterHistory::insert( $history );
					return response()->json( ["code" => 400 , "message" => $response->error->message] );
                }else{
                    WebsiteStoreView::where('id', $fetchStores->id)->update( [ 'site_submit_webmaster' => 1 ] );
					return response()->json( ["code" => 200 , "message" => 'Site submit successfully'] );
                }
			}
			return response()->json( ["code" => 400 , "message" => 'No record found'] );
		}
	}

}
