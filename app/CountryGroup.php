<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


class CountryGroup extends Model
{
    /**
     * @var string
    * @SWG\Property(property="name",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    public function groupItems()
    {
        return $this->hasMany("\App\CountryGroupItem","country_group_id","id");
    }

    public static function list()
    {
        return self::pluck("name","id")->toArray();
    }

}
