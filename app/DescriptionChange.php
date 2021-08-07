<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DescriptionChange extends Model
{
         /**
     * @var string
   * @SWG\Property(property="keyword",type="string")
   * @SWG\Property(property="replace_with",type="string")
     */
    protected $fillable = [
        'keyword',
        'replace_with',
    ];

    public static function getErpName($name)
    {
        $mc = self::all();
        $text = $name;
        foreach ($mc as $replace) {
            if(strpos($name,$replace->keyword) !== false){
                $text = str_replace(strtolower($replace->keyword), strtolower($replace->replace_with), strtolower($name));
            }
            # code...
        }
        return ucwords($text);
    }
}
