<?php

namespace App\Http\Controllers;

use App\Account;
use App\TargetLocation;
use Illuminate\Http\Request;

class PinterestAccountAcontroller extends Controller
{
    public function index(Request $request) {

        $accounts = Account::where('platform', 'pinterest');

        if ($request->get('query') != '') {
            $accounts->where(function($query) use ($request) {
                $q = $request->get('query');
                $query->where('first_name','LIKE', "%$q%")->orWhere('last_name', 'LIKE', "%$q%");
            });
        }


        if ($request->get('blocked') == 'on') {
            $accounts = $accounts->where('blocked', 1);
        }

        $accounts = $accounts->get();
        $countries = TargetLocation::all();

        return view('pinterest.accounts',compact('accounts', 'request', 'countries'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'last_name' => 'required',
            'first_name' => 'required',
            'password' => 'required',
            'email' => 'required'
        ]);

        $account = new Account();
        $account->first_name = $request->get('first_name');
        $account->last_name = $request->get('last_name');
        $account->email = $request->get('email');
        $account->password = $request->get('password');
        $account->country = $request->get('country');
        $account->gender = $request->get('gender');
        $account->platform = 'pinterest';
        $account->save();

        return redirect()->back()->with('success', 'Account added successfully!');

    }

    public function edit($id) {
        $account = Account::find($id);
        $countries = TargetLocation::all();

        return view('pinterest.account-edit', compact('countries', 'account'));

    }

    public function update($id, Request $request) {
        $this->validate($request, [
            'last_name' => 'required',
            'first_name' => 'required',
            'password' => 'required',
            'email' => 'required'
        ]);

        $account = Account::findOrFail($id);
        $account->last_name = $request->get('last_name');
        $account->first_name = $request->get('first_name');
        $account->password = $request->get('password');
        $account->email = $request->get('email');
        $account->blocked = $request->get('blocked') == 'on' ? 1 : 0;
        $account->country = $request->get('country');
        $account->save();

        return redirect()->back()->with('message', 'Account updated successfully!');
    }

    public function destroy($id) {
        $acc = Account::findOrFail($id);
        $acc->delete();

        return redirect()->back()->with('success', 'Account deleted successfully!');
    }
}
