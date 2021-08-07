<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Affiliates;
class AffiliateResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $query = Affiliates::query();
        $query = $query->where('source','!=','google');
		if($request->id){
			$query = $query->where('id', $request->id);
		}

        if($request->type != null) {
            $query = $query->where('type', $request->type);
        }


		if($request->term){
            $query = $query->where(function($q) use($request){
                  $q = $q->orWhere('first_name', 'LIKE','%'.$request->term.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('emailaddress', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('phone', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('website_name', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('url', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('title', 'LIKE', '%'.$request->term.'%');
            });
		}

		$data = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('affiliates.partials.list-affiliate', compact('data'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$data->render(),
                'count' => $data->total(),
            ], 200);
        }
		return view('affiliates.index', compact('data'))
			->with('i', ($request->input('page', 1) - 1) * 5);
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
        $affiliates = Affiliates::find($id);

        return response()->json(["code" => 200 , "data" => $affiliates]);
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
    public function destroy(request $request)
    {
        $id = $request->id;
        if(is_array($id)){
            \DB::table('affiliates')
            ->whereIn('id', $id)
            ->delete();
        }else{
            \DB::table('affiliates')
            ->where('id', $id)
            ->delete();
        }

		return redirect()->route('affiliates.list')
			->with('success', 'Affiliates deleted successfully');
    }
}
