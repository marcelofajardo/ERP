<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nestable\NestableTrait;

class ResourceCategory extends Model{
  /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="title",type="string")
     */

  use NestableTrait;

  protected $parent = 'parent_id';

  public $fillable = ['title'];

  public function childs() {
    return $this->hasOne(__CLASS__,'parent_id','id') ;
  }

  public function parent()
  {
    return $this->hasOne('App\ResourceCategory', 'id', 'parent_id');
  }


  public static function isParent($id){

    $child_count = DB::table('resource_categories as c')
                        ->where('parent_id',$id)
              ->count();

    return $child_count ? true : false;
  }


  static public function create($input){
    $resourceimg = new ResourceCategory;
    $input['parent_id'] = $input['parent_id'] ?? 1;
    $resourceimg->parent_id = ($input['parent_id'] == 1 ?  0 : $input['parent_id']);
    $resourceimg->title = $input['title'];
    $resourceimg->is_active = "Y";
    $resourceimg->created_at = date("Y-m-d H:i:s");
    $resourceimg->updated_at = date("Y-m-d H:i:s");
    $resourceimg->created_by = Auth::user()->name;    
    return $resourceimg->save();
  	// echo "<pre>"; print_r($resourceimg);die("herer");
  }

  static public function getCategories(){
    return ResourceCategory::where('parent_id','=',0)->get();
  }

  static public function getSubCategories(){
    return ResourceCategory::where('parent_id','!=',0)->get();
  }

}
