<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SSP;
use App\Keywordassign;
use Exception;
use DB;
use App\KeywordAutoGenratedMessageLog;//Purpose : add model - DEVTASK-4233

class KeywordassignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$coupons = Coupon::orderBy('id', 'DESC')->get();
        //$keywordassign = DB::table('keywordassign')->select('*')->get();
        $keywordassign = DB::table('keywordassigns')
            ->select('keywordassigns.id','keywordassigns.keyword','task_categories.title','keywordassigns.task_description','users.name')
            ->leftJoin('users', 'keywordassigns.assign_to', '=', 'users.id')
            ->leftJoin('task_categories', 'keywordassigns.task_category', '=', 'task_categories.id')
            ->orderBy('id')
            ->get();

        return view('keywordassign.index', compact('keywordassign'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $task_category = DB::table('task_categories')->select('*')->get();
        $userslist = DB::table('users')->select('*')->get();
        return view('keywordassign.create', compact('task_category','userslist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = $this->validate(request(), [
            'keyword' => 'required',
            'task_category' => 'required',
            'task_description' => 'required',
            'assign_to' => 'required'
        ]);
        // Create the task
        $exp_keyword = explode(",", $request->keyword);
        $new_keywordstr = "";
        for($i=0;$i<count($exp_keyword);$i++)
        {
            $new_keywordstr.=trim($exp_keyword[$i]).",";
        }
        $keyword = trim($new_keywordstr,",");
        $task_category = $request->task_category;
        $task_description = $request->task_description;
        $assign_to = $request->assign_to;
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        $insert_data = array(
                "keyword"=>$keyword,
                "task_category"=>$task_category,
                "task_description"=>$task_description,
                "assign_to"=>$assign_to,
                "created_at"=>$created_at,
                "updated_at"=>$updated_at,
            );
        DB::table('keywordassigns')->insert($insert_data);
        return redirect()->route('keywordassign.index')
            ->with('success', 'Keyword assign created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $keywordassign = DB::table('keywordassigns')->select('*')->where('id',$id)->get();
        $task_category = DB::table('task_categories')->select('*')->get();
        $userslist = DB::table('users')->select('*')->get();
        return view('keywordassign.edit', compact('keywordassign','task_category','userslist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = $this->validate(request(), [
            'keyword' => 'required',
            'task_category' => 'required',
            'task_description' => 'required',
            'assign_to' => 'required'
        ]);
        // Create the task
        $keyword = $request->keyword;
        $task_category = $request->task_category;
        $task_description = $request->task_description;
        $assign_to = $request->assign_to;
        $updated_at = date("Y-m-d H:i:s");
        $insert_data = array(
                "keyword"=>$keyword,
                "task_category"=>$task_category,
                "task_description"=>$task_description,
                "assign_to"=>$assign_to,
                "updated_at"=>$updated_at,
            );
        $affected = DB::table('keywordassigns')
              ->where('id', $id)
              ->update($insert_data);
        return redirect()->route('keywordassign.index')
            ->with('success', 'Keyword assign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('keywordassigns')->where('id', '=', $id)->delete();
        return redirect()->route('keywordassign.index')
            ->with('success', 'Keyword assign deleted successfully.');
    }
    public function taskcategory(Request $request)
    {
        $task_category_name = $request->task_category_name;
        $insert_data = array(
                "parent_id"=>0,
                "title"=>$task_category_name,
            );
        DB::table('task_categories')->insert($insert_data);
        $id = DB::getPdo()->lastInsertId();
        return response()->json(["code" => 200 , "data" => ['id'=>$id,'Category'=>$task_category_name], "message" => "Task Category Inserted"]);
    }

    //START - Purpose : create function for get data - DEVTASK-4233
    PUBLIC FUNCTION keywordreponse_logs(Request $request){
        try{

            $query = KeywordAutoGenratedMessageLog::orderBy('id', 'DESC');

            if($request->get('keyword') != ''){
                $keywordlogs = $query->where('keyword', 'like', '%' . $request->get('keyword') . '%');
            }

            if($request->get('keyword_duedate') != ''){
                $keywordlogs = $query->whereDate('created_at', '=', $request->get('keyword_duedate'));
            }
            
            $keywordlogs = $query->paginate(30);
            return view('keywordassign.logs',compact('keywordlogs','request'));
        }catch(\Exception $e){
           
        }
    }
    //END - DEVTASK-4233
}
