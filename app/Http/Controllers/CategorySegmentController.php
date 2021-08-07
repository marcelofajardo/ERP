<?php

namespace App\Http\Controllers;

use App\CategorySegment;
use App\Setting;
use Illuminate\Http\Request;

class CategorySegmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_segments = CategorySegment::query();

        $keyword = request('keyword');
        if(!empty($keyword)) {
            $category_segments = $category_segments->where("name","like","%".$keyword."%");
        }

        $category_segments = $category_segments->paginate(Setting::get('pagination'));

        return view( 'category-segment.index', compact('category_segments'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data[ 'name' ] = '';
        $data[ 'status' ] = '';
        $data[ 'modify' ] = 0;
        return view('category-segment.form', $data);
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
            'name' => 'required|unique:category_segments,name'
        ]);
        $category_segment = new CategorySegment();
        $category_segment->name = $request->name;
        $category_segment->status = $request->status;
        $category_segment->save();
        return redirect()->route('category-segment.index')->with('success', 'Category Segment created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category_segment = CategorySegment::find($id);
        $data = $category_segment->toArray();
        $data[ 'modify' ] = 1;

        return view('category-segment.form', $data);
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
        $this->validate($request, [
            'name' => 'required|unique:category_segments,name,' . $id
        ]);
        $category_segment = CategorySegment::find($id);
        $category_segment->name = $request->name;
        $category_segment->status = $request->status;
        $category_segment->save();
        return redirect()->route('category-segment.index')->with('success', 'Category Segment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category_segment = CategorySegment::find($id);
        if($category_segment) {
            $category_segment->delete();
        }
        return redirect()->route('category-segment.index')->with('success', 'Category Segment deleted successfully!');
    }
}
