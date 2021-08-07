<?php

namespace App\Http\Controllers;

use App\AutomatedMessages;
use App\HashtagPostHistory;
use App\InstagramAutomatedMessages;
use Illuminate\Http\Request;
use InstagramAPI\Instagram;

class InstagramAutomatedMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replies = InstagramAutomatedMessages::all();

        return view('instagram.am.index', compact('replies'));

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
     * SImply create the message
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'sender_type' => 'required',
            'receiver_type' => 'required',
            'reusable' => 'required',
            'message' => 'required',
            'account_id' => 'required',
            'target_id' => 'required'
        ]);

        $reply = new InstagramAutomatedMessages();
        $reply->type = $request->get('type');
        $reply->account_id = $request->get('account_id');
        $reply->target_id = $request->get('target_id');
        $reply->sender_type = $request->get('sender_type');
        $reply->receiver_type = $request->get('receiver_type');
        $reply->reusable = $request->get('reusable');
        $reply->message = $request->get('message');
        $reply->status = 1;
        $reply->save();


        return redirect()->back()->with('message', 'The automated reply added successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InstagramAutomatedMessages  $instagramAutomatedMessages
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comments = HashtagPostHistory::all();

        return view('instagram.am.comments', compact('comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InstagramAutomatedMessages  $instagramAutomatedMessages
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reply = InstagramAutomatedMessages::findOrFail($id);

        return view('instagram.am.edit', compact('reply'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InstagramAutomatedMessages  $instagramAutomatedMessages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reply = InstagramAutomatedMessages::findOrFail($id);

        $this->validate($request, [
            'message' => 'required'
        ]);

        $reply->type = $request->get('type');
        $reply->sender_type = $request->get('sender_type');
        $reply->receiver_type = $request->get('receiver_type');
        $reply->reusable = $request->get('reusable');
        $reply->message = $request->get('message');
        $reply->save();

        return redirect()->action('InstagramAutomatedMessagesController@index')->with('message', 'Update successful!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $msg = InstagramAutomatedMessages::findOrFail($id);

        if ($msg) {
            $msg->delete();
        }

        return redirect()->action('InstagramAutomatedMessagesController@index')->with('success', 'Message deleted successfully!');
    }
}
