<?php

namespace App\Http\Controllers;

use App\Dubbizle;
use App\Helpers;
use App\User;
use App\ChatMessage;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DubbizleController extends Controller
{

    public function updateReminder(Request $request) {
        $supplier = Dubbizle::find($request->get('dubbizle_id'));
        $supplier->frequency = $request->get('frequency');
        $supplier->reminder_message = $request->get('message');
        $supplier->save();

        return response()->json([
            'success'
        ]);
    }

    public function index() {
        // $posts = Dubbizle::all();

        $posts = DB::select('
                    SELECT *,
     							 (SELECT mm3.id FROM chat_messages mm3 WHERE mm3.id = message_id) AS message_id,
                    (SELECT mm1.message FROM chat_messages mm1 WHERE mm1.id = message_id) as message,
                    (SELECT mm2.status FROM chat_messages mm2 WHERE mm2.id = message_id) AS message_status,
                    (SELECT mm4.sent FROM chat_messages mm4 WHERE mm4.id = message_id) AS message_type,
                    (SELECT mm2.created_at FROM chat_messages mm2 WHERE mm2.id = message_id) as last_communicated_at

                    FROM (
                      SELECT * FROM dubbizles

                      LEFT JOIN (SELECT MAX(id) as message_id, dubbizle_id, message, MAX(created_at) as message_created_At FROM chat_messages WHERE chat_messages.status != 7 AND chat_messages.status != 8 AND chat_messages.status != 9 GROUP BY dubbizle_id ORDER BY chat_messages.created_at DESC) AS chat_messages
                      ON dubbizles.id = chat_messages.dubbizle_id

                    ) AS dubbizles
                    WHERE id IS NOT NULL
                    ORDER BY last_communicated_at DESC;
     						');

        $keywords = Dubbizle::select('keywords')->get()->groupBy('keywords');

        // dd($keywords);

                // dd($posts);

        return view('dubbizle', [
          'posts'     => $posts,
          'keywords'  => $keywords,
        ]);
    }

    public function show($id)
    {
      $dubbizle = Dubbizle::find($id);
      $users_array = Helpers::getUserArray(User::all());

      return view('dubbizle-show', [
        'dubbizle'  => $dubbizle,
        'users_array'  => $users_array,
      ]);
    }

    public function bulkWhatsapp(Request $request)
    {
      $this->validate($request, [
        'group'   => 'required|string',
        'message' => 'required|string',
      ]);

      $params = [
        'user_id'   => Auth::id(),
        'number'    => NULL,
        'message'   => $request->message,
        'approved'  => 0,
        'status'    => 1
      ];

      $dubbizles = Dubbizle::where('keywords', $request->group)->get();

      foreach ($dubbizles as $dubbizle) {
        $params['dubbizle_id'] = $dubbizle->id;

        $chat_message = ChatMessage::create($params);

        app('App\Http\Controllers\WhatsAppController')->sendWithNewApi($dubbizle->phone_number, '919152731483', $params['message'], NULL, $chat_message->id);

        $chat_message->update([
          'approved'    => 1,
          'status'      => 2,
          'created_at'  => Carbon::now()
        ]);
      }

      return redirect('/scrap/dubbizle')->withSuccess('You have successfully sent bulk whatsapp messages');
    }

    public function edit($id) {
        $d = Dubbizle::findOrFail($id);

        return view('dubbizle-edit', compact('d'));
    }

    public function update($id, Request $request) {
        $d = Dubbizle::findOrFail($id);

        $d->url = $request->get('url');
        $d->phone_number = $request->get('phone_number');
        $d->keywords = $request->get('keywords');
        $d->post_date = $request->get('post_date');
        $d->requirements = $request->get('requirements');
        $d->body = $request->get('body');
        $d->phone_number = $request->get('phone_number');
        $d->save();

        return redirect()->back()->with('success', 'Record updated successfully!');
    }
}
