<?php

namespace App\Loggers;

use Illuminate\Database\Eloquent\Model;

class LogTineye extends Model
{
    protected $table = 'log_tineye';

    public static function log($url, $result)
    {
        // Log result to database
        $logTineye = new LogTineye();
        $logTineye->image_url = $url;
        $logTineye->md5 = md5(file_get_contents($url));
        $logTineye->response = $result;
        $logTineye->save();

        // Return
        return;
    }
}
