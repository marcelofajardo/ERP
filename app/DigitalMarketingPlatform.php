<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */


use Illuminate\Database\Eloquent\Model;

class DigitalMarketingPlatform extends Model
{

 /**
   * @SWG\Property(property="platform",type="string")
   * @SWG\Property(property="sub_platform",type="string")
   * @SWG\Property(property="description",type="string")
   * @SWG\Property(property="status",type="string")
   * @SWG\Property(property="created_at",type="datetime")
   * @SWG\Property(property="updated_at",type="datetime")
        */


    const STATUS = [
        0 => "Draft",
        1 => "Active",
        2 => "Inactive",
        3 => "Planned",
        4 => "Do not need",
    ];

    protected $fillable = [
        'platform',
        'sub_platform',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    public function solutions()
    {
        return $this->hasMany("\App\DigitalMarketingSolution", "digital_marketing_platform_id", "id");
    }

    public function components()
    {
        return $this->hasMany("App\DigitalMarketingPlatformComponent", "digital_marketing_platform_id", "id");
    }
}
