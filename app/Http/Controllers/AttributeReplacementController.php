<?php

namespace App\Http\Controllers;

use App\AttributeReplacement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttributeReplacementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replacements = AttributeReplacement::orderBy('field_identifier')->get();

        return view('products.attr_replacements.index', compact('replacements'));
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
            'field_identifier' => 'required',
            'first_term' => 'required'
        ]);

        $r = new AttributeReplacement();
        $r->action_to_peform = 'REPLACE';
        $r->field_identifier = $request->get('field_identifier');
        $r->first_term = $request->get('first_term');
        $r->replacement_term = $request->get('replacement_term');
        $r->remarks = $request->get('remarks');
        $r->save();

        return redirect()->back()->with('message', 'Added successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AttributeReplacement  $attributeReplacement
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $r = AttributeReplacement::find($id);
        if (!$r) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        $r->authorized_by = Auth::id();
        $r->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AttributeReplacement  $attributeReplacement
     * @return \Illuminate\Http\Response
     */
    public function edit(AttributeReplacement $attributeReplacement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AttributeReplacement  $attributeReplacement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttributeReplacement $attributeReplacement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AttributeReplacement  $attributeReplacement
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttributeReplacement $attributeReplacement)
    {
        //
    }
}
