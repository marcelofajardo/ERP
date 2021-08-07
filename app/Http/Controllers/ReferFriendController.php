<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReferFriend;
class ReferFriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        
        $query = ReferFriend::query();

		if($request->id){
			$query = $query->where('id', $request->id);
		}
		if($request->term){
            $query = $query->where('referrer_email', 'LIKE','%'.$request->term.'%')
                    ->orWhere('referee_email', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('website', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('referrer_phone', 'LIKE', '%'.$request->term.'%')
                    ->orWhere('referee_phone', 'LIKE', '%'.$request->term.'%');
		}

		$data = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
		if ($request->ajax()) {
            return response()->json([
                'tbody' => view('referfriend.partials.list-referral', compact('data'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$data->render(),
                'count' => $data->total(),
            ], 200);
        }
		return view('referfriend.index', compact('data'))
			->with('i', ($request->input('page', 1) - 1) * 5);
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
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ReferFriend = ReferFriend::find($id);

		// NotificationQueue::where('sent_to', $user->id)->orWhere('user_id', $user->id)->delete();
		// PushNotification::where('sent_to', $user->id)->orWhere('user_id', $user->id)->delete();

		$ReferFriend->delete();

		return redirect()->route('referfriend.list')
			->with('success', 'Referral deleted successfully');
    }
}
