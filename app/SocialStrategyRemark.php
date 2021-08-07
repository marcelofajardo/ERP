<?php
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialStrategyRemark extends Model
{
	   /**
     * @var string
     * @SWG\Property(property="remarks",type="string")
     * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="social_strategy_id",type="integer")
     */
    protected $fillable = ['remarks','user_id','social_strategy_id'];
}
