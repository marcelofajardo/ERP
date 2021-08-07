<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */

use Illuminate\Database\Eloquent\Model;

class ErpEvents extends Model
{
    /**
     * @var string
     * @SWG\Property(property="event_name",type="string")
     * @SWG\Property(property="event_description",type="integer")
     * @SWG\Property(property="start_date",type="datetime")
     * @SWG\Property(property="end_date",type="datetime")
     * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="brand_id",type="integer")
     * @SWG\Property(property="category_id",type="integer")
     * @SWG\Property(property="number_of_person",type="integer")
     * @SWG\Property(property="product_start_date",type="datetime")
     * @SWG\Property(property="product_end_date",type="datetime")
     * @SWG\Property(property="minute",type="float")
     * @SWG\Property(property="hour",type="integer")
     * @SWG\Property(property="day_of_month",type="integer")
     * @SWG\Property(property="month",type="integer")
     * @SWG\Property(property="day_of_week",type="integer")
     * @SWG\Property(property="next_run_date",type="datetime")
     * @SWG\Property(property="is_closed",type="boolean")
     * @SWG\Property(property="created_by",type="integer")

     */

    protected $fillable = [
        "event_name",
        "event_description",
        "start_date",
        "end_date",
        "type",
        "brand_id",
        "category_id",
        "number_of_person",
        "product_start_date",
        "product_end_date",
        "minute",
        "hour",
        "day_of_month",
        "month",
        "day_of_week",
        "next_run_date",
        "is_closed",
        "created_by"
    ];

}
