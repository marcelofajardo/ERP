<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Mediables extends Model
{
	/**
     * @var string
     * @SWG\Property(property="mediables",type="string")
     */
    public $table = "mediables";

    public static function getMediasFromProductId($product_id)
    {
       $columns = array('directory','filename','extension','disk','created_at');

       return  \App\Mediables::leftJoin("media as m",function($query){
                        $query->on("media_id","m.id");
                    })->where("mediable_id",$product_id)->where("mediable_type",\App\Product::class)->get($columns);
    }
}
