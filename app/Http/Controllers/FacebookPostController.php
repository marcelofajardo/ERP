<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FacebookPost;
use App\Account;
use Auth;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Validator;
class FacebookPostController extends Controller
{
    public function index(Request $request) {
        $posts =  FacebookPost::query();
        $account_id = $request->account_id;
        $term = $request->term;
        if($request->account_id) {
            $posts = $posts->where('account_id',$account_id);
        }
        if($term) {
            $posts = $posts->where(function($q) use ($term) {
                $q->where('caption', 'like', '%'.$term.'%')
                ->orWhere('post_body', 'like', '%'.$term.'%');
            });
        }
        $posts = $posts->paginate(30);
        $totalPosts = $posts->total();
        $accounts = Account::where('platform','facebook')->where('status',1)->get();
        if($request->ajax()) {
            return view('facebook.data',compact('posts','accounts','account_id','term','totalPosts'));
        }
        return view('facebook.index',compact('posts','accounts','account_id','term','totalPosts'));
    }

    public function create() {
        $accounts = Account::where('platform','facebook')->where('status',1)->get();
        return view('facebook.create',compact('accounts'));
    }


    public function store(Request $request) {
        if(!$request->account_id || $request->account_id == '') {
				return response()->json(['message' => 'Select Account','code' => 500]);
        }
        $post =  new FacebookPost;
        $post->account_id = $request->account_id;
        $post->caption = $request->caption;
        $post->post_body = $request->post_body;
        $post->post_by = Auth::user()->id;
        $post->save();



        if( !empty($request->file('image') ) ){
            $media = MediaUploader::fromSource($request->file('image'))
                                    ->toDirectory('facebook/'.floor($post->id / config('constants.image_per_folder')))
                                    ->upload();
            $post->attachMedia($media,config('constants.media_tags'));
        }
        return response()->json(['message' => 'Successfull','code' => 200]);
    }

    /**
     * @SWG\Post(
     *   path="/facebook/account",
     *   tags={"Facebook"},
     *   summary="facbook posts details",
     *   operationId="facbook-posts-details",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function getPost(Request $request) {
        $account = Account::where('email', $request->email)->first();
        if(!$account) {
            return response()->json(['message' => 'Account not found with this email','code' => 403]);
        }
        $post = FacebookPost::where('account_id', $account->id)->where('status',0)->orderBy('created_at')->first();
        if(!$post) {
            return response()->json(['message' => 'No post found','code' => 404]);
        }
        if($post->getMedia(config('constants.media_tags'))->first()) {
            $url = $post->getMedia(config('constants.media_tags'))->first()->getUrl();
            $data = [
                "queueNumber" => $post->id ,
                "username" => $request->email,
                "body" => $url,
                "filename" => '',
                "caption" => $post->caption
            ];
        }
        else {
            $data = [
                "queueNumber" => $post->id ,
                "username" => $request->email,
                "body" => $post->post_body
            ];
        }
        return response()->json(['data' => $data,'code' => 200]);
    }

    /**
     * @SWG\Post(
     *   path="/facebook/post/status",
     *   tags={"Facebook"},
     *   summary="Set facbook post status",
     *   operationId="set-facbook-post-status",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function setPostStatus(Request $request) {
        $post = FacebookPost::where('id', $request->queueNumber)->first();
        if(!$post) {
            return response()->json(['message' => 'No post found','code' => 404]);
        }
        $post->status = $request->status;
        $post->posted_on = \Carbon\Carbon::now();
        $post->save();
        return response()->json(['message' => 'Successfull','code' => 200]);
    }
}
