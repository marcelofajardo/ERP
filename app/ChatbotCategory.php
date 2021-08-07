<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class ChatbotCategory extends Model
{
	/**
     * @var string
     * @SWG\Property(property="name",type="string")
     */
    protected $fillable = [
        'name'
    ];
}
