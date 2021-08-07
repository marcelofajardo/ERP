<?php

namespace App\Http\Controllers;

use App\TaskCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageTaskCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Manage Task Category";

        return view("manage-task-category.index", compact('title'));

    }

    public function records()
    {
        $records = \App\TaskCategory::query();

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("title", "LIKE", "%$keyword%");
            });
        }

        $records = $records->get();

        return response()->json(["code" => 200, "data" => $records, "total" => count($records)]);
    }

    public function save(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }

        $id = $request->get("id", 0);

        $records = TaskCategory::find($id);

        if (!$records) {
            $records = new TaskCategory;
        }

        $records->fill($post);
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);

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
            'title' => 'required|string',
        ]);

        $data = $request->except('_token');

        TaskCategory::create($data);

        return redirect()->back();
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
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $modal = TaskCategory::where("id", $id)->first();

        if ($modal) {
            return response()->json(["code" => 200, "data" => $modal]);
        }

        return response()->json(["code" => 500, "error" => "Id is wrong!"]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $taskCategory = TaskCategory::where("id", $id)->first();

        $isExist = \App\Task::where("category", $id)->first();
        if ($isExist) {
            return response()->json(["code" => 500, "error" => "Category is assigned to task , Please update task before delete."]);
        }

        if ($taskCategory) {
            $taskCategory->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong id!"]);
    }

    public function mergeModule(Request $request)
    {
        $toCategory   = $request->get("to_category");
        $fromCategory = $request->get("from_category");

        if (empty($toCategory)) {
            return response()->json(["code" => 500, "error" => "Merge category is missing"]);
        }

        if (empty($fromCategory)) {
            return response()->json(["code" => 500, "error" => "Please select category before select merge category"]);
        }

        if (in_array($toCategory, $fromCategory)) {
            return response()->json(["code" => 500, "error" => "Merge category can not be same"]);
        }

        $taskCategory     = \App\TaskCategory::where("id", $toCategory)->first();
        $allMergeCategory = \App\Task::whereIn("category", $fromCategory)->get();

        if ($taskCategory) {
            // start to merge first
            if (!$allMergeCategory->isEmpty()) {
                foreach ($allMergeCategory as $amc) {
                    $amc->category = $taskCategory->id;
                    $amc->save();
                }
            }
            // once all merged category store then delete that category from table
            \App\TaskCategory::whereIn("id", $fromCategory)->delete();
        }

        return response()->json(["code" => 200, "data" => [], "messages" => "Category has been merged successfully"]);
    }

}
