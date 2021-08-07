<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="ScrapLog"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrapLog extends Model
{
    /**
     * @var string
     * @SWG\Property(property="scraper_id",type="integer")
     * @SWG\Property(property="folder_name",type="string")
     * @SWG\Property(property="file_name",type="string")
     * @SWG\Property(property="log_messages",type="string")
     */

    protected $fillable = [
        'scraper_id', 'folder_name', 'file_name', 'log_messages',
    ];

}
