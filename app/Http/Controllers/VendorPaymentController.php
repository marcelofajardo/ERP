<?php

namespace App\Http\Controllers;

use App\Events\VendorPaymentCreated;
use App\Helpers;
use App\Listeners\VendorPaymentCashFlow;
use App\Vendor;
use App\VendorPayment;
use Illuminate\Http\Request;

class VendorPaymentController extends Controller
{
    public function index(Vendor $vendor)
    {
        $payments = $vendor->payments()->orderBy('payment_date')->paginate(50);
        return view('vendors.payments', [
            'payments' => $payments,
            'vendor' => $vendor,
            'currencies' => Helpers::currencies(),
        ]);
    }

    public function store(Vendor $vendor, Request $request)
    {
        $this->validate($request, [
            'currency' => 'required|numeric',
            'payment_date' => 'required|date',
            'payable_amount' => 'required|numeric',
            'paid_date' => 'sometimes|nullable|date',
            'paid_amount' => 'sometimes|nullable|numeric',
        ]);
        try {
            $status = 0;
            if ($request->get('paid_date') && $request->get('paid_amount')) {
                $status = 1;
            }
            $vendor_payment = $vendor->payments()->create([
                'service_provided' => $request->get('service_provided'),
                'payment_date' => $request->get('payment_date'),
                'payable_amount' => $request->get('payable_amount'),
                'paid_date' => $request->get('paid_date'),
                'paid_amount' => $request->get('paid_amount'),
                'description' => $request->get('description'),
                'module' => $request->get('module'),
                'work_hour' => $request->get('work_hour'),
                'currency' => $request->get('currency'),
                'status' => $status,
            ]);
            event(new VendorPaymentCreated($vendor, $vendor_payment, $status));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t store vendor payment');
        }
        return redirect()->back()->withSuccess('You have successfully added a vendor payment!');
    }

    public function update(Vendor $vendor, VendorPayment $vendor_payment, Request $request)
    {
        $this->validate($request, [
            'currency' => 'required|numeric',
            'payment_date' => 'required|date',
            'payable_amount' => 'required|numeric',
            'paid_date' => 'sometimes|nullable|date',
            'paid_amount' => 'sometimes|nullable|numeric',
        ]);
        try {
            $payment = $vendor->payments()->where('id', $vendor_payment->id)->first();
            $status = 0;
            if ($request->get('paid_date') && $request->get('paid_amount')) {
                $status = 1;
            }
            $payment->fill([
                'service_provided' => $request->get('service_provided'),
                'payment_date' => $request->get('payment_date'),
                'payable_amount' => $request->get('payable_amount'),
                'paid_date' => $request->get('paid_date'),
                'paid_amount' => $request->get('paid_amount'),
                'description' => $request->get('description'),
                'module' => $request->get('module'),
                'work_hour' => $request->get('work_hour'),
                'currency' => $request->get('currency'),
                'status' => $status,
            ])->save();
            event(new VendorPaymentCreated($vendor, $payment, $status));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t update vendor payment');
        }
        return redirect()->back()->withSuccess('You have successfully updated vendor payment!');
    }

    public function destroy(Vendor $vendor, VendorPayment $vendor_payment)
    {
        $payment = $vendor->payments()->where('id', $vendor_payment->id)->firstOrFail();
        try {
            $payment->delete();
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t delete vendor payment');
        }
        return redirect()->back()->withSuccess('You have successfully deleted vendor payment!');
    }
}
