<?php

namespace App\Http\Controllers;

use App\CustomerCategory;
use App\KeywordToCategory;
use Illuminate\Http\Request;

class KeywordToCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keywords = KeywordToCategory::all();

        $orderStatuses = [
            'Follow up for advance' => 'Follow up for advance',
            'Proceed without Advance' => 'Proceed without Advance',
            'Advance received' => 'Advance received',
            'Cancel' => 'Cancel',
            'Prepaid' => 'Prepaid',
            'Product Shiped form Italy' => 'Product Shiped form Italy',
            'In Transist from Italy' => 'In Transist from Italy',
            'Product shiped to Client' => 'Product shiped to Client',
            'Delivered' => 'Delivered',
            'Refund to be processed' => 'Refund to be processed',
            'Refund Dispatched' => 'Refund Dispatched',
            'Refund Credited' => 'Refund Credited',
            'VIP' => 'VIP',
            'HIGH PRIORITY' => 'HIGH PRIORITY'
        ];

        $leadStatuses = [
            '1' => 'Cold',
            '2' => 'Cold Important',
            '3' => 'Hot',
            '4' => 'Very Hot',
            '5' => 'Advance Follow Up',
            '6' => 'High Priority'
        ];

        $categories = CustomerCategory::all();

        return view('customers.category_messages.keywords.index', compact('keywords', 'categories', 'leadStatuses', 'orderStatuses'));
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
            'keyword' => 'required',
            'category' => 'required'
        ]);

        $catAndCatType = $this->getCategoryWIthData($request->get('category'));

        $keyword = new KeywordToCategory();
        $keyword->keyword_value = $request->get('keyword');
        $keyword->category_type = $catAndCatType[0];
        $keyword->model_id = $catAndCatType[1];
        $keyword->save();

        return redirect()->back()->with('message', 'Keyword added successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function show(KeywordToCategory $keywordToCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(KeywordToCategory $keywordToCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeywordToCategory $keywordToCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KeywordToCategory  $keywordToCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $keyword = KeywordToCategory::find($id);

        if ($keyword) {
            $keyword->delete();
        }

        return redirect()->back()->with('message', 'Keyword deleted successfully!');
    }

    /**
     * @param $value
     * @return array
     */
    private function getCategoryWIthData($value): array
    {
        if (stripos(strtolower($value), 'order_') !== false) {
            return ['order', str_replace('order_', '', $value)];
        }

        if (stripos(strtolower($value), 'lead_') !== false) {
            return ['lead', str_replace('lead_', '', $value)];
        }

        return ['category', $value];
    }
}
