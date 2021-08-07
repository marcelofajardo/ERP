<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Translations extends Model 
{
            /**
     * @var string
      * @SWG\Property(property="text",type="string")
      * @SWG\Property(property="text_original",type="string")
      * @SWG\Property(property="from",type="string")
      * @SWG\Property(property="to",type="string")
      * @SWG\Property(property="created_at",type="datetime")
      * @SWG\Property(property="updated_at",type="datetime")
 
     */
    /**
     * Fillables for the database
     *
     * @access protected
     *
     * @var array $fillable
     */
    protected $fillable = [
        'text',
        'text_original',
        'from',
        'to'
    ];

    /**
     * Protected Date
     *
     * @access protected
     * @var    array $dates
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * This static method will create new translation
     *
     * @param String $textOriginal
     * @param String $text
     * @param String $from
     * @param String $to
     *
     * @return bool 
     */
    public static function addTranslation($textOriginal, $text, $from, $to) {
        $obj = new Translations();
        $obj->text_original = $textOriginal;
        $obj->text = $text;
        $obj->from = $from;
        $obj->to = $to;

        $obj->save();
    }
}