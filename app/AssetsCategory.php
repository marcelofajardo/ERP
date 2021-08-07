<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class AssetsCategory extends Model
{

  //use SoftDeletes;
  protected $table = 'assets_category';

 	/**
     * @var string
     * @SWG\Property(property="cat_name",type="string")
     */

  protected $fillable = [
    'cat_name'];


  /*public function Assetscat()
  {
    return $this->hasMany('App\AssetsManager', 'category_id')->where('id', 'App\AssetsCategory');
  }*/
}
