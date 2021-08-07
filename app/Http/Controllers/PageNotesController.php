<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Setting;//Purpose : Add Setting - DEVTASK-4289

//use Spatie\Permission\Models\Permission;
//use Spatie\Permission\Models\Role;

class PageNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // will use after review
        // $this->middleware('permission:role-list');
        // $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function create(Request $request)
    {

        $pageNotes          = new \App\PageNotes;

        if ($request->get("isUpdate")) {
            $pageNotes = \App\PageNotes::where('user_id', \Auth::user()->id)->whereDate('created_at', Carbon::today())->first();
            if (empty($pageNotes)) {
                $pageNotes = new \App\PageNotes;
            }
        }

        $pageNotes->user_id = \Auth::user()->id;
        $pageNotes->url     = $request->get("page", "");
        $pageNotes->category_id = $request->get("category_id", null);
        $pageNotes->note    = $request->get("note", "");

        if ($pageNotes->save()) {
        	$list = $pageNotes->getAttributes();
        	$list["name"] = $pageNotes->user->name;
            $list["category_name"] = !empty($pageNotes->pageNotesCategories->name) ? $pageNotes->pageNotesCategories->name : '';
            return response()->json(["code" => 1, "notes" => $list]);
        }

        return response()->json(["code" => -1, "message" => "oops, something went wrong!!"]);

    }

    public function list(Request $request)
    {
    	$pageNotes = \App\PageNotes::join('users', 'users.id', '=', 'page_notes.user_id')
        ->leftJoin('page_notes_categories', 'page_notes.category_id', '=', 'page_notes_categories.id')
    	->select(["page_notes.*","users.name", "page_notes_categories.name as category_name"])
        ->where("page_notes.user_id",\Auth::user()->id)
    	->where("url",$request->get("url"))
        ->orderBy("page_notes.id","desc")
    	->get()
    	->toArray();

    	return response()->json(["code" => 1, "notes" => $pageNotes]);
    }
    
    public function edit (Request $request)
    {
        $id = $request->get('id', 0);
        $pageNotes = \App\PageNotes::where('id', $id)->first();
        if ($pageNotes) {
            $category = \App\PageNotesCategories::pluck('name', 'id')->toArray();
            return view("pagenotes.edit", compact('pageNotes', 'category'));
        }
        return 'Page Note Not Found';
    }

    public function update(Request $request)
    {
        $id = $request->get('id', 0);
        $pageNotes = \App\PageNotes::where('id', $id)->first();
        if ($pageNotes) {
            $pageNotes->user_id = \Auth::user()->id;
            $pageNotes->category_id = $request->get("category_id", null);
            $pageNotes->note    = $request->get("note", "");

            if ($pageNotes->save()) {
                $list = $pageNotes->getAttributes();
                $list["name"] = $pageNotes->user->name;
                $list["category_name"] = !empty($pageNotes->pageNotesCategories->name) ? $pageNotes->pageNotesCategories->name : '';
                return response()->json(["code" => 1, "notes" => $list]);
            }

        }
        return response()->json(["code" => -1, "message" => "oops, something went wrong!!"]);

    }

    public function delete(Request $request)
    {
        $id = $request->get('id', 0);
        $pageNotes = \App\PageNotes::where('id', $id)->first();
        if ($pageNotes) {
            $pageNotes->delete();
            
            return response()->json(["code" => 1, "message" =>"success!"]);
        }
        return response()->json(["code" => -1, "message" => "oops, something went wrong!!"]);

    }

    public function index(Request $request)
    {
        //START - Purpose : Get Page Note - DEVTASK-4289
        $records = \App\PageNotes::join('users', 'users.id', '=', 'page_notes.user_id')
        ->leftJoin('page_notes_categories', 'page_notes.category_id', '=', 'page_notes_categories.id')
        ->where("page_notes.user_id",\Auth::user()->id)
        ->orderBy("page_notes.id","desc")
        ->select(["page_notes.*","users.name", "page_notes_categories.name as category_name"]);

        //START - Purpose : Add search - DEVTASK-4289
        if($request->search)
        {
            $search ='%'.$request->search.'%';
            $records= $records->where('page_notes.note','like',$search);
        }
        //END - DEVTASK-4289

        $records = $records->paginate(Setting::get('pagination'));

        // return view("pagenotes.index");
    	return view("pagenotes.index",compact('records'));
        //END - DEVTASK-4289
    }

    public function records()
    {
    	return datatables()->of(\App\PageNotes::join('users', 'users.id', '=', 'page_notes.user_id')
            ->leftJoin('page_notes_categories', 'page_notes.category_id', '=', 'page_notes_categories.id')
            ->where("page_notes.user_id",\Auth::user()->id)
            ->orderBy("page_notes.id","desc")
            ->select(["page_notes.*","users.name", "page_notes_categories.name as category_name"]
        )->get())->make();
    }

    public function instructionCreate(Request $request)
    {
        $page = \App\PageInstruction::where("page",$request->get("page"))->first();
        if(!$page) {
            $page = new \App\PageInstruction; 
        }

        $page->page = $request->get("page");
        $page->instruction = $request->get("note");
        $page->created_by = \Auth::user()->id;
        $page->save();

        return response()->json(["code" => 200, "data" => []]);

    }

    public function notesCreate(Request $request)
    {
        \App\PageNotes::create([
            'url' => $request->url,
            'category_id' => '',
            'note' => $request->data,
            'user_id' => \Auth::user()->id,
        ]);

        return response()->json(["code" => 200, "message" => 'Notes Added Successfully.']);
    }

}
