<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScrapRequestHistory extends Model
{
		/**
     * @var string
     * @SWG\Property(property="scrap_request_histories",type="string")
     * @SWG\Property(property="scraper_id",type="integer")
     * @SWG\Property(property="date",type="datetime")
     * @SWG\Property(property="start_time",type="datetime")
     * @SWG\Property(property="end_time",type="datetime")
     * @SWG\Property(property="request_sent",type="string")
     * @SWG\Property(property="request_failed",type="string")
     */
    protected $table = 'scrap_request_histories';

    protected $fillable = ['scraper_id', 'date', 'start_time', 'end_time','request_sent','request_failed'];
}
