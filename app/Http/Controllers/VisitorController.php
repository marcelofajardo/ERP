<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VisitorLog;
use App\Setting;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
    	 if ($request->ip || $request->browser || $request->location) {

            $query = VisitorLog::query();

            if (request('ip') != null) {
                $query->where('ip', 'LIKE', "%{$request->ip}%");
            }

            if (request('browser') != null) {
                $query->where('browser', 'LIKE', "%{$request->browser}%");
            }

            if (request('location') != null) {
                $query->where('browser', 'LIKE', "%{$request->location}%");
            }

            //  if (request('log_created') != null) {
            //     $query->whereDate('log_created',request('log_created'));
            // }

            // if (request('created') != null) {
            //     $query->whereDate('created_at', request('created'));
            // }

            // if (request('updated') != null) {
            //     $query->whereDate('updated_at', request('updated'));
            // }

            // if(request('orderCreated') != null){
            //     if(request('orderCreated') == 0){
            //         $query->orderby('created_at','asc');
            //     }else{
            //         $query->orderby('created_at','desc');
            //     }
            // }

            // if(request('orderUpdated') != null){
            //     if(request('orderUpdated') == 0){
            //         $query->orderby('updated_at','asc');
            //     }else{
            //         $query->orderby('updated_at','desc');
            //     }
            // }

            // if(request('orderCreated') == null && request('orderUpdated') == null){
            //     $query->orderby('log_created','desc');
            // }

            $paginate = (Setting::get('pagination') * 10);
            $logs = $query->paginate($paginate)->appends(request()->except(['page']));
        }
        else {

             $paginate = (Setting::get('pagination') * 10);
            $logs = VisitorLog::orderby('created_at','desc')->paginate($paginate);

        }

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('logging.partials.visitordata', compact('logs'))->render(),
                'links' => (string)$logs->render(),
                'count' => $logs->total(),
            ], 200);
        }

    	return view('logging.visitorlog',compact('logs'));
    }
}
