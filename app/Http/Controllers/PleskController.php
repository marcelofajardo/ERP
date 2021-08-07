<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PleskHelper;
use App\EmailAddress;
class PleskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pleskHelper = new PleskHelper;
        $domains = $pleskHelper->getDomains();
        if($domains) {
            return view('plesk.index',compact('domains'));
        }
        return response()->with('error','Something went wrong');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, Request $request)
    {
        $sitename = $request->sitename;
        return view('plesk.create-mail',compact('id','sitename'));
    }
    public function submitMail($id, Request $request) {
        $pleskHelper = new PleskHelper;
        $validatedData = $request->validate([
            'name' => 'required',
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*?_~:,()\[\]{}]).+$/'
        ]);
        try {
            $response = $pleskHelper->createMail($request->name,$id,$request->mailbox,$request->password);
            $address = new EmailAddress;
            $address->from_name = $request->name;
            $address->from_address = $request->name.'@'.$request->site_name;
            $address->driver = 'imap';
            $address->host = $request->site_name;
            $address->port = '993';
            $address->encryption = 'ssl';
            $address->username = $request->name.'@'.$request->site_name;
            $address->password = $request->password;
            $address->save();
            $msg = 'Successfully created';
            $type = 'success';
        }
        catch (\Exception $e) {
            $msg = $e->getMessage();
            $type = 'warning';
        }

        return redirect()->back()->with($type,$msg);
    }


    public function getMailAccounts($id, Request $request) {
        $pleskHelper = new PleskHelper;
        // $mailAccount = $pleskHelper->getMailAccounts($id);
        try {
            $mailAccount = $pleskHelper->getMailAccounts($id);
            $msg = 'Successful';
            $type = 'success';
        }
        catch (\Exception $e) {
            $msg = $e->getMessage();
            $type = 'warning';
        }
        $site_name = $request->name;
        return view('plesk.mail-list',compact('mailAccount','id','site_name'));
    }

    public function deleteMail($id, Request $request) {
        $pleskHelper = new PleskHelper;
        try {
            $pleskHelper->deleteMailAccount($id,$request->name);
            $username = $request->name.'@'.$request->site_name;
            EmailAddress::where('username',$username)->delete();
            $msg = 'Successful';
            $type = 'success';
        }
        catch (\Exception $e) {
            $msg = $e->getMessage();
            return response()->json(['message' => $msg],500);
        }
        return response()->json(['message' => 'Successful'],200);
    }

    public function changePassword(Request $request) {
        $validatedData = $request->validate([
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*?_~:,()\[\]{}]).+$/'
        ]);
        $pleskHelper = new PleskHelper;
        try {
            $pleskHelper->changePassword($request->hidden_site_id,$request->hidden_mail_name,$request->password);
            $username = $request->hidden_mail_name.'@'.$request->hidden_domain_name;
            EmailAddress::where('username',$username)->update(['password' => $request->password]);
            $msg = 'Successful';
            $type = 'success';
        }
        catch (\Exception $e) {
            $msg = $e->getMessage();
            $type = 'warning';
        }
        return redirect()->back()->with($type,$msg);
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
        $pleskHelper = new PleskHelper;
        $domain = $pleskHelper->viewDomain($id);
        if($domain) {
            return view('plesk.show',compact('domain'));
        }
        return response()->with('error','Something went wrong');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
