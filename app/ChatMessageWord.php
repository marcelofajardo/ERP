<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatMessageWord extends Model
{
	/**
     * @var string
     * @SWG\Property(property="word",type="string")
      * @SWG\Property(property="total",type="integer")
     */
    protected $fillable = [
        'word', 'total',
    ];

    public function pharases()
    {
    	return $this->hasMany('App\ChatMessagePhrase','word_id','id');
    }
}
