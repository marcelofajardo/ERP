<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreWebsiteProductCheck extends Model
{
   // use SoftDeletes;
   protected $fillable =  ['website_id' ,
   'website' ,
   'sku' ,
   'size' ,
   'brands' ,
   'dimensions' ,
   'composition' ,
   //'images' => $value->composition,
   'english'=>'Yes',
   'arabic'=>'Yes',
   'german'=>'Yes',
   'spanish'=>'No',
   'french'=>'No',
   'italian'=>'No',
   'japanese'=>'No',
   'korean'=>'No',
   'russian'=>'No',
   'chinese'=>'No'];
}