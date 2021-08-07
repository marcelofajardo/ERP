<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\PurchaseStatus;
use App\Http\Controllers\Controller;
use DB;


class PurchaseStatusController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchaseStatus = PurchaseStatus::orderBy('id', 'DESC')->paginate(10);
        return view('purchase-status.index', compact('purchaseStatus'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchase-status.create');
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
            'name' => 'required|unique:purchase_status,name',
        ]);


        $department = PurchaseStatus::create(['name' => $request->input('name')]);

        return redirect()->route('purchase-status.index')
            ->with('success', 'Purchase Status created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = PurchaseStatus::find($id);


        return view('purchase-status.edit', compact('status'));
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
            'name' => 'required',
        ]);


        $purchaseStatus = PurchaseStatus::find($id);
        $purchaseStatus->name = $request->input('name');
        $purchaseStatus->save();

        return redirect()->route('purchase-status.index')
            ->with('success', 'Purchase Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("purchase_status")->where('id', $id)->delete();
        return redirect()->route('purchase-status.index')
            ->with('success', 'Purchase Status deleted successfully');
    }
}