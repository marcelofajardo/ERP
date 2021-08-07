<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
	 /**
     * @var string
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="reply",type="string")
     * @SWG\Property(property="model",type="string")
     * @SWG\Property(property="deleted_at",type="datetime")
     */
    
    use SoftDeletes;

	  protected $fillable = ['category_id','store_website_id', 'reply', 'model'];
	
    protected $dates = ['deleted_at'];

    public function category() {
      return $this->belongsTo('App\ReplyCategory', 'category_id');
    }
}
