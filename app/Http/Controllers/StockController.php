<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Setting;
use App\Product;
use App\PrivateView;
use App\StatusChange;
use App\Helpers;
use App\Customer;
use App\User;
use App\ChatMessage;
use App\AutoReply;
use App\DeliveryApproval;
use Auth;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class StockController extends Controller
{

    public function __construct() {
    //  $this->middleware('permission:private-viewing', ['only' => ['privateViewing', 'privateViewingStore', 'privateViewingUpload', 'privateViewingUpdateStatus', 'updateOfficeBoy', 'privateViewingDestroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if($request->input('orderby') == '')
          $orderby = 'asc';
      else
          $orderby = 'desc';

      $stocks = Stock::latest()->paginate(Setting::get('pagination'));

      return view('stock.index', [
        'stocks'  => $stocks,
        'orderby' => $orderby
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('stock.create');
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
        'courier'     => 'required|string|min:3|max:255',
        'from'        => 'sometimes|nullable|string|min:3|max:255',
        'date'        => 'sometimes|nullable',
        'awb'         => 'required|min:3|max:255',
        'l_dimension' => 'sometimes|nullable|numeric',
        'w_dimension' => 'sometimes|nullable|numeric',
        'h_dimension' => 'sometimes|nullable|numeric',
        'weight'      => 'sometimes|nullable|numeric',
        'pcs'         => 'sometimes|nullable|numeric',
      ]);

      $stock = Stock::create($request->except('_token'));

      if ($request->ajax()) {
        return response($stock->id);
      }

      return redirect()->route('stock.index')->with('success', 'You have successfully created stock');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $stock = Stock::find($id);

      return view('stock.show', [
        'stock' => $stock
      ]);
    }

    public function trackPackage(Request $request)
    {
      $url = "http://www.bluedart.com/servlet/RoutingServlet?handler=tnt&action=custawbquery&loginid=BOM07707&awb=awb&numbers=$request->awb&format=html&lickey=e2be31925a15e48125bfec50bfeb64a7&verno=1.3f&scan=1";
      // $content = $_POST['data'];
      //$content = '{"request":"{"event":"INBOX","from":"918879948245","to":"918291920455","text":"Let me know if u get this"}","response":"","status":200}';

      $curl = curl_init($url);
      // curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($curl, CURLOPT_HTTPHEADER,
      //         array("Content-type: application/json"));
      // curl_setopt($curl, CURLOPT_POST, true);
      // curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

      $response = curl_exec($curl);
      $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      // $params = [
      //   // 'request' => $content,
      //   'response' => $response,
      //   'status' => $status
      // ];
      // file_put_contents(__DIR__."/log.txt", json_encode($params));
      // file_put_contents(__DIR__."/status.txt", json_encode($status));
      // file_put_contents(__DIR__."/response.txt", json_encode($response));

      curl_close($curl);

      return response($response);

      // $xml = simplexml_load_string($response);

      // dd($xml);
      // dd($response);
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
        'courier'     => 'required|string|min:3|max:255',
        'from'        => 'sometimes|nullable|string|min:3|max:255',
        'date'        => 'sometimes|nullable',
        'awb'         => 'required|min:3|max:255',
        'l_dimension' => 'sometimes|nullable|numeric',
        'w_dimension' => 'sometimes|nullable|numeric',
        'h_dimension' => 'sometimes|nullable|numeric',
        'weight'      => 'sometimes|nullable|numeric',
        'pcs'         => 'sometimes|nullable|numeric',
      ]);

      Stock::find($id)->update($request->except('_token'));

      return redirect()->route('stock.show', $id)->with('success', 'You have successfully updated stock!');
    }

    public function privateViewing(Request $request)
    {
      $selected_customer = $request->customer_id ?? '';
      $type = $request->type ?? '';

      if ($selected_customer != '') {
        $private_views = PrivateView::where('customer_id', $selected_customer);
      }

      if ($type != '') {
        if ($selected_customer != '') {
          if ($type != 'no_boy') {
            $private_views = $private_views->where('status', $type);
          } else {
            $private_views = $private_views->whereNull('assigned_user_id');
          }
        } else {
          if ($type != 'no_boy') {
            $private_views = PrivateView::where('status', $type);
          } else {
            $private_views = PrivateView::whereNull('assigned_user_id');
          }
        }
      }

      if ($selected_customer == '' && $type == '') {
        $private_views = (new PrivateView)->newQuery();
      }

      $private_views = $private_views->paginate(Setting::get('pagination'));

      $users_array = Helpers::getUserArray(User::all());
      $customers_all = Customer::all();
      $office_boys = User::role('Office Boy')->get();

      return view('instock.private-viewing', [
        'private_views' => $private_views,
        'users_array'   => $users_array,
        'customers_all' => $customers_all,
        'selected_customer'      => $selected_customer,
        'office_boys'   => $office_boys,
        'type'   => $type,
      ]);
    }

    public function privateViewingStore(Request $request)
    {
      $products = json_decode($request->products);
      $product_information = '';

      foreach ($products as $key => $product_id) {
        $private_view = new PrivateView;
        $private_view->customer_id = $request->customer_id;
        $private_view->date = $request->date;
        $private_view->save();

        $private_view->products()->attach($product_id);

        $product = Product::find($product_id);
        // $product->supplier = '';
        // $product->save();
        if ($key == 0) {
          $product_information .= "$product->name - Size $product->size - $product->color";
        } else {
          $product_information .= ", $product->name - Size $product->size - $product->color";
        }
      }

      $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'private-viewing-message')->first();

			$auto_message = preg_replace("/{product_information}/i", $product_information, $auto_reply->reply);

      $params = [
        'number'    => NULL,
        'user_id'   => Auth::id(),
        'message'   => $auto_message,
        'approved'  => 0,
        'status'    => 1
      ];

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

      $chat_message = ChatMessage::create($params);

      $whatsapp_number = Auth::user()->whatsapp_number != '' ? Auth::user()->whatsapp_number : NULL;

			app('App\Http\Controllers\WhatsAppController')->sendWithNewApi('37067501865', $whatsapp_number, $params['message'], NULL, $chat_message->id);

      $chat_message->update([
        'approved' => 1,
        'status'   => 2
      ]);

      return redirect()->route('customer.show', $request->customer_id)->with('success', 'You have successfully added products for private viewing!');
    }

    public function privateViewingUpload(Request $request)
    {
      $this->validate($request, [
        'images'  => 'required'
      ]);

      $private_view = PrivateView::find($request->view_id);

      if ($request->hasfile('images')) {
        foreach ($request->file('images') as $image) {
          $media = MediaUploader::fromSource($image)
                                  ->toDirectory('privateview/'.floor($private_view->id / config('constants.image_per_folder')))
                                  ->upload();
          $private_view->attachMedia($media,config('constants.media_tags'));
        }
      }

      return redirect()->back()->with('success', 'You have successfully uploaded images!');
    }

    public function privateViewingUpdateStatus(Request $request, $id)
    {
      $private_view = PrivateView::find($id);
      $private_view->status = $request->status;
      $private_view->save();

      StatusChange::create([
        'model_id'    => $private_view->id,
        'model_type'  => PrivateView::class,
        'user_id'     => Auth::id(),
        'from_status' => $private_view->status,
        'to_status'   => $request->status
      ]);

      if ($private_view->delivery_approval) {
        $private_view->delivery_approval->status = $request->status;
        $private_view->delivery_approval->save();

        StatusChange::create([
          'model_id'    => $private_view->delivery_approval->id,
          'model_type'  => DeliveryApproval::class,
          'user_id'     => Auth::id(),
          'from_status' => $private_view->delivery_approval->status,
          'to_status'   => $request->status
        ]);
      }

      if ($request->status == 'delivered') {
        $private_view->products[0]->supplier = '';
        $private_view->products[0]->save();
      } elseif ($request->status == 'returned') {
        $private_view->products[0]->supplier = 'In-stock';
        $private_view->products[0]->save();
      }

      return response('success');
    }

    public function updateOfficeBoy(Request $request, $id)
    {
      $private_view = PrivateView::find($id);
      $private_view->assigned_user_id = $request->assigned_user_id;
      $private_view->save();

      $product_ids = [];

      foreach ($private_view->products as $product) {
        $product_ids[] = $product->id;
      }

      $requestData = new Request();
			$requestData->setMethod('POST');
			$requestData->request->add([
        'customer_id'       => $private_view->customer_id,
        'order_type'        => "offline",
        'convert_order'     => 'convert_order',
        'selected_product'  => $product_ids,
        'order_status'      => "Follow up for advance",
        'order_status_id'   => \App\Helpers\OrderHelper::$followUpForAdvance
      ]);

			$order = app('App\Http\Controllers\OrderController')->store($requestData);

      $delivery_approval = new DeliveryApproval;
      $delivery_approval->order_id = $order->id;
      $delivery_approval->private_view_id = $private_view->id;
      $delivery_approval->assigned_user_id = $request->assigned_user_id;
      $delivery_approval->status = $private_view->status;
      $delivery_approval->date = $private_view->date;
      $delivery_approval->save();

      $product_information = '';

      foreach ($private_view->products as $key => $product) {
        if ($key == 0) {
          $product_information .= "$product->name - Size $product->size - $product->color";
        } else {
          $product_information .= ", $product->name - Size $product->size - $product->color";
        }
      }

      $address = $private_view->customer->address . ", " . $private_view->customer->pincode . ", " . $private_view->customer->city;

      $auto_reply = AutoReply::where('type', 'auto-reply')->where('keyword', 'private-viewing-details')->first();

      $auto_message = preg_replace("/{customer_name}/i", $private_view->customer->name, $auto_reply->reply);
      $auto_message = preg_replace("/{customer_phone}/i", $private_view->customer->phone, $auto_message);
      $auto_message = preg_replace("/{customer_address}/i", $address, $auto_message);
      $auto_message = preg_replace("/{product_information}/i", $product_information, $auto_message);

      // $params['message'] = "Details for Private Viewing: Customer - " . $private_view->customer->name . ", Phone: " . $private_view->customer->phone . ", Address: $address" . "; Products $product_information";
      $params['message'] = $auto_message;

      $params['erp_user'] = $request->assigned_user_id;
      $chat_message = ChatMessage::create($params);

      $office_boy = User::find($request->assigned_user_id);

      $whatsapp_number = $office_boy->whatsapp_number != '' ? $office_boy->whatsapp_number : NULL;

      app('App\Http\Controllers\WhatsAppController')->sendWithNewApi($office_boy->phone, $whatsapp_number, $params['message'], NULL, $chat_message->id);

      $chat_message->update([
        'approved' => 1,
        'status'   => 2
      ]);

      return response('success');
    }

    public function privateViewingDestroy($id)
    {
      $private_view = PrivateView::find($id);

      $private_view->products()->detach();

      $private_view->delete();

      return redirect()->route('stock.private.viewing')->withSuccess('You have successfully deleted private viewing record!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Stock::find($id)->delete();

      return redirect()->route('stock.index')->with('success', 'You have successfully archived stock');
    }

    public function permanentDelete($id)
    {
      $stock = Stock::find($id);
      $stock->products()->detach();
      $stock->forceDelete();

      return redirect()->route('stock.index')->with('success', 'You have successfully deleted stock');
    }
}
