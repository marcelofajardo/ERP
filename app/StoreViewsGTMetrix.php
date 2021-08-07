<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreViewsGTMetrix extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'store_views_gt_metrix';

    protected $fillable = [
        'store_view_id', 
        'test_id',
        'status',
        'error',
        'report_url',
        'website_url',
        'html_load_time',
        'html_bytes',
        'page_load_time',
        'page_bytes',
        'page_elements',
        'pagespeed_score',
        'yslow_score',
        'resources'
    ];

     protected $casts = [
        'resources' => 'array',
    ];
}
