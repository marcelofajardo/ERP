<?php

namespace App\Http\Controllers;

use App\googleTraslationSettings;
use Illuminate\Http\Request;
use App\GoogleTranslate;

class GoogleTraslationSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = googleTraslationSettings::all();
        return view('googleTraslationSettings.index',compact('settings'));
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
        try {
            $email = $request->email;
            $account_json = $request->account_json;
            $status = $request->status;
            $last_note = $request->last_note;

            $googleTraslationSettings = new googleTraslationSettings;

            $googleTraslationSettings->email = $email;
            $googleTraslationSettings->account_json = $account_json;
            $googleTraslationSettings->status = $status;
            $googleTraslationSettings->last_note = $last_note;
            $googleTraslationSettings->save();

            $msg = 'Setting Add Successfully';
            return redirect()->route('google-traslation-settings.index')->with( 'success', $msg );
        } catch (Exception $e) {
            return redirect()->route('google-traslation-settings.index')->with( 'error', $e->getMessage() );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\googleTraslationSettings  $googleTraslationSettings
     * @return \Illuminate\Http\Response
     */
    public function show(googleTraslationSettings $googleTraslationSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\googleTraslationSettings  $googleTraslationSettings
     * @return \Illuminate\Http\Response
     */
    public function edit($id,googleTraslationSettings $googleTraslationSettings)
    {
        $data = googleTraslationSettings::where('id',$id)->first();
        return view('googleTraslationSettings.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\googleTraslationSettings  $googleTraslationSettings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, googleTraslationSettings $googleTraslationSettings)
    {
        try {
            $id = $request->id;
            $email = $request->email;
            $account_json = $request->account_json;
            $status = $request->status;
            $last_note = $request->last_note;

            $googleTraslationSettings = new googleTraslationSettings;
            $googleTraslationSettings->where('id', $id)
            ->limit(1)
            ->update([
                'email' => $email,
                'account_json' => $account_json,
                'status' => $status,
                'last_note' => $last_note
            ]);
            return redirect()->route('google-traslation-settings.index')->with( 'success', "Setting Update Successfully" );
        } catch (Exception $e) {
            return redirect()->route('google-traslation-settings.index')->with( 'error', $e->getMessage() );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\googleTraslationSettings  $googleTraslationSettings
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $googleTraslationSettings)
    {
        googleTraslationSettings::where('id',$googleTraslationSettings->setting)->delete();

        $msg = "Setting Delete Successfully";
        return redirect()->route('google-traslation-settings.index')->with( 'success', $msg );
    }
}
