<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class GoogleAdsAccount extends Model
{
		   /**
     * @var string
     * @SWG\Property(property="googleadsaccounts",type="string")
     * @SWG\Property(property="account_name",type="string")
     * @SWG\Property(property="store_websites",type="string")
     * @SWG\Property(property="config_file_path",type="string")
     * @SWG\Property(property="notes",type="string")
     * @SWG\Property(property="status",type="string")
     */
    protected $table='googleadsaccounts';
    protected $fillable=['account_name','store_websites','config_file_path','notes','status'];
}
