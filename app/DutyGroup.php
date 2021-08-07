<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class DutyGroup extends Model
{
      /**
   * @SWG\Property(property="name",type="string")
   * @SWG\Property(property="hs_code",type="string")
   * @SWG\Property(property="duty",type="string")
   * @SWG\Property(property="vat",type="string")
   * @SWG\Property(property="created_at",type="datetime")
   * @SWG\Property(property="updated_at",type="datetime")

        */
    protected $fillable = [
        'name',
        'hs_code',
        'duty',
        'vat',
        'created_at',
        'updated_at',
    ];

    public static function selectList()
    {
        return self::pluck('name', 'id')->toArray();
    }

}
