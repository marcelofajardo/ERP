<?php

namespace App\Http\Controllers;

use App\UserLog;
use Illuminate\Http\Request;
use DataTables;
use Input;

class UserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.url-log');
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
     * @SWG\Post(
     *   path="/userLogs",
     *   tags={"Userlog"},
     *   summary="store user logs",
     *   operationId="save user logs",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = $request->url;
        $user_id = $request->user_id;
        $user_name = $request->user_name;

        $user_log = new UserLog();
            $user_log->user_id = $user_id;
            $user_log->url = $url;
            $user_log->user_name = $user_name;
            $user_log->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function show(UserLog $userLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function edit(UserLog $userLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserLog $userLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserLog  $userLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserLog $userLog)
    {
        //
    }

    public function getData(Request $request){
        $query = UserLog::query();

        if($request->from_date){
            $query = $query->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        if($request->id){
            $query = $query->where('user_name','LIKE','%'.$request->id.'%');
        }


        $userslogs = $query->select(['id', 'user_id', 'url', 'created_at','user_name', 'updated_at'])->orderBy('id','desc');


        return Datatables::of($userslogs)
        ->addColumn('user_name', function ($userslogs) {
            return '<button class="btn btn-sm yellow edit" onclick="usertype('.$userslogs->user_id .')">'.$userslogs->user_name .'</button>';
        })
        ->rawColumns(['user_name'])
        ->make(true);

         
     }
}
