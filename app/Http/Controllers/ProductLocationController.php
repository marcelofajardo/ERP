<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ProductLocation;

class ProductLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productLocation = ProductLocation::all();

        return view('product-location.index', compact('productLocation'));
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

        $productLocation       = new ProductLocation();
        $productLocation->name = $request->get('name');
        $productLocation->save();

        return redirect()->back()->with('message', 'Location added successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productLocation = ProductLocation::find($id);

        if ($productLocation) {
            $productLocation->delete();
        }

        return redirect()->back()->with('message', 'Location deleted successfully!');
    }

}
