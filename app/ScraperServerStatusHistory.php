<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class ScraperServerStatusHistory extends Model
{
    protected $fillable = [
        'scraper_name',
        'scraper_string',
        'server_id',
        'start_time',
        'total_memory',
        'used_memory',
        'in_percentage',
        'pid',
        'duration'
    ];

    public static function runOnGiveTime($date, $time)
    {
        $start = $date . " " . $time . ":00:00";
        $end   = date("Y-m-d H:i:s", strtotime($date . " " . $time . ":00:00 +1 hour"));

        return self::where('created_at', ">=", $start)->where("created_at", "<=", $end)->get();
    }

}
