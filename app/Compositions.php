<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

class Compositions extends Model
{

    /**
     * @var string
   
     * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="replace_with",type="string")
     */
    //
    protected $fillable = [
        'name',
        'replace_with',
    ];

    public static function getErpName($name)
    {
        $parts = preg_split('/\s+/', trim($name));
        
        $mc = self::query();
        if(!empty($parts))  {
            foreach($parts as $p){
                $p = str_replace("%", "", $p);
                if(!empty($p)) {
                    $mc->orWhere("name","like","%".trim($p)."%");
                }
            }
        }
        $mc = $mc->groupBy('name')->get(['name', 'replace_with']);

        $isReplacementFound = false;
        if (!$mc->isEmpty() && !empty($name)) {
            foreach ($mc as $key => $c) {
                // check if the full replacement found then assign from there
                if (strtolower($name) == strtolower($c->name) && !empty($c->replace_with)) {
                    return $c->replace_with;
                }

                foreach($parts as $p) {
                    if (strtolower($p) == strtolower($c->name) && !empty($c->replace_with)) {
                        $name = str_replace($p, $c->replace_with, $name);
                        $isReplacementFound = true;
                    }
                }
            }
        }

        // check if replacement found then assing that to the composition otherwise add new one and start next process
        if($isReplacementFound) {
            $checkExist = self::where('name','like',$name)->first();
            if($checkExist && !empty($checkExist->replace_with)) {
                return $checkExist->replace_with;
            }
        }

        // in this case color refenrece we don't found so we need to add that one
        if(!empty($name)) {
            $compositionModel = self::where('name',$name)->first();
            if(!$compositionModel) {
                self::create([
                    'name'         => $name,
                    'replace_with' => '',
                ]);
            }
        }

        // Return an empty string by default
        return '';
    }

    public static function products($name)
    {
        return \App\ScrapedProducts::where('composition',"LIKE",$name)->count();
    }

        public function productCounts()
        {
            return $this->hasMany(ScrapedProducts::class, 'composition', 'name');
        }
}
