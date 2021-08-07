<?php

namespace App\Http\Controllers;

use App\GoogleServer;
use Illuminate\Http\Request;
use App\LogGoogleCse;

class GoogleServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $googleServer = GoogleServer::paginate(15);
        return view('google-server.index', [
            'googleServer' => $googleServer
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255'
        ]);

        $data = $request->except('_token');
        
        GoogleServer::create($data);

        return redirect()->route('google-server.index')->withSuccess('You have successfully saved a Google Server!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255'
        ]);

        $data = $request->except('_token');
        
        GoogleServer::find($id)->update($data);

        return redirect()->back()->withSuccess('You have successfully updated a Google Server!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $googleServer = GoogleServer::find($id);

        $googleServer->delete();

        return redirect()->route('google-server.index')->withSuccess('You have successfully deleted a Google Server');
    }

    public function logGoogleCse(Request $request)
    {
        $url = $request->url;
        $keyword = $request->keyword;
        $response = $request->response;
        $count = $request->count;
        
        
        $responseString = 'Link: '.$response[$count]['link'] .'\n Display Link: '.$response[$count]['displayLink'].'\n Title : '.$response[$count]['title'].'\n Image Details: '.$response[$count]['image']['contextLink'].' Height:'. $response[$count]['image']['height'].' Width : '. $response[$count]['image']['width'].'\n ThumbnailLink '.$response[$count]['image']['thumbnailLink'];

        $log =  new LogGoogleCse();
        $log->image_url = $url;
        $log->keyword = $keyword;
        $log->response = $responseString;
        $log->save();
    }
}
