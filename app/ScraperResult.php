<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class ScraperResult extends Model
{
	/**
     * @var string
     * @SWG\Property(property="scraper_name",type="string")
     * @SWG\Property(property="total_urls",type="string")
     * @SWG\Property(property="existing_urls",type="string")
     * @SWG\Property(property="new_urls",type="string")
     * @SWG\Property(property="date",type="datetime")
     */
    protected $fillable = ['date','scraper_name','total_urls','existing_urls','new_urls'];

}
