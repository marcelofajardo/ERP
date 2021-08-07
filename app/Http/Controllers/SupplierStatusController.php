<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\SupplierStatus;
use App\Http\Controllers\Controller;
use DB;


class SupplierStatusController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $supplierstatus = SupplierStatus::orderBy('id', 'DESC')->paginate(10);
        return view('supplier-status.index', compact('supplierstatus'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier-status.create');
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
            'name' => 'required|unique:supplier_status,name',
        ]);


        $department = SupplierStatus::create(['name' => $request->input('name')]);

        return redirect()->route('supplier-status.index')
            ->with('success', 'Supplier Status created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = SupplierStatus::find($id);


        return view('supplier-status.edit', compact('status'));
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


        $department = SupplierStatus::find($id);
        $department->name = $request->input('name');
        $department->save();

        return redirect()->route('supplier-status.index')
            ->with('success', 'Supplier Status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("supplier_status")->where('id', $id)->delete();
        return redirect()->route('supplier-status.index')
            ->with('success', 'Supplier Status deleted successfully');
    }
}