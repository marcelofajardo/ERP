<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SearchQueueController extends Controller
{
  private $supported_file_types;

  public function __construct(){
    $this->supported_file_types=['png','jpg','jpeg','gif'];
  }


  /**
   * @SWG\Get(
   *   path="/search/{type}",
   *   tags={"Search"},
   *   summary="get search",
   *   operationId="get-search",
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
  public function index($type){
      $validator=Validator::make(['search_type'=>$type], [
        'search_type' =>'required|exists:search_queues,search_type'
      ]);
  
      if($validator->fails()) {
        $response['success']=false;
        $response['message']=$validator->errors()->first();
        
        return response()->json($response,400);
      }

      try{
        $list=\App\SearchQueue::paginate();
        $response['success']=true;
        $response['message']="success";
        $response['data']=$list;
        
        return response()->json($response,200);
      }catch(\Exception $e){
        $response['success']=false;
        $response['message']=$e->getMessage();
        
        return response()->json($response,500);
      }

      //echo "<pre>";print_r($list);exit;
  }

  /**
   * @SWG\Post(
   *   path="/search/{type}",
   *   tags={"Search"},
   *   summary="upload content",
   *   operationId="upload-content",
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
  public function upload_content(Request $request){
    set_time_limit(0);
    $validator=Validator::make($request->all(), [
      'list'=>'required|array|min:1',
      'list.*.id'=>'required|exists:search_queues,id',
      'list.*.upload_path'=>'required|string|min:1',
      'list.*.links'=>'required|array|min:1',
      'list.*.links.*'=>'required|url',
    ]);

    if($validator->fails()) {
      $response['success']=false;
      $response['message']=$validator->errors()->first();
      
      return response()->json($response,400);
    }

    try{
      $count=0;
      foreach($request->list as $item){
        $queue=\App\SearchQueue::find($item['id']);
        
        if($queue){
          ///// Check requested upload path //////
          $valid_path=$this->validate_upload_path($item['upload_path']);
          if($valid_path){
            foreach($item['links'] as $key=>$link){
              $is_valid_type=$this->get_file_extension($link);
              if($is_valid_type!=false){
                $upload_filename=$queue->id.$key."_".time().".".$is_valid_type;
                $upload_path=public_path().'/'.$item['upload_path'];
                
                $upload_image=new $queue->model_name;
                $upload_response=$upload_image->saveFromSearchQueues($upload_path,$link,$upload_filename);
                if($upload_response==true){
                  //$upload_image->filename=$item['upload_path'].'/'.$upload_filename;
                  $upload_image->filename=$upload_filename;
                  $upload_image->status=1;
                  $upload_image->created_at=$upload_image->updated_at=time();
                  if($upload_image->save()){
                    $count++;
                  }
                }
              }
            }
            $queue->delete();
          }
        }
      }
    }catch(\Exception $e){
      $response['success']=false;
      $response['message']=$e->getMessage();
      
      return response()->json($response,500);
    }
    $response['success']=true;
    $response['message']=$count." files uploaded successfuly";
    
    return response()->json($response,200);
  }

  private function validate_upload_path($path){
    $path=public_path().'/'.$path;
    if (!File::exists($path))
    {
        $result=File::makeDirectory($path, $mode = 0755, true, true);
        if($result){
          return true;
        }else{
          return false;
        }
    }else{
      return true;
    }
  }

  private function get_file_extension($link){
    $link=explode('.',$link);
    $link=$link[sizeof($link)-1];

    if(in_array($link,$this->supported_file_types)){
      return $link;
    }else{
      return false;
    }
  }


}
