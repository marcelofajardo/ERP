<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class StoreWebsiteGoalRemark extends Model
{
		/**
     * @var string
    
      * @SWG\Property(property="store_website_id",type="integer")
     * @SWG\Property(property="remark",type="string")
      * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = [
        'remark', 'store_website_goal_id', 'created_at', 'updated_at',
    ];
}
