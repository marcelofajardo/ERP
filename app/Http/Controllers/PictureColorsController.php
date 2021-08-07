<?php

namespace App\Http\Controllers;

use App\PictureColors;
use Illuminate\Http\Request;

class PictureColorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pictures = PictureColors::all();
        return view('test2', compact('pictures'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PictureColors  $pictureColors
     * @return \Illuminate\Http\Response
     */
    public function show(PictureColors $pictureColors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PictureColors  $pictureColors
     * @return \Illuminate\Http\Response
     */
    public function edit(PictureColors $pictureColors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PictureColors  $pictureColors
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PictureColors $pictureColors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PictureColors  $pictureColors
     * @return \Illuminate\Http\Response
     */
    public function destroy(PictureColors $pictureColors)
    {
        //
    }
}
