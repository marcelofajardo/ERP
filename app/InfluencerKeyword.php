<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class InfluencerKeyword extends Model
{
    protected $fillable = [
        'name',
        'instagram_account_id',
    ];

    public function next()
    {
        // get next keyword
        return InfluencerKeyword::where('id', '>', $this->id)->orderBy('id', 'asc')->first();

    }
    public function previous()
    {
        // get previous  keyword
        return InfluencerKeyword::where('id', '<', $this->id)->orderBy('id', 'desc')->first();

    }

    public function instagramAccount()
    {
        return $this->hasOne(\App\Account::class,'id','instagram_account_id');
    }
}
