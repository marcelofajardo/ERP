<?php

namespace App\Http\Controllers;

use App\ErpEvents;

class ErpEventController extends Controller
{

    public function index()
    {
        $events = ErpEvents::all();

        $listEvents = [];

        if(!$events->isEmpty()) {
            foreach($events as $event) {
               $listEvents[] = [
                    "startDate" => date("Y-m-d",strtotime($event->start_date)),
                    "endDate" => date("Y-m-d",strtotime($event->end_date)),
                    "title" => $event->event_name,
               ];     
            }
        }


        return view("erp-events.index", compact('events','listEvents'));

        /*$events = ErpEvents::all();

    if (!$events->isEmpty()) {
    foreach ($events as $event) {
    try{
    $cron = CronExpression::factory("$event->minute $event->hour $event->day_of_month $event->month $event->day_of_week");
    echo $cron->getNextRunDate()->format('Y-m-d H:i:s');die;
    if ($cron->isDue()) {
    $event->next_run_date = $cron->getNextRunDate()->format('Y-m-d H:i:s');
    } else {
    $event->is_closed = 1;
    }
    }catch(\Exception $e) {
    $event->is_closed = 1;
    }

    $event->save();
    dd($event);
    }
    }*/
    }

    public function store()
    {
        $params                = request()->all();
        $params["brand_id"]    = implode(",", $params["brand_id"]);
        $params["category_id"] = implode(",", $params["category_id"]);
        $params["type"]        = 1;
        $params["created_by"]  = \Auth::id();
        $erpEvnts              = new ErpEvents();
        $erpEvnts->fill($params);
        $erpEvnts->save();

        return response()->json(["code" => 1]);
    }

    public function dummy()
    {
        $params = [
            "event_name"         => "Testing Event",
            "event_description"  => "This is test description",
            "start_date"         => "2019-12-04",
            "end_date"           => "2019-12-15",
            "type"               => "1",
            "brand_id"           => "1,2,3",
            "category_id"        => "10,38",
            "number_of_person"   => "20",
            "product_start_date" => "",
            "product_end_date"   => "",
            "minute"             => "0",
            "hour"               => "1",
            "day_of_month"       => "0",
            "month"              => "0",
            "day_of_week"        => "0",
            "created_by"         => "1",
        ];

        $erpEvnts = new ErpEvents();
        $erpEvnts->fill($params);
        $erpEvnts->save();

    }

}
