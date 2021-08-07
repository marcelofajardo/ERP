<?php

namespace App\Http\Controllers;

use App\Account;
use App\PeopleNames;
use App\PreAccount;
use App\TargetLocation;
use Illuminate\Http\Request;

class PreAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = PreAccount::all();
        $firstName = PeopleNames::inRandomOrder()->take(10)->get();
        $lastName = PeopleNames::inRandomOrder()->take(10)->get()->toArray();
        $countries = TargetLocation::all();

        return view('pre.accounts', compact('accounts','firstName', 'lastName', 'countries'));
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
            'email' => 'required|array',
            'password' => 'required|array'
        ]);

        $emails = $request->get('email');

        foreach ($emails as $key=>$email) {
            if (!$email) {
                continue;
            }
            $account = new PreAccount();
            $account->first_name = $request->get('first_name')[$key];
            $account->last_name = $request->get('last_name')[$key];
            $account->email = $email;
            $account->password = $request->get('password')[$key];
            $account->instagram = 0;
            $account->facebook = 0;
            $account->pinterest = 0;
            $account->twitter = 0;
            $account->save();

            $a = new Account();
            $a->email = $account->email;
            $a->first_name = $account->first_name . ' ' . $account->last_name;
            $a->platform = 'instagram';
            $a->dob = date('Y-m-d');
            $a->save();

            $a = new Account();
            $a->email = $account->email;
            $a->first_name = $account->first_name . ' ' . $account->last_name;
            $a->platform = 'pinterest';
            $a->dob = date('Y-m-d');
            $a->save();

        }

        return redirect()->back()->with('message', 'E-mail added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PreAccount  $preAccount
     * @return \Illuminate\Http\Response
     */
    public function show(PreAccount $preAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreAccount  $preAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(PreAccount $preAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreAccount  $preAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreAccount $preAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreAccount  $preAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pre = PreAccount::findOrFail($id);
        $pre->delete();

        return redirect()->back()->with('success', 'Deleted successfully!');
    }
}
