<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Courier;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courier = Courier::all();

        return view('courier.index', compact('courier'));
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
            'name' => 'required',
        ]);

        $courier       = new Courier();
        $courier->name = $request->get('name');
        $courier->save();

        return redirect()->back()->with('message', 'Courier added successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function show(KeywordToCategory $keywordToCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(KeywordToCategory $keywordToCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeywordToCategory $keywordToCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $keyword = Courier::find($id);

        if ($keyword) {
            $keyword->delete();
        }

        return redirect()->back()->with('message', 'Courier deleted successfully!');
    }

}
