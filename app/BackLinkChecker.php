<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
class BackLinkChecker extends Model
{
    /**
     * Fillables for the database
     *
     * @access protected
     *
     * @var array $fillable
     */
    /**
     * @var string
     * @SWG\Property(property="domains",type="string")
     * @SWG\Property(property="links",type="string")
     * @SWG\Property(property="link_type",type="string")
     * @SWG\Property(property="review_numbers",type="integer")
     * @SWG\Property(property="rank",type="string")
     * @SWG\Property(property="rating",type="integer")
     * @SWG\Property(property="serp_id",type="integer")
     * @SWG\Property(property="snippet",type="string")
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="visible_link",type="string")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     */
    protected $fillable = array(
        'domains', 'links', 'link_type',
        'review_numbers', 'rank',
        'rating', 'serp_id', 'snippet',
        'title', 'visible_link'
    );

    /**
     * Protected Date
     *
     * @access protected
     * @var    array $dates
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
