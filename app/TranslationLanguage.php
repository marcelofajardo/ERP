<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class TranslationLanguage extends Model
{
		/**
     * @var string
    
      * @SWG\Property(property="locale",type="string")
 
     */
    protected $fillable = [
        'locale'
    ];
}
