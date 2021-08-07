<?php

namespace App\Http\Controllers\Marketing;

use App\Marketing\MarketingPlatform;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use Validator;
use Response;

class MarketingPlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->term || $request->date){

        $query =  MarketingPlatform::query();

            //global search term
        if (request('term') != null) {
            $query->where('name', 'LIKE', "%{$request->term}%");
            }
        if (request('date') != null) {
            $query->whereDate('created_at', request('website'));
        }
        
        $platforms = $query->orderby('id','desc')->paginate(Setting::get('pagination')); 

        }else{
          $platforms = MarketingPlatform::orderby('id','desc')->paginate(Setting::get('pagination'));   
        }

        if ($request->ajax()) {
        return response()->json([
            'tbody' => view('marketing.platforms.partials.data', compact('platforms'))->render(),
            'links' => (string)$platforms->render()
        ], 200);
    }



    return view('marketing.platforms.index', [
      'platforms' => $platforms,
    ]);


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
        'name'  => 'required|min:3|max:255',
      ]);

      $data = $request->except('_token');
      MarketingPlatform::create($data);

      return redirect()->back()->withSuccess('You have successfully stored Marketing Platform');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MarketingPlatform  $marketingPlatform
     * @return \Illuminate\Http\Response
     */
    public function show(MarketingPlatform $marketingPlatform)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MarketingPlatform  $marketingPlatform
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $this->validate($request, [
        'name'  => 'required|min:3|max:255',
        ]);
        $platform = MarketingPlatform::findorfail($request->id);
        $data = $request->except('_token','id');
       $platform->update($data);

        return redirect()->back()->withSuccess('You have successfully changed Marketing Platform');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MarketingPlatform  $marketingPlatform
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketingPlatform $marketingPlatform)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MarketingPlatform  $marketingPlatform
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $platform = MarketingPlatform::findorfail($request->id);
        $platform->delete();
        return Response::json(array(
            'success' => true,
            'message' => 'Marketing Platform Deleted'
        ));
    }
}
