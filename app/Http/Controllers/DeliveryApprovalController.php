<?php

namespace App\Http\Controllers;

use App\DeliveryApproval;
use App\StatusChange;
use App\PrivateView;
use App\ChatMessage;
use App\Helpers;
use App\User;
use Auth;
use Illuminate\Http\Request;

class DeliveryApprovalController extends Controller
{

    public function __construct() {
     // $this->middleware('permission:delivery-approval');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $delivery_approvals = DeliveryApproval::all();
      $users_array = Helpers::getUserArray(User::all());

      return view('deliveryapprovals.index', [
        'delivery_approvals'  => $delivery_approvals,
        'users_array'  => $users_array,
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
        //
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

    public function updateStatus(Request $request, $id)
    {
      $delivery_approval = DeliveryApproval::find($id);

      StatusChange::create([
        'model_id'    => $delivery_approval->id,
        'model_type'  => DeliveryApproval::class,
        'user_id'     => Auth::id(),
        'from_status' => $delivery_approval->status,
        'to_status'   => $request->status
      ]);

      $delivery_approval->status = $request->status;
      $delivery_approval->save();

      if ($request->status == 'delivered') {
        $delivery_approval->private_view->products[0]->supplier = '';
        $delivery_approval->private_view->products[0]->save();

        // Message to Customer
        $params = [
          'number'    => NULL,
          'user_id'   => Auth::id(),
          'customer_id' => $delivery_approval->private_view->customer_id,
          'message'   => "This product has been delivered. Thank you for your business",
          'approved'  => 0,
          'status'    => 1
        ];

        $chat_message = ChatMessage::create($params);
      } elseif ($request->status == 'returned') {
        $delivery_approval->private_view->products[0]->supplier = 'In-stock';
        $delivery_approval->private_view->products[0]->save();

        // Message to Stock Coordinator
        $params = [
          'number'    => NULL,
          'user_id'   => Auth::id(),
          'message'   => "This product will be sent back",
          'approved'  => 0,
          'status'    => 1
        ];

        $chat_message = ChatMessage::create($params);

        $whatsapp_number = Auth::user()->whatsapp_number != '' ? Auth::user()->whatsapp_number : NULL;

        $stock_coordinators = User::role('Stock Coordinator')->get();

        foreach ($stock_coordinators as $coordinator) {
          $params['erp_user'] = $coordinator->id;
          $chat_message = ChatMessage::create($params);

          $whatsapp_number = $coordinator->whatsapp_number != '' ? $coordinator->whatsapp_number : NULL;

          app('App\Http\Controllers\WhatsAppController')->sendWithNewApi($coordinator->phone, $whatsapp_number, $params['message'], NULL, $chat_message->id);

          $chat_message->update([
            'approved' => 1,
            'status'   => 2
          ]);
        }

        // Message to Aliya
        $coordinators = User::role('Delivery Coordinator')->get();

        foreach ($coordinators as $coordinator) {
          $params['erp_user'] = $coordinator->id;
          $chat_message = ChatMessage::create($params);

          $whatsapp_number = $coordinator->whatsapp_number != '' ? $coordinator->whatsapp_number : NULL;

          app('App\Http\Controllers\WhatsAppController')->sendWithNewApi($coordinator->phone, $whatsapp_number, $params['message'], NULL, $chat_message->id);

          $chat_message->update([
            'approved' => 1,
            'status'   => 2
          ]);
        }


      }

      if ($delivery_approval->private_view) {
        $delivery_approval->private_view->status = $request->status;
        $delivery_approval->private_view->save();

        StatusChange::create([
          'model_id'    => $delivery_approval->private_view->id,
          'model_type'  => PrivateView::class,
          'user_id'     => Auth::id(),
          'from_status' => $delivery_approval->private_view->status,
          'to_status'   => $request->status
        ]);
      }

      return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
