<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
	/**
     * @var string
   * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="chatbot_keyword_value_id",type="integer")

     */
    public $table = "courier";
    protected $fillable = [
        'name',
    ];
}
