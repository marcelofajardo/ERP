<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DescriptionChange;

class ChangeDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matchedArray = [];
        $descriptions = DescriptionChange::query();

		$listdescriptions = ["" => "-- Select --"] + DescriptionChange::where('replace_with','!=','')->groupBy('replace_with')->pluck('replace_with','replace_with')->toArray();

		$descriptions = $descriptions->orderBy('id','desc')->paginate(50);

        return view('description.index', compact('descriptions','listdescriptions'));
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
            'keyword'         => 'required',
            'replace_with' => 'required',
        ]);

		$ifExist = DescriptionChange::where('keyword',$request->keyword)->first();

		if($ifExist){
			return redirect()->back();
		}
        
        $c = DescriptionChange::create($request->all());
        return redirect()->back();

    }

	    /**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Compositions  $compositions
	 * @return \Illuminate\Http\Response
	 */
    public function destroy(Request $request)
    {
    	
        $id = $request->description_id;
        $c = DescriptionChange::find($id);
        $c->delete();
        return redirect()->back();
    }


}
