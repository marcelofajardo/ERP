<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;
class ResourceImage extends Model{
  /**
     * @var string
     * @SWG\Property(property="cat_id",type="integer")
     * @SWG\Property(property="image1",type="string")
     * @SWG\Property(property="image",type="string")
     * @SWG\Property(property="is_pending",type="boolean")
     * @SWG\Property(property="sub_cat_id",type="interger")
     * @SWG\Property(property="is_rejected",type="boolean")
     */
  protected $fillable = ['cat_id','image1','image','is_pending','sub_cat_id'];

  public function category(){
    return $this->hasOne('App\ResourceCategory','id','cat_id');
  }

  public function sub_category(){
    return $this->hasOne('App\ResourceCategory','id','sub_cat_id');
  }


  static public function create($input){
    //dd($input);
    $resourceimg = new ResourceImage;
    $resourceimg->cat_id = $input['cat_id'];
    $resourceimg->sub_cat_id = $input['sub_cat_id'];
    $resourceimg->images = @$input['images'];
    $resourceimg->url = @$input['url'];
    $resourceimg->description = @$input['description'];
    $resourceimg->created_at = date("Y-m-d H:i:s");
    $resourceimg->updated_at = date("Y-m-d H:i:s");
    $resourceimg->created_by = Auth::user()->name;
    return $resourceimg->save();
  }

  static public function getData(){
    $allresources = ResourceImage::get();
    $dataArray=array();
    $title="";
    if($allresources){
      foreach ($allresources as $key => $resources) {
        $categories = ResourceCategory::where('id','=',$resources->cat_id)->get()->first();
        $parent_id = $categories->parent_id;
        $id = $categories->id;
        if($parent_id == 0){
          $title = $categories->title;
          $subcat = '';
        }else{
          $titlestr=array();
          while ($parent_id != 0) {
            $categories = ResourceCategory::where('id','=',$id)->get()->first();
            $titlestr[]= $categories->title;
            $id = $parent_id = $categories->parent_id;
          }
          try{
               krsort($titlestr);
               $subcat = $titlestr[0];
           }catch (\Exception $e){
               $subcat = '';
           }
         
          
        }
        $dataArray[]=array('id'=>$resources->id,
                           'cat'=>$title,
                           'sub_cat'=>$subcat,
                           'cat_id'=>$resources->cat_id,
                           'url' => $resources->url,
                           'description' => $resources->description,
                           'created_at'=>$resources->created_at,
                           'updated_at'=>$resources->updated_at,
                           'created_by'=>$resources->created_by);
      }
    }
    return $dataArray;
    // echo "<pre>"; print_r($dataArray); die;
  }

}
