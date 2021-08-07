<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="AutoRefreshPage"))
 */
use Illuminate\Database\Eloquent\Model;

class AutoRefreshPage extends Model
{
    protected $fillable = [
        'page',
        'time',
        'user_id'
    ];

    public function user()
    {
        return $this->hasOne(\App\User::class, 'id', 'user_id');
    }

}
