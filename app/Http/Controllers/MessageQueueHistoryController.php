<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessageQueueHistory;
use Carbon\Carbon;


class MessageQueueHistoryController extends Controller
{
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $title = "List | Message Queue History";

        return view('custom-chat-message.index', compact('title'));
    }

    public function records(Request $request)
    {
        $keyword = $request->get("keyword");

        $records = MessageQueueHistory::with('user');

        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("number", "LIKE", "%$keyword%");
            });
        }

        $records = $records->latest('time')->paginate(12);

        $recorsArray = [];

        foreach ($records as $row) {

            $recorsArray[] = [
                'id'         => $row->id,
                'number'     => $row->number,
                'counter'    => $row->counter,
                'type'       => $row->type, 
                'user_id'    => $row->user_id??'-', 
                'time'       => Carbon::parse($row->time)->format('d-m-y H:i:s'),
            ];
        }    

        return response()->json([
            "code"       => 200,
            "data"       => $recorsArray,
            "pagination" => (string) $records->links(),
            "total"      => $records->total(),
            "page"       => $records->currentPage(),
        ]);
        
    }

    // public function getLoadDataValue(Request $request)
    // {
        
    //     $records = MessageQueueHistory::where('id',$request->id)->first();

    //     $fulltextvalue = $records[$request->field];
        
    //     return response()->json(["code" => 200, "data" => $fulltextvalue]);
    // }
}
