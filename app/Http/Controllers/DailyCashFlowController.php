<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DailyCashFlow;
use App\Setting;
use App\Purchase;
use App\Order;
use Carbon\Carbon;

class DailyCashFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $short_fall = 0;
      $filter_date = $request->date ?? Carbon::now()->format('Y-m-d');

      $cash_flows = DailyCashFlow::where(function ($query) use ($filter_date) {
        $query->where('date', 'LIKE', "%$filter_date%");
      })->paginate(Setting::get('pagination'));

      $orders = Order::with('order_product')->select(['id', 'order_date', 'balance_amount', 'order_status_id', 'order_status', 'estimated_delivery_date'])->where(function ($query) use ($filter_date) {
        $query->where('order_date', $filter_date)->orWhere('estimated_delivery_date', 'LIKE', "%$filter_date%");
      })->paginate(Setting::get('pagination'), ['*'], 'order-page');

      $purchases = Purchase::with(['products' => function ($query) {
        $query->with(['orderproducts' => function ($q) {
          $q->with('order');
        }]);
      }])->select(['id', 'created_at'])->orderBy('created_at', 'DESC')->paginate(Setting::get('pagination'), ['*'], 'purchase-page');

      foreach ($cash_flows as $cash_flow) {
        $short_fall += $cash_flow->received - $cash_flow->expected;
      }

      // $sold_price = 0;
      // $actual_price = 0;
      //
      // foreach ($purchases as $purchase) {
      //   foreach ($purchase->products as $product) {
      //     $actual_price += $product->price;
      //
      //     foreach ($product->orderproducts as $order_product) {
      //       $sold_price += $order_product->product_price;
      //     }
      //   }
      // }

      $sold_price = 0;
      $purchase_price = 0;
      $balance = 0;
      $vouchers = 0;

      foreach ($orders as $order) {
        if ($order->order_product) {
          foreach ($order->order_product as $order_product) {
            $sold_price += $order_product->product_price;

            if ($order_product->product) {
              if (count($order_product->product->purchases) > 0) {
                $purchase_price += ($order_product->product->price * 78);
              }

              $balance += $order->balance_amount;
            }
          }
        }

        if ($order->delivery_approval && $order->delivery_approval->voucher) {
          $vouchers += $order->delivery_approval->voucher->amount;
        }
      }

      $short_fall += $sold_price - $purchase_price - $balance - $vouchers;

      return view('dailycashflows.index', [
        'cash_flows'  => $cash_flows,
        'purchases'  => $purchases,
        'orders'  => $orders,
        'short_fall'  => $short_fall,
        'filter_date'  => $filter_date,
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
        'received_from' => 'sometimes|nullable|string',
        'paid_to'       => 'sometimes|nullable|string',
        'date'          => 'required',
        'expected'      => 'required_without:received|nullable|numeric',
        'received'      => 'required_without:expected|nullable|numeric',
      ]);

      $data = $request->except('_token');

      DailyCashFlow::create($data);

      return redirect()->route('dailycashflow.index')->withSuccess('You have successfully stored a record!');
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
      $this->validate($request, [
        'received_from' => 'sometimes|nullable|string',
        'paid_to'       => 'sometimes|nullable|string',
        'date'          => 'required',
        'expected'      => 'required_without:received|nullable|numeric',
        'received'      => 'required_without:expected|nullable|numeric',
      ]);

      $data = $request->except('_token');

      DailyCashFlow::find($id)->update($data);

      return redirect()->route('dailycashflow.index')->withSuccess('You have successfully updated a record!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $cash_flow = DailyCashFlow::find($id);

      $cash_flow->delete();

      return redirect()->route('dailycashflow.index')->withSuccess('You have successfully deleted a record!');
    }
}
