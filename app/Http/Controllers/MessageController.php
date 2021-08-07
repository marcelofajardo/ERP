<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Leads;
use App\Order;
use App\Product;
use App\Customer;
use App\ChatMessage;
use App\PushNotification;
use App\NotificationQueue;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Image;
use Storage;
use File;
use \App\ChatbotDialog;
use \App\ChatbotDialogResponse;
use App\Library\Watson\Model as WatsonManager;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::with(['user'])->get();

        return response()->json($messages);
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
        $message = $this->validate($request, [
              'body' => 'required',
              'moduleid' => 'required',
              'moduletype' => 'required',
              'status' => 'required',
            ]);

            $data = $request->except( '_token');
            $id = $request->get('moduleid');
            $moduletype = $request->get('moduletype');

            if ($moduletype == 'leads') {
              $lead = Leads::find($id);

              if ($lead->customer) {
                $data['customer_id'] = $lead->customer->id;
              }
            } elseif ($moduletype == 'order') {
              $order = Order::find($id);

              if ($order->customer) {
                $data['customer_id'] = $order->customer->id;
              }
            } elseif ($moduletype == 'customer') {
              $customer = Customer::find($id);
              $data['customer_id'] = $customer->id;
            }

            $data['userid'] = Auth::id();
            if ($data['status'] == '4')
              $data['assigned_to'] = $data['assigned_user'];

            $message = Message::create($data);

            if ($request->hasFile('image')) {
              $media = MediaUploader::fromSource($request->file('image'))
                                      ->toDirectory('message/'.floor($message->id / config('constants.image_per_folder')))
                                      ->upload();
              $message->attachMedia($media,config('constants.media_tags'));
            }

            if ($request->images) {
              foreach (json_decode($request->images) as $image) {
                // $product = Product::find($product_id);
                // $media = $product->getMedia(config('constants.media_tags'))->first();
                $media = Media::find($image);
                $message->attachMedia($media,config('constants.media_tags'));
              }
            }

            if ($request->screenshot_path != '') {
              $image_path = public_path() . '/uploads/temp_screenshot.png';
              $img = substr($request->screenshot_path, strpos($request->screenshot_path, ",")+1);
              $img = Image::make(base64_decode($img))->encode('png')->save($image_path);

              $media = MediaUploader::fromSource($image_path)
                                      ->toDirectory('message/'.floor($message->id / config('constants.image_per_folder')))
                                      ->upload();
              $message->attachMedia($media,config('constants.media_tags'));

              File::delete('uploads/temp_screenshot.png');
            }

            // if( $data['status'] == '1' ) {
            //
	          //   NotificationQueueController::createNewNotification( [
		        //     'message'    => 'New : ' . $data['body'],
		        //     'timestamps' => [ '+0 minutes'],
		        //     'model_type' => $data['moduletype'],
		        //     'model_id'   => $data['moduleid'],
            //     'message_id' => $message->id,
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => '',
		        //     'role'       => 'Admin',
	          //   ] );
            //
            //   NotificationQueueController::createNewNotification( [
		        //     'message'    => 'Reminder : ' . $data['body'],
            //     // 'timestamps' => [ '+5 minutes',  '+10 minutes',  '+15 minutes',  '+20 minutes',  '+25 minutes',  '+30 minutes',  '+35 minutes',  '+40 minutes',  '+45 minutes',  '+50 minutes',  '+55 minutes',  '+60 minutes',  '+65 minutes',  '+70 minutes',  '+75 minutes',  '+80 minutes',  '+85 minutes',  '+90 minutes',  '+95 minutes',  '+100 minutes'],
		        //     'timestamps' => [ '+0 minutes'],
            //     // 'reminder'   => 1,
            //     'message_id' => $message->id,
		        //     'model_type' => $data['moduletype'],
		        //     'model_id'   => $data['moduleid'],
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => '',
		        //     'role'       => 'Admin',
	          //   ] );
            //
            // }
            // else if($data['status'] == '0'){
            //
	          //   NotificationQueueController::createNewNotification( [
		        //     'message'    => "Reply : " . $data['body'],
		        //     'timestamps' => [ '+0 minutes' ],
		        //     'model_type' => $data['moduletype'],
            //     'message_id' => $message->id,
		        //     'model_id'   => $data['moduleid'],
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => $data['assigned_user'],
            //     'role'       => ''
	          //   ] );
            //
            //   NotificationQueueController::createNewNotification( [
		        //     'message'    => "Reply : " . $data['body'],
		        //     'timestamps' => [ '+0 minutes' ],
		        //     'model_type' => $data['moduletype'],
		        //     'model_id'   => $data['moduleid'],
            //     'message_id' => $message->id,
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => '',
		        //     'role'       => 'Admin',
	          //   ] );
            //
            //   NotificationQueueController::createNewNotification( [
		        //     'message'    => 'Reminder to Reply : ' . $data['body'],
            //     // 'timestamps' => [ '+5 minutes',  '+10 minutes',  '+15 minutes',  '+20 minutes',  '+25 minutes',  '+30 minutes',  '+35 minutes',  '+40 minutes',  '+45 minutes',  '+50 minutes',  '+55 minutes',  '+60 minutes',  '+65 minutes',  '+70 minutes',  '+75 minutes',  '+80 minutes',  '+85 minutes',  '+90 minutes',  '+95 minutes',  '+100 minutes'],
            //     'timestamps' => [ '+0 minutes'],
            //     // 'reminder'   => 1,
            //     'message_id' => $message->id,
		        //     'model_type' => $data['moduletype'],
		        //     'model_id'   => $data['moduleid'],
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => $data['assigned_user'],
            //     'role'       => ''
	          //   ] );
            //
            //   NotificationQueueController::createNewNotification( [
		        //     'message'    => 'Reminder to Reply : ' . $data['body'],
            //     // 'timestamps' => [ '+5 minutes',  '+10 minutes',  '+15 minutes',  '+20 minutes',  '+25 minutes',  '+30 minutes',  '+35 minutes',  '+40 minutes',  '+45 minutes',  '+50 minutes',  '+55 minutes',  '+60 minutes',  '+65 minutes',  '+70 minutes',  '+75 minutes',  '+80 minutes',  '+85 minutes',  '+90 minutes',  '+95 minutes',  '+100 minutes'],
            //     'timestamps' => [ '+0 minutes'],
            //     // 'reminder'   => 1,
            //     'message_id' => $message->id,
		        //     'model_type' => $data['moduletype'],
		        //     'model_id'   => $data['moduleid'],
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => '',
		        //     'role'       => 'Admin',
	          //   ] );
            // } else if($data['status'] == '4'){
            //
	          //   NotificationQueueController::createNewNotification( [
		        //     'message'    => 'New Instructions : ' . $data['body'],
		        //     'timestamps' => [ '+0 minutes' ],
		        //     'model_type' => $data['moduletype'],
		        //     'model_id'   => $data['moduleid'],
            //     'message_id' => $message->id,
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => $data['assigned_user'],
            //     'role'       => ''
	          //   ] );
            //
            //   NotificationQueueController::createNewNotification( [
		        //     'message'    => 'New Instructions : ' . $data['body'],
		        //     'timestamps' => [ '+0 minutes' ],
		        //     'model_type' => $data['moduletype'],
		        //     'model_id'   => $data['moduleid'],
            //     'message_id' => $message->id,
		        //     'user_id'    => \Auth::id(),
		        //     'sent_to'    => '',
            //     'role'       => 'Admin'
	          //   ] );
            // }

            if ($moduletype == 'product') {
              $moduletype = 'purchase/product';
            }

            if ($request->ajax()) {
              return '';
            }

           return redirect('/'. $moduletype.'/'.$id);
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

    public function downloadImages(Request $request)
    {
      $new_match = [];
      preg_match_all('/<img src="(.*?)" class="message-img/', $request->images, $match);

      foreach ($match[1] as $image) {
        $exploded = explode('uploads/', $image);

        array_push($new_match, public_path('uploads/' . $exploded[1]));
      }

      \Zipper::make(public_path('images.zip'))->add($new_match)->close();

      return response()->download(public_path('images.zip'))->deleteFileAfterSend();
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
      if ($request->type == 'message') {
        $message = Message::find($id);
        $message->body = $request->get('body');
        $message->save();
      } elseif ($request->type == 'whatsapp') {
        $message = ChatMessage::find($id);

        if($message->is_chatbot == 1) {
          $oldMessage           = $message->message;
          // find the old message into dilog and update the new one
          $dialogResponse = ChatbotDialogResponse::where("value", $oldMessage)->get();
          if(!$dialogResponse->isEmpty()) {
            foreach($dialogResponse as $response) {
                $response->value = $request->get('body');
                $response->save();
                WatsonManager::pushDialog($response->chatbot_dialog_id);
            }
          }
        }


        $message->message = $request->get('body');
        $message->save();
      }

      return response(['message' => 'Success']);
    }

    public function updatestatus(Request $request )
    {
        $message = Message::find($request->get('id'));
        $message->status = $request->get('status');
        $moduleid = $request->get('moduleid');
        $moduletype = $request->get('moduletype');
        $message->save();


	    // if( $message->status == '2' ) {
      //   // $notification_queues = NotificationQueue::where('model_id', $message->moduleid)->where('model_type', $message->moduletype)->delete();
      //   if ($notifications = PushNotification::where('message_id', $message->id)->where('model_type', $message->moduletype)->get()) {
      //     foreach ($notifications as $notification) {
      //       $notification->isread = 1;
      //       $notification->save();
      //     }
      //   }
      //
      //   if ($notifications_queue = NotificationQueue::where('message_id', $message->id)->where('model_type', $message->moduletype)->get()) {
      //     foreach ($notifications_queue as $notification) {
      //       $notification->delete();
      //     }
      //   }
      //
		  //   // NotificationQueueController::createNewNotification( [
			//   //   'message'    => 'Approved : ' . $message->body,
			//   //   'timestamps' => [ '+0 minutes' ],
      //   //   'message_id' => $message->id,
			//   //   'model_type' => $message->moduletype,
			//   //   'model_id'   => $message->moduleid,
			//   //   'user_id'    => Auth::id(),
			//   //   'sent_to'    => '',
			//   //   'role'       => 'message',
		  //   // ] );
      //
      //   // NotificationQueueController::createNewNotification( [
			//   //   'message'    => 'Approved Reminder: ' . $message->body,
			//   //   // 'timestamps' => [ '+0 minutes' ],
      //   //   'timestamps' => [ '+5 minutes',  '+10 minutes',  '+15 minutes',  '+20 minutes',  '+25 minutes',  '+30 minutes',  '+35 minutes',  '+40 minutes',  '+45 minutes',  '+50 minutes',  '+55 minutes',  '+60 minutes',  '+65 minutes',  '+70 minutes',  '+75 minutes',  '+80 minutes',  '+85 minutes',  '+90 minutes',  '+95 minutes',  '+100 minutes'],
      //   //   'reminder'   => 1,
      //   //   'message_id' => $message->id,
			//   //   'model_type' => $message->moduletype,
			//   //   'model_id'   => $message->moduleid,
			//   //   'user_id'    => Auth::id(),
			//   //   'sent_to'    => '',
			//   //   'role'       => 'message',
		  //   // ] );
      //
      //   // NotificationQueueController::createNewNotification( [
			//   //   'message'    => 'Approved : ' . $message->body,
			//   //   'timestamps' => [ '+0 minutes' ],
      //   //   // 'timestamps' => [ '+5 minutes',  '+10 minutes',  '+15 minutes',  '+20 minutes',  '+25 minutes',  '+30 minutes',  '+35 minutes',  '+40 minutes',  '+45 minutes',  '+50 minutes',  '+55 minutes',  '+60 minutes',  '+65 minutes',  '+70 minutes',  '+75 minutes',  '+80 minutes',  '+85 minutes',  '+90 minutes',  '+95 minutes',  '+100 minutes'],
      //   //   // 'reminder'   => 1,
      //   //   'message_id' => $message->id,
			//   //   'model_type' => $message->moduletype,
			//   //   'model_id'   => $message->moduleid,
			//   //   'user_id'    => Auth::id(),
			//   //   'sent_to'    => $message->userid,
			//   //   'role'       => '',
		  //   // ] );
	    // }

      // if( $message->status == '3' ) {
      //   // $notification_queues = NotificationQueue::where('model_id', $message->moduleid)->where('model_type', $message->moduletype)->delete();
      //   if ($notifications = PushNotification::where('message_id', $message->id)->where('model_type', $message->moduletype)->get()) {
      //     foreach ($notifications as $notification) {
      //       $notification->isread = 1;
      //       $notification->save();
      //     }
      //   }
      //
      //   if ($notifications_queue = NotificationQueue::where('message_id', $message->id)->where('model_type', $message->moduletype)->get()) {
      //     foreach ($notifications_queue as $notification) {
      //       $notification->delete();
      //     }
      //   }
      //
		  //   // NotificationQueueController::createNewNotification( [
			//   //   'message'    => 'Message Sent : ' . $message->body,
			//   //   'timestamps' => [ '+0 minutes' ],
			//   //   'model_type' => $message->moduletype,
			//   //   'model_id'   => $message->moduleid,
      //   //   'message_id' => $message->id,
			//   //   'user_id'    => Auth::id(),
			//   //   'sent_to'    => $message->userid,
			//   //   'role'       => 'Admin',
		  //   // ] );
	    // }

      // if( $message->status == '5' ) {
		  //   NotificationQueueController::createNewNotification( [
			//     'message'    => 'Message was read : ' . $message->body,
			//     'timestamps' => [ '+0 minutes' ],
			//     'model_type' => $message->moduletype,
			//     'model_id'   => $message->moduleid,
      //     'message_id' => $message->id,
			//     'user_id'    => Auth::id(),
			//     'sent_to'    => '',
			//     'role'       => 'Admin',
		  //   ] );
	    // }

      // if( $message->status == '6' ) {
      //   // $notification_queues = NotificationQueue::where('model_id', $message->moduleid)->where('model_type', $message->moduletype)->delete();
      //   if ($notifications = PushNotification::where('message_id', $message->id)->where('model_type', $message->moduletype)->get()) {
      //     foreach ($notifications as $notification) {
      //       $notification->isread = 1;
      //       $notification->save();
      //     }
      //   }
      //
      //   if ($notifications_queue = NotificationQueue::where('message_id', $message->id)->where('model_type', $message->moduletype)->get()) {
      //     foreach ($notifications_queue as $notification) {
      //       $notification->delete();
      //     }
      //   }
      //
		  //   NotificationQueueController::createNewNotification( [
			//     'message'    => 'Message Sent : ' . $message->body,
			//     'timestamps' => [ '+0 minutes' ],
			//     'model_type' => $message->moduletype,
			//     'model_id'   => $message->moduleid,
      //     'message_id' => $message->id,
			//     'user_id'    => Auth::id(),
			//     'sent_to'    => $message->userid,
			//     'role'       => 'Admin',
		  //   ] );
	    // }

	    // return redirect('/'. $moduletype.'/'.$moduleid);
    }

    public function loadmore(Request $request)
    {
         $moduleid = $request->get('moduleid');
         $moduletype = $request->get('moduletype');
         $messageid = $request->get('messageid');
         $messages = Message::all()->where('id','<',$messageid)->where('moduleid','=', $moduleid)->where('moduletype','=', $moduletype)->sortByDesc("created_at")->take(10)->toArray();
         return view('leads.bubbles',compact(['messages', 'moduletype']));
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

    public function removeImage(Request $request, $id)
    {
      if ($request->type == 'message') {
        $message = Message::find($id);
      } else {
        $message = ChatMessage::find($id);
      }

      $message->detachMedia($request->image_id, config('constants.media_tags'));

      return response('');
    }

    
}
