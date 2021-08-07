<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReferralProgram;
use App\StoreWebsite;
class ReferralProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        
        $query = ReferralProgram::query();

		if($request->id){
			$query = $query->where('id', $request->id);
		}
		if($request->term){
            $query = $query->where('name', 'LIKE','%'.$request->term.'%')
                    ->orWhere('uri', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('credit', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('currency', 'LIKE', '%'.$request->term.'%');
		}

		$data = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('referralprogram.partials.list-programs', compact('data'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$data->render(),
                'count' => $data->total(),
            ], 200);
        }
		return view('referralprogram.index', compact('data'))
			->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$StoreWebsite = StoreWebsite::select('id','website')->groupBy('website')->get();
		return view('referralprogram.create', compact('StoreWebsite'));
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
			'uri' => 'required|exists:store_websites,website',
			'credit' => 'required|integer',
            'currency' => 'required|string',
            'lifetime_minutes'=>'integer',
		]);
        $StoreWebsiteId = StoreWebsite::where('website',$request->input('uri'))->first()->id; 
        $input = $request->all();
        $input['store_website_id'] = $StoreWebsiteId;
		$insert = ReferralProgram::create($input);

		return redirect()->to('/referralprograms/' . $insert->id . '/edit')->with('success', 'Program created successfully');
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
        $StoreWebsite = StoreWebsite::select('id','website')->groupBy('website')->get();
        $ReferralProgram = ReferralProgram::where('id',$id)->first();
		return view('referralprogram.edit', compact('StoreWebsite','ReferralProgram'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
			'name' => 'required',
			'uri' => 'required|exists:store_websites,website',
			'credit' => 'required|integer',
            'currency' => 'required|string',
            'lifetime_minutes'=>'integer',
        ]);
        $id = $request->input('id');
        $StoreWebsiteId = StoreWebsite::where('website',$request->input('uri'))->first()->id; 
        $input = $request->except('_token');
        $input['store_website_id'] = $StoreWebsiteId;
		$insert = ReferralProgram::where('id',$id)->update($input);

		return redirect()->back()->with('success', 'Program updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ReferralProgram = ReferralProgram::find($id);
		$ReferralProgram->delete();

		return redirect()->route('referralprograms.list')
			->with('success', 'Program deleted successfully');
    }
    
}
