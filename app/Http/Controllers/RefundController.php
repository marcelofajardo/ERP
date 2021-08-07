<?php

namespace App\Http\Controllers;


use App\Events\RefundDispatched;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Refund;
use App\Setting;
use App\Customer;
use App\Order;
use Carbon\Carbon;

class RefundController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$refunds = Refund::paginate(Setting::get('pagination'));

		return view('refund.index', [
			'refunds'	=> $refunds
		]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$customers = Customer::all();
		$orders = Order::all();
		$orders_array = [];

		foreach ($orders as $key => $order) {
			$orders_array[$key]['id'] = $order->id;
			$orders_array[$key]['order_id'] = $order->order_id;
			$orders_array[$key]['customer_id'] = $order->customer_id;
		}

		return view('refund.create', [
			'customers'			=> $customers,
			'orders_array'	=> $orders_array
		]);
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
			'customer_id'	=> 'required|integer',
			'order_id'		=> 'required|integer',
			'type'				=> 'required|string'
		]);

		$data = $request->except('_token');
		$data['date_of_issue'] = Carbon::parse($request->date_of_request)->addDays(10);

		Refund::create($data);

		return redirect()->route('refund.index')->with('success', 'You have successfully added refund!');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$refund = Refund::find($id);
		$customers = Customer::all();
		$orders = Order::all();
		$orders_array = [];

		foreach ($orders as $key => $order) {
			$orders_array[$key]['id'] = $order->id;
			$orders_array[$key]['order_id'] = $order->order_id;
			$orders_array[$key]['customer_id'] = $order->customer_id;
		}

		return view('refund.show', [
			'refund'				=> $refund,
			'customers'			=> $customers,
			'orders_array'	=> $orders_array
		]);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{

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
		$this->validate($request, [
			'customer_id'	=> 'required|integer',
			'order_id'		=> 'required|integer',
			'type'				=> 'required|string'
		]);

		$order = Order::find($request->order_id);

		$data = $request->except('_token', '_method');
		if (!$request->dispatched) {
			$data['dispatch_date'] = null;
			$data['awb'] = '';
		} else {
			$order->order_status = 'Refund Dispatched';
			$order->order_status_id = \App\Helpers\OrderHelper::$refundDispatched;
            $refund = Refund::find($id);
			event(new RefundDispatched($refund));
		}

		if ($request->credited) {
			$data['credited'] = 1;

			$order->order_status = 'Refund Credited';
			$order->order_status_id = \App\Helpers\OrderHelper::$refundCredited;
		}

		$order->save();

		$data['date_of_issue'] = Carbon::parse($request->date_of_request)->addDays(10);

		$refund = Refund::find($id);
		$refund->update($data);

		if ($request->credited) {
			$refund->order->delete();
			$refund->delete();
		}

		return redirect()->route('refund.index')->with('success', 'You have successfully added refund!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		Refund::find($id)->delete();

		return redirect()->route('refund.index')->with('success', 'You have successfully deleted refund!');
	}
}
