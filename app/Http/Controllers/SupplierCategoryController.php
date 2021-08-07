<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\SupplierCategory;
use App\Http\Controllers\Controller;
use App\User;
use DB;


class SupplierCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suppliercategory = SupplierCategory::orderBy('id', 'DESC')->paginate(10);
        return view('supplier-category.index', compact('suppliercategory'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier-category.create');
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
            'name' => 'required|unique:supplier_category,name',
        ]);


        $department = SupplierCategory::create(['name' => $request->input('name')]);

        return redirect()->route('supplier-category.index')
            ->with('success', 'Supplier Category created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = SupplierCategory::find($id);


        return view('supplier-category.edit', compact('category'));
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


        $department = SupplierCategory::find($id);
        $department->name = $request->input('name');
        $department->save();

        return redirect()->route('supplier-category.index')
            ->with('success', 'Supplier Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("supplier_category")->where('id', $id)->delete();
        return redirect()->route('supplier-category.index')
            ->with('success', 'Supplier Category deleted successfully');
    }
//
    public function usersPermission(Request $request)
    {
        $users = User::where('is_active',1)->orderBy('name','asc')->with('supplierCategoryPermission')->get();
        $categories = SupplierCategory::orderBy('name','asc')->get();
        return view('suppliers.supplier-category-permission.index',compact('users','categories'))->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function  updatePermission(Request $request){
        $user_id =  $request->user_id;
        $category_id = $request->supplier_category_id;
        $check = $request->check;
        $user = User::findorfail($user_id);
        //ADD PERMISSION
        if($check == 1){
            $user->supplierCategoryPermission()->attach($category_id);
            $message = "Permission added Successfully";
        }
        //REMOVE PERMISSION
        if($check == 0){
            $user->supplierCategoryPermission()->detach($category_id);
            $message = "Permission removed Successfully";
        }

        $data = [
            'success' => true,
            'message'=> $message
        ] ;
        return response()->json($data);


    }
}