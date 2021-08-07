<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Budget;
use App\Setting;
use App\BudgetCategory;
use Carbon\Carbon;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $date = $request->date ?? Carbon::now()->format('Y-m-d');

      if ($request->date) {
        $fixed_budgets = Budget::where('type', 'fixed')->where('date', 'LIKE', "%$date%")->latest()->paginate(Setting::get('pagination'));
        $variable_budgets = Budget::where('type', 'variable')->where('date', 'LIKE', "%$date%")->latest()->paginate(Setting::get('pagination'), ['*'], 'variable-page');
      } else {
        $fixed_budgets = Budget::where('type', 'fixed')->latest()->paginate(Setting::get('pagination'));
        $variable_budgets = Budget::where('type', 'variable')->latest()->paginate(Setting::get('pagination'), ['*'], 'variable-page');
      }

      $categories = BudgetCategory::where('parent_id', 0)->get();
      $subcategories = BudgetCategory::where('parent_id', '!=', 0)->get();

      return view('budgets.index', [
        'fixed_budgets' => $fixed_budgets,
        'variable_budgets' => $variable_budgets,
        'categories' => $categories,
        'subcategories' => $subcategories,
        'date'  => $date
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'description'           => 'sometimes|nullable|string',
        'date'                  => 'required',
        'amount'                => 'required|numeric',
        'type'                  => 'required|string',
        'budget_category_id'    => 'required|numeric',
        'budget_subcategory_id' => 'required|numeric'
      ]);

      $data = $request->except('_token');

      Budget::create($data);

      return redirect()->route('budget.index')->withSuccess('You have successfully created a budget!');
    }

    public function categoryStore(Request $request)
    {
      $this->validate($request, [
        'category'  => 'required|string|max:255'
      ]);

      $category = new BudgetCategory;
      $category->name = $request->category;
      $category->save();

      return redirect()->route('budget.index')->withSuccess('You have successfully added a budget category!');
    }

    public function subCategoryStore(Request $request)
    {
      $this->validate($request, [
        'parent_id'       => 'required|integer',
        'subcategory'     => 'required|string|max:255'
      ]);

      $category = new BudgetCategory;
      $category->parent_id = $request->parent_id;
      $category->name = $request->subcategory;
      $category->save();

      return redirect()->route('budget.index')->withSuccess('You have successfully added a budget sub category!');
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
      $budget = Budget::find($id);

      $budget->delete();

      return redirect()->route('budget.index')->withSuccess('You have successfully deleted a budget!');
    }
}
