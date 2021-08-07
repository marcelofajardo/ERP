/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\InstagramPosts;
use App\Account;

class InstagramCommentQueue extends Model
{
    public function getPost()
    {
    	return $this->hasOne(InstagramPosts::class,'id','post_id');
    }

    public function account()
    {
    	return $this->hasOne(Account::class,'id','account_id');
    }


}
