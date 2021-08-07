<?php

namespace App\Http\Controllers;

use App\QuickReply;
use App\Reply;
use App\ReplyCategory;
use App\StoreWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zend\Diactoros\Response\JsonResponse;

class QuickReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replies = QuickReply::all();

        return view('quick_reply.index', compact('replies'));
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
            'text' => 'required'
        ]);

        $r = new QuickReply();
        $r->text = $request->get('text');
        $r->save();

        return redirect()->back()->with('message', 'Quick reply added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QuickReply  $quickReply
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reply = QuickReply::findOrFail($id);

        $reply->delete();

        return redirect()->back()->with('message', 'Deleted successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QuickReply  $quickReply
     * @return \Illuminate\Http\Response
     */
    public function edit(QuickReply $quickReply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QuickReply  $quickReply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuickReply $quickReply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\QuickReply  $quickReply
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuickReply $quickReply)
    {
        //
    }

    public function quickReplies()
    {
        try {
            $all_categories = ReplyCategory::all();
            $store_websites = StoreWebsite::all();
            $website_length = 0;
            if(count($store_websites) > 0){
                $website_length = count($store_websites);
            }
            //all categories replies related to store website id
            $all_replies = DB::select("SELECT * from replies");
            $category_wise_reply = [];
            foreach($all_replies as $replies){
                    $category_wise_reply[$replies->category_id][$replies->store_website_id][$replies->id] = $replies;
            }
            return view('quick_reply.quick_replies', compact('all_categories', 'store_websites', 'website_length','category_wise_reply'));
        } catch (\Exception $e) {
            return redirect()->back();
        }

    }

    public function getStoreWiseReplies($category_id, $store_website_id = null)
    {
        try {
            
            $replies = ( $store_website_id ) 
                        ? Reply::where(['category_id' => $category_id, 'store_website_id' => $store_website_id])->get()
                        : Reply::where(['category_id' => $category_id])->get();
            return new JsonResponse(['status' => 1, 'data' => $replies]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 0, 'message' => 'Try again']);
        }
    }

    public function saveStoreWiseReply(Request $request){
        try {
            if(isset($request->reply_id)){
                //update reply
                Reply::where('id','=',$request->reply_id)->update([
                    'reply' => $request->reply,
                ]);
                return new JsonResponse(['status' => 1, 'data' =>  $request->reply, 'message' => 'Reply updated successfully']);
            }else{
                Reply::create([
                    'category_id' => $request->category_id,
                    'store_website_id' => $request->store_website_id,
                    'reply' => $request->reply,
                    'model' => 'Store Website'
                ]);
                return new JsonResponse(['status' => 1, 'data' =>  $request->reply, 'message' => 'Reply added successfully']);
            }
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 0, 'message' => 'Try again']);
        }
    }





}
