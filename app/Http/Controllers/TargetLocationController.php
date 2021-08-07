<?php

namespace App\Http\Controllers;

use App\InstagramUsersList;
use App\TargetLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = TargetLocation::all();

        return view('instagram.location.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'country' => 'required',
            'region' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);



        $location = new TargetLocation();
        $location->country = $request->get('country');
        $location->region = $request->get('region');
        $polyY = explode(',', $request->get('lat'));
        $polyX = explode(',', $request->get('lng'));
        $location->region_data = [$polyX,$polyY];

        $location->save();

        return redirect()->back()->with('message', 'Location added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TargetLocation  $targetLocation
     * @return \Illuminate\Http\Response
     */
    public function show(TargetLocation $targetLocation)
    {
        return view('instagram.location.show', compact('targetLocation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TargetLocation  $targetLocation
     * @return \Illuminate\Http\Response
     */
    public function edit($review)
    {
        $stats = DB::table('instagram_users_lists')
            ->select(DB::raw('COUNT(`instagram_users_lists`.`id`) AS count, `target_locations`.`id` as location_id, `target_locations`.`country`, `target_locations`.`region`'))
            ->leftJoin('target_locations', 'instagram_users_lists.location_id', '=', 'target_locations.id')
            ->groupBy('location_id')->get()->toArray();

        $data = [];
        $labels = [];
        foreach ($stats as $stat) {
            $data[] = $stat->count;
            $labels[] = "\"$stat->country ($stat->region)\"";
        }

        $data = implode(', ', $data);
        $labels = implode(', ', $labels);

        return view('instagram.location.report', compact('data', 'labels', 'stats'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TargetLocation  $targetLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TargetLocation $targetLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TargetLocation  $targetLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(TargetLocation $targetLocation)
    {
        //
    }
}
