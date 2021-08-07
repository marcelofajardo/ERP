<?php

namespace App\Http\Controllers;

use App\Customer;
use App\CustomerCategory;
use Illuminate\Http\Request;

class CustomerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = CustomerCategory::all();

        return view('customers.category_messages.category.index', compact('categories'));
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
            'name' => 'required|unique:customer_categories,name'
        ]);

        $category = new CustomerCategory();
        $category->name = $request->get('name');
        $category->message = $request->get('message');
        $category->save();

        return redirect()->back()->with('message', 'Category added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCategory $customerCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customerCategory = CustomerCategory::find($id);

        if (!$customerCategory) {
            return redirect()->back()->with('message', 'The requested category is not available!');
        }

        return view('customers.category_messages.category.edit', compact('customerCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $category = CustomerCategory::find($id);

        if (!$category) {
            return redirect()->back()->with('message', 'The requested category is not available!');
        }

        $this->validate($request, [
            'name' => 'required|unique:customer_categories,id,'.$id
        ]);

        $category->name = $request->get('name');
        $category->message = $request->get('message');
        $category->save();

        return redirect()->back()->with('message', 'Category updated successfully!');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerCategory  $customerCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = CustomerCategory::find($id);

        if (!$category) {
            return redirect()->back()->with('message', 'The requested category is not available!');
        }

        $category->delete();

        return redirect()->action('CustomerCategoryController@index')->with('message', 'Category deleted successfully!');
    }
}
