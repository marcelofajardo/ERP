<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wetransfer;
use seo2websites\ErpExcelImporter\ErpExcelImporter;
use App\Setting;

class WeTransferController extends Controller
{
    public function index()
    {
        $wetransfers = Wetransfer::orderBy('id')->paginate(Setting::get('pagination'));
        return view('wetransfer.index',['wetransfers' => $wetransfers]);
    }

    /**
     * @SWG\Get(
     *   path="/wetransfer",
     *   tags={"Wetransfer"},
     *   summary="Get wetransfer link",
     *   operationId="get-wetransfer-link",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function getLink()
    {
    	$wetransfer = Wetransfer::where('is_processed',0)->first();
    	if($wetransfer == null){
    		return json_encode(['error' => 'Nothing to process now']);
    	}
    	return json_encode($wetransfer);
    }

    /**
     * @SWG\Post(
     *   path="/wetransfer-file-store",
     *   tags={"Wetransfer"},
     *   summary="store wetransfer file",
     *   operationId="store-wetransfer-file",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function storeFile(Request $request)
    {
        $wetransfer = Wetransfer::find($request->id);
        if($request->status){
            $wetransfer->is_processed = 2;
            $wetransfer->update();
            return json_encode(['success' => 'Wetransfer Status has been updated']);
        }

        if($request->file){

            $wetransfer->is_processed = 1;
            $wetransfer->update();
            $file = $request->file('file');
            $attachments_array = [];
            if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                $attachments = ErpExcelImporter::excelZipProcess($file, $file->getClientOriginalName(), $wetransfer->supplier, '', $attachments_array);
                
            }

            return json_encode(['success' => 'Wetransfer has been stored']);
        }	
    }

    public function reDownloadFiles(Request $request)
    {   

        $id = $request->id;
        $list = Wetransfer::where( 'id', $id )->first();

        if ( !empty( $list ) ) {
            // foreach ($queuesList as $list) {
                
                $this->downloadFromURL( $list->url, $list->supplier );
                $file  = $this->downloadWetransferFiles( $list->url );
                
                if ( !empty( $file ) ) {
                    
                    $extension = last(explode('.', $file));
                    if ( $extension == 'zip' ) {

                        $filename_list = [];
                        $zip  = new \ZipArchive;

                        if( $zip->open( public_path( 'wetransfer/'.$file ) ) === TRUE ){
                            for ($i = 0; $i < $zip->count(); $i++) {
                                $filename_list[] = $zip->getNameIndex($i);
                            }
                            $zip->extractTo( public_path( 'wetransfer/' ) );
                        }

                        $update = array(
                            'files_count'  => $zip->count(),
                            'files_list'   => json_encode( $filename_list ),
                            'is_processed' => 2,
                        );
                        $zip->close();
                        Wetransfer::where( 'id', $list->id )->update( $update );

                    }else{

                        $update = array(
                           'files_count'  => 1,
                           'files_list'   => json_encode( [ $file ] ),
                           'is_processed' => 2,
                       );
                       Wetransfer::where( 'id', $list->id )->update( $update );

                    }

                     return response()->json([
                        'status'      => true,
                        'message'     => 'Download completed'
                    ], 200);
                   
                }

                $update = array(
                        'files_count'  => 0,
                        'files_list'   => null,
                        'is_processed' => 0,
                );
                Wetransfer::where( 'id', $list->id )->update( $update );
               
            // }	
        }
        return response()->json([
            'status'      => true,
            'message'     => 'Something went wrong, Please check if URL is correct!'
        ], 200);
    }

    private function downloadWetransferFiles( $url = null )
    {
        $WETRANSFER_API_URL = 'https://wetransfer.com/api/v4/transfers/';

        try {

            if (strpos($url, 'https://we.tl/') !== false) {
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0"); // Necessary. The server checks for a valid User-Agent.
                curl_exec($ch);

                $response = curl_exec($ch);
                preg_match_all('/^Location:(.*)$/mi', $response, $matches);
                curl_close($ch);

                if(isset($matches[1])){
                    if(isset($matches[1][0])){
                        $url = trim($matches[1][0]);
                    }
                }

            }

            $url = str_replace('https://wetransfer.com/downloads/', '', $url);
            //making array from url
            $dataArray = explode('/', $url);

            if(count($dataArray) == 2){
                $securityhash = $dataArray[1];
                $transferId = $dataArray[0];
            }elseif(count($dataArray) == 3){
                $securityhash = $dataArray[2];
                $recieptId = $dataArray[1];
                $transferId = $dataArray[0];
            }else{
                die('Something is wrong with url');
            }

            // $header = getCsrfFromWebsite();

            //making post request to get the url
            $data = array();
            $data['intent']        = 'entire_transfer';
            $data['security_hash'] = $securityhash;

            $curlURL = $WETRANSFER_API_URL.$transferId.'/download'; 

            $cookie = "cookie.txt";
            $url    = 'https://wetransfer.com/';
            $ch     = curl_init();

            curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_COOKIESESSION, true);
            curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/'.$cookie);
            curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/'.$cookie);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) die(curl_error($ch));

            $re = '/name="csrf-token" content="([^"]+)"/m';

                preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);

                if(count($matches) != 0){
                    if(isset($matches[0])){
                        if(isset($matches[0][1])){
                            $token = $matches[0][1];
                        }
                    }
                }

            $headers[] = 'Content-Type: application/json';
            $headers[] = 'X-CSRF-Token:' .  $token;

            curl_setopt($ch, CURLOPT_URL, $curlURL);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $real = curl_exec($ch);

            $real = json_decode( $real);

            $url = $real->direct_link; 
                
            $extension = pathinfo( $url, PATHINFO_EXTENSION);

            // Use basename() function to return the base name of file  
            $file_name = basename( parse_url($url)['path'] ); 
            
            if (!file_exists(public_path('wetransfer'))) {
                mkdir(public_path('wetransfer'), 0777, true);
            }
            $file = file_put_contents( public_path( 'wetransfer/'.$file_name ), file_get_contents($url));
             
            return $file_name;

        } catch (\Throwable $th) {
            return false;  
        }
        return false;
    }

    public static function downloadFromURL($url, $supplier)
    {
        $WETRANSFER_API_URL = 'https://wetransfer.com/api/v4/transfers/';



        if (strpos($url, 'https://we.tl/') !== false) {
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Feirefox/21.0"); // Necessary. The server checks for a valid User-Agent.
            curl_exec($ch);

            $response = curl_exec($ch);
            preg_match_all('/^Location:(.*)$/mi', $response, $matches);
            curl_close($ch);

            if(isset($matches[1])){
                if(isset($matches[1][0])){
                    $url = trim($matches[1][0]);
                }
            }

        }

        //replace https://wetransfer.com/downloads/ from url

        $url = str_replace('https://wetransfer.com/downloads/', '', $url);

        //making array from url

        $dataArray = explode('/', $url);

        if(count($dataArray) == 2){
            $securityhash = $dataArray[1];
            $transferId = $dataArray[0];
        }elseif(count($dataArray) == 3){
            $securityhash = $dataArray[2];
            $recieptId = $dataArray[1];
            $transferId = $dataArray[0];
        }else{
            die('Something is wrong with url');
        }




        //making post request to get the url
        $data = array();
        $data['intent'] = 'entire_transfer';
        $data['security_hash'] = $securityhash;

        $curlURL = $WETRANSFER_API_URL.$transferId.'/download'; 

          $cookie= "cookie.txt";
          $url='https://wetransfer.com/';
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_COOKIESESSION, true);
          curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/'.$cookie);
          curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/'.$cookie);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          if (curl_errno($ch)) die(curl_error($ch));

          $re = '/name="csrf-token" content="([^"]+)"/m';

            preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);

            if(count($matches) != 0){
                if(isset($matches[0])){
                    if(isset($matches[0][1])){
                        $token = $matches[0][1];
                    }
                }
            }

          $headers[] = 'Content-Type: application/json';
          $headers[] = 'X-CSRF-Token:' .  $token;

          curl_setopt($ch, CURLOPT_URL, $curlURL);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);   
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

          $real = curl_exec($ch);

          $urlResponse = json_decode($real);

          //dd($urlResponse);

            if(isset($urlResponse->direct_link)){
                //echo $real;
                $downloadURL = $urlResponse->direct_link;
                
                $d = explode('?',$downloadURL);

                $fileArray = explode('/',$d[0]);

                $filename = end($fileArray);

                $file = file_get_contents($downloadURL);

                \Storage::put($filename,$file);

                $storagePath  = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                
                $path = $storagePath."/".$filename;
                
                $get = \Storage::get($filename);  
                    
                    if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                        
                        if(strpos($filename, '.zip') !== false){
                            $attachments = ErpExcelImporter::excelZipProcess($path, $filename , $supplier, '', '');
                        }


                        if(strpos($filename, '.xls') !== false || strpos($filename, '.xlsx') !== false){
                            if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                $excel = $supplier;
                                ErpExcelImporter::excelFileProcess($path, $filename,'');
                            }
                        }



                    }           
                return true;
            }
        return false;
    }
}
