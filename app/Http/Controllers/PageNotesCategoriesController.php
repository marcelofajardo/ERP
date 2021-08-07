<?php

namespace App\Http\Controllers;

use App\Helpers;
use Illuminate\Http\Request;

class PageNotesCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $data;
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $pageNotesCategories = \App\PageNotesCategories::paginate(15);
        return view("page-notes-categories.index", ['pageNotesCategories' => $pageNotesCategories]);
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
        $this->validate($request,[
           'name' => 'required'
        ]);
        $account = \App\PageNotesCategories::create([
            'name' => $request->get('name')
        ]);
        return redirect()->back()->withSuccess('Page Notes Category Successfully stored.');
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request,[
            'name' => 'required'
        ]);
        $pageNotesCategories = \App\PageNotesCategories::where('id', $id)->first();
        if ($pageNotesCategories) {
            $pageNotesCategories->fill([
                'name' => $request->get('name')
            ])->save();   
            return redirect()->back()->withSuccess('Page Notes Category Successfully updated.');
        }

        return redirect()->back()->withSuccess('Page Notes Category Successfully not updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pageNotesCategories = \App\PageNotesCategories::where('id', $id)->first();
        try {
            $pageNotes = \App\PageNotes::where('category_id', $id)->count();
            if (!$pageNotes) {
                $pageNotesCategories->delete();
            } else {
                return redirect()->back()->withErrors('Couldn\'t delete data , category is using in page notes!!');    
            }
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t delete data');
        }
        return redirect()->back()->withSuccess('You have successfully deleted page notes category');
    }
}
