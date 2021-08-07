<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\MonetaryAccount;
use Illuminate\Http\Request;

class MonetaryAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $data;
    public function __construct()
    {
//        $this->middleware('permission:blogger-all');
        $this->middleware(function ($request, $next) {
            session()->flash('active_tab','blogger_list_tab');
            return $next($request);
        });
    }

    public function index(MonetaryAccount $monetary_account, Request $request)
    {
        $this->data['accounts'] = $monetary_account;
        $order_by = 'DESC';
        if ($request->orderby == '')
            $order_by = 'ASC';

        $this->data['orderby'] = $order_by;
        $this->data['accounts'] = $this->data['accounts']->paginate(50);
        $this->data['currencies'] = Helpers::currencies();
        $this->data['account_types'] = ['cash'=>'Cash','bank'=>'Bank'];
        return view('monetary-account.index',$this->data);
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
        $this->validate($request,[
           'currency' => 'required',
           'date' => 'required|date',
           'amount' => 'required|numeric',
        ]);
        $account = MonetaryAccount::create([
            'date' => $request->get('date'),
            'currency' => $request->get('currency'),
            'amount' => $request->get('amount'),
            'type' => $request->get('type'),
            'short_note' => $request->get('short_note'),
            'description' => $request->get('description'),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);
        return redirect()->back()->withSuccess('Monetary Capital Successfully stored.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MonetaryAccount $monetary_account)
    {
        $this->validate($request,[
            'currency' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
        ]);
        $monetary_account->fill([
            'date' => $request->get('date'),
            'currency' => $request->get('currency'),
            'amount' => $request->get('amount'),
            'type' => $request->get('type'),
            'short_note' => $request->get('short_note'),
            'description' => $request->get('description'),
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ])->save();
        return redirect()->back()->withSuccess('Monetary Capital Successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MonetaryAccount $monetary_account)
    {
        try {
            $monetary_account->delete();
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t delete data');
        }
        return redirect()->back()->withSuccess('You have successfully deleted account detail');
    }
}
