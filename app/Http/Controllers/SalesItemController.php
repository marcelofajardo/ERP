<?php

namespace App\Http\Controllers;

use App\SalesItem;
use Illuminate\Http\Request;

class SalesItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = SalesItem::distinct()->get(['supplier']);

        return view('scrap.sale.index', compact('items'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SalesItem  $salesItem
     * @return \Illuminate\Http\Response
     */
    public function show($supplier)
    {
        $products = SalesItem::where('supplier', $supplier)->paginate(25);
        $title = $supplier;
        return view('scrap.sale.show', compact('products', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SalesItem  $salesItem
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesItem $salesItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SalesItem  $salesItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesItem $salesItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SalesItem  $salesItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesItem $salesItem)
    {
        //
    }
}
