<?php

namespace App\Http\Controllers;




use App\Account;
use App\HashTag;
use App\InstagramPosts;
use App\Post;
use App\InstagramPostsComments;
use App\Setting;
use Illuminate\Http\Request;
use InstagramAPI\Instagram;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use File;
use App\CommentsStats;
use App\InstagramCommentQueue;
use App\ScrapInfluencer;
use Carbon\Carbon;
use App\InstagramUsersList;
use App\Library\Instagram\PublishPost;
use Plank\Mediable\Media;
use App\StoreSocialContent;
use App\InstagramPostLog;
use App\InstagramLog;
use UnsplashSearch;
use App\Jobs\InstaSchedulePost;
\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;



class InstagramPostsController extends Controller
{
    public function index(Request $request)
    {
        // Load posts
        if($request->hashtag){
            $posts = $this->_getFilteredInstagramPosts($request);
        }else{
            $posts = InstagramPosts::orderBy('id','desc');
        }
        // Paginate
        $posts = $posts->paginate(Setting::get('pagination'));

     
        // Return view
        return view('social-media.instagram-posts.index', compact('posts'));
    }


    public function post(Request $request)
    {

       
       $images = $request->get('images', false);
       $mediaIds = $request->get('media_ids', false);

        $productArr = null;
        if ($images) {
            $productIdsArr = \DB::table('mediables')
                                ->whereIn('media_id', json_decode($images))
                                ->where('mediable_type', 'App\Product')
                                ->pluck('mediable_id')
                                ->toArray();
            
            if (!empty($productIdsArr)) {
                $productArr = \App\Product::select('id', 'name', 'sku', 'brand')->whereIn('id', $productIdsArr)->get();
            }
        }

        $mediaIdsArr = null;
        if( $mediaIds ){
            $mediaIdsArr = \DB::table('mediables')
                        ->whereIn('media_id', explode(',',$mediaIds))
                        ->where('mediable_type', 'App\StoreWebsite')
                        ->get();
        }
        //$accounts = \App\Account::where('platform','instagram')->whereNotNull('proxy')->where('status',1)->get();
        $accounts = \App\Account::where('platform','instagram')->where('status',1)->get();

        //$posts = Post::where('status', 1)->get();
        
        $query = Post::query();
        
        if($request->acc){
            $query = $query->where('id', $request->acc);
        }
        if($request->comm){
            $query = $query->where('comment', 'LIKE','%'.$request->comm.'%');
        }
        if($request->tags){
            $query = $query->where('hashtags', 'LIKE','%'.$request->tags.'%');
        }
        if($request->loc){
            $query = $query->where('location', 'LIKE','%'.$request->loc.'%');
        }
        if($request->select_date){
            $query = $query->whereDate('created_at',$request->select_date);
        }
        $posts = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));


        $used_space = 0;
        $storage_limit = 0;
        $contents = StoreSocialContent::query();
        $contents = $contents->get();
        $records = [];
        foreach($contents as $site) {
            if ($site) {
                    if ($site->hasMedia(config('constants.media_tags'))) {
                        foreach ($site->getMedia(config('constants.media_tags')) as $media) {                                  
                            $records[] = [
                                "id"        => $media->id,
                                'extension' => strtolower($media->extension), 
                                'file_name' => $media->filename, 
                                'mime_type' => $media->mime_type, 
                                'size' => $media->size , 
                                'thumb' => $media->getUrl() , 
                                'original' => $media->getUrl() 
                            ];
                        }
                    }
            }
        }

        $imagesHtml='';
        if(isset($productArr) && count($productArr)):
            foreach($productArr as $product):
                foreach($product->media as $media):
                    $imagesHtml.='<div class="media-file">    <label class="imagecheck m-1">        <input name="media[]" type="checkbox" value="'.$media->id.'" data-original="'.$media->getUrl().'" class="imagecheck-input">        <figure class="imagecheck-figure">            <img src="'.$media->getUrl().'" alt="'.$product->name.'" class="imagecheck-image" style="cursor: default;">        </figure>    </label><p style="font-size: 11px;"></p></div>';
                endforeach;
            endforeach;
        endif;

        if(isset($mediaIdsArr) && !empty($mediaIdsArr)):
            foreach($mediaIdsArr as $image):
                $media = Media::where('id',$image->media_id)->get();
                if(!empty($media)):
                    $imagesHtml.='<div class="media-file">    <label class="imagecheck m-1">        <input name="media[]" type="checkbox" value="'.$media[0]->getkey().'" data-original="'.$media[0]->getUrl().'" class="imagecheck-input">        <figure class="imagecheck-figure">            <img src="'.$media[0]->getUrl().'" alt="Images" class="imagecheck-image" style="cursor: default;">        </figure>    </label><p style="font-size: 11px;"></p></div>';
                endif;
            endforeach;
        endif;
       
        return view('instagram.post.create' , compact('accounts','records','used_space','storage_limit', 'posts','imagesHtml'))->with('i', ($request->input('page', 1) - 1) * 5);;   
    }

    public function createPost(Request $request){
        
        //resizing media 
        $all = $request->all();
        
        if($request->media)
        {
            foreach ($request->media as $media) {
               
                $mediaFile = Media::where('id',$media)->first();
                $image = self::resize_image_crop($mediaFile,640,640);
            }
        }

        if($request->postId){
            $userPost = InstagramPosts::find($request->postId);
            foreach($userPost->getMedia('instagram') as $media){
                $image = self::resize_image_crop($media,640,640);
                $mediaPost = $media->id;
                break; 
            }
        }
        
        if(!isset($mediaPost)){
            $mediaPost = $request->media;
        }

        if(empty($request->location)){
            $location = '';
        }else{
            $location = $request->location;
        }
        
        if(empty($request->hashtags)){
            $hashtag = '';
        }else{
            $hashtag = $request->hashtags;
        }
        
        $post = new Post();
        $post->account_id = $request->account;
        $post->type       = $request->type;
        $post->caption    = $request->caption.' '.$hashtag;
        $ig         = [
            'media'    => $mediaPost,
            'location' => $location,
        ];
        $post->ig       = json_encode($ig);
        $post->location = $location;
        $post->hashtags = $hashtag;
        $post->scheduled_at = $request->scheduled_at;
        $post->save();
        $newPost = Post::find($post->id);

        $media = json_decode($newPost->ig,true);

        $ig         = [
            'media'    => $media['media'],
            'location' => $location,
            'hashtag'  => $hashtag,
        ];
        $newPost->ig = $ig;

        if( $request->scheduled === "1" ){
            
            $diff = strtotime($request->scheduled_at) - strtotime( now() );
            InstaSchedulePost::dispatch( $newPost )->onQueue('InstaSchedulePost')->delay( $diff );
            return redirect()->back()->with('message', __('Your post schedule has been saved'));
        }
        
        // Publish Post on instagram
        if (new PublishPost($newPost)) {
            $this->createPostLog($newPost->id,"success",'Your post has been published');

            if($request->ajax()){
                return response()->json('Your post has been published', 200);
            }else{
                return redirect()->route('post.index')
                ->with('success', __('Your post has been published'));
            }

        } else {
            $this->createPostLog($newPost->id,"error",'Post failed to published');
            if($request->ajax()){
                return response()->json('Post failed to published', 200);
            }else{
                return redirect()->route('post.index')
                ->with('error', __('Post failed to published'));
            }

        }

    }


    public function publishPost(Request $request, $id){
       
        $post = Post::find($id);
        $media = json_decode($post->ig,true);
        $ig         = [
            'media'    => $post->media,
            'location' => '',
        ];
        $post->ig = $ig;
        if (new PublishPost($post)) {
            $this->createPostLog($newPost->id,"success",'Your post has been published');
            return redirect()->route('post.index')
                ->with('success', __('Your post has been published'));
        } else {
            $this->createPostLog($newPost->id,"error",'Post failed to published');
            return redirect()->route('post.index')
                ->with('error', __('Post failed to published'));
        }

    }



    public function grid(Request $request)
    {
        // Load posts
        $posts = $this->_getFilteredInstagramPosts($request);

        // Paginate
        $posts = $posts->paginate(Setting::get('pagination'));

        // For ajax
        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('social-media.instagram-posts.json_grid', compact('posts'))->render(),
                'links' => (string)$posts->appends($request->all())->render()
            ], 200);
        }

        // Return view
        return view('social-media.instagram-posts.grid', compact('posts', 'request'));
    }

    private function _getFilteredInstagramPosts(Request $request) {
        // Base query
        $instagramPosts = InstagramPosts::orderBy('posted_at', 'DESC')
            ->join('hash_tags', 'instagram_posts.hashtag_id', '=', 'hash_tags.id')
            ->select(['instagram_posts.*','hash_tags.hashtag']);

        //Ignore google search result from DB
        $instagramPosts->where('source', '!=', 'google');
        
        // Apply hashtag filter
        if (!empty($request->hashtag)) {
            $instagramPosts->where('hash_tags.hashtag', str_replace('#', '', $request->hashtag));
        }

        // Apply author filter
        if (!empty($request->author)) {
            $instagramPosts->where('username', 'LIKE', '%' . $request->author . '%');
        }

        // Apply author filter
        if (!empty($request->post)) {
            $instagramPosts->where('caption', 'LIKE', '%' . $request->post . '%');
        }

        // Return instagram posts
        return $instagramPosts;
    }

     /**
     * @SWG\Post(
     *   path="/instagram/post",
     *   tags={"Instagram"},
     *   summary="post instagram",
     *   operationId="post-instagram",
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
    public function apiPost(Request $request)
    {
        // Get raw body
        $file = $request->file('file');

        $f = File::get($file);

        $payLoad = json_decode($f);

        // NULL? No valid JSON
        if ($payLoad == null) {
            return response()->json([
                'error' => 'Invalid json'
            ], 400);
        }

        // Process input
        if (is_array($payLoad) && count($payLoad) > 0) {
            $payLoad = json_decode(json_encode($payLoad), true);

            // Loop over posts
            foreach ($payLoad as $postJson) {

                if(isset($postJson['Followers'])){
                    
                    $inf = ScrapInfluencer::where('name',$postJson['Owner'])->first();
                    if($inf == null){
                        $influencer = new ScrapInfluencer;
                        $influencer->name = $postJson['Owner'];
                        $influencer->url = $postJson['URL'];
                        $influencer->followers = $postJson['Followers'];
                        $influencer->following = $postJson['Following'];
                        $influencer->posts = $postJson['Posts'];
                        $influencer->description = $postJson['Bio'];
                        if(isset($postJson['keyword'])){
                            $influencer->keyword = $postJson['keyword'];
                        }
                        $influencer->save();
                    }
                }else{
                        // Set tag
                    $tag = $postJson[ 'Tag used to search' ];

                    // Get hashtag ID
                    $hashtag = HashTag::firstOrCreate(['hashtag' => $tag]);
                    $hashtag->is_processed = 1;
                    $hashtag->save();

                    // Retrieve instagram post or initiate new
                    $instagramPost = InstagramPosts::firstOrNew(['location' => $postJson[ 'URL' ]]);
                    $instagramPost->hashtag_id = $hashtag->id;
                    $instagramPost->username = $postJson[ 'Owner' ];
                    $instagramPost->caption = $postJson[ 'Original Post' ];
                    $instagramPost->posted_at = date('Y-m-d H:i:s', strtotime($postJson[ 'Time of Post' ]));
                    $instagramPost->media_type = !empty($postJson[ 'Image' ]) ? 'image' : 'other';
                    $instagramPost->media_url = !empty($postJson[ 'Image' ]) ? $postJson[ 'Image' ] : $postJson[ 'URL' ];
                    $instagramPost->source = 'instagram';
                    $instagramPost->save();

                    // Store media
                    if (!empty($postJson[ 'Image' ])) {
                        if (!$instagramPost->hasMedia('instagram-post')) {
                            $media = MediaUploader::fromSource($postJson[ 'Image' ])
                                ->toDisk('uploads')
                                ->toDirectory('social-media/instagram-posts/' . floor($instagramPost->id / 1000))
                                ->useFilename($instagramPost->id)
                                ->beforeSave(function (\Plank\Mediable\Media $model, $source) {
                                    $model->setAttribute('extension', 'jpg');
                                })
                                ->upload();
                            $instagramPost->attachMedia($media, 'instagram-post');
                        }
                    }

                    // Comments
                    if (isset($postJson[ 'Comments' ]) && is_array($postJson[ 'Comments' ])) {
                        // Loop over comments
                        foreach ($postJson[ 'Comments' ] as $comment) {
                            // Check if there really is a comment
                            if (isset($comment[ 'Comments' ][ 0 ])) {
                                // Set hash
                                $commentHash = md5($comment[ 'Owner' ] . $comment[ 'Comments' ][ 0 ] . $comment[ 'Time' ]);

                                $instagramPostsComment = InstagramPostsComments::firstOrNew(['comment_id' => $commentHash]);
                                $instagramPostsComment->instagram_post_id = $instagramPost->id;
                                $instagramPostsComment->comment_id = $commentHash;
                                $instagramPostsComment->username = $comment[ 'Owner' ];
                                $instagramPostsComment->comment = $comment[ 'Comments' ][ 0 ];
                                $instagramPostsComment->posted_at = date('Y-m-d H:i:s', strtotime($comment[ 'Time' ]));
                                $instagramPostsComment->save();
                            }
                        }
                    } 
                }
                
            }
        }

        // Return
        return response()->json([
            'ok'
        ], 200);
    }

    /**
     * @SWG\Get(
     *   path="/instagram/send-account/{token}",
     *   tags={"Instagram"},
     *   summary="get instagram account details",
     *   operationId="get-instagram-account-details",
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
    public function sendAccount($token)
    {
      if($token != 'sdcsds'){
        return response()->json(['message' => 'Invalid Token'], 400);
      }
      $account = Account::where('platform','instagram')->where('comment_pending',1)->first();

     return response()->json(['username' => $account->last_name , 'password' => $account->password], 200);
    }

    /**
     * @SWG\Get(
     *   path="/instagram/get-comments-list/{username}",
     *   tags={"Instagram"},
     *   summary="get instagram comments list",
     *   operationId="get-instagram-comment-list",
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
    public function getComments($username)
    {
        $account = Account::where('last_name',$username)->first();

        if($account == null && $account == ''){
             return response()->json(['result' =>  false,'message' => 'Account Not Found'], 400);
        }

        $comments = InstagramCommentQueue::select('id','post_id','message')->where('account_id',$account->id)->where('is_send',0)->take(20)->get();
        if(count($comments) != 0){
            return response()->json(['result' => true , 'comments' => $comments],200); 
        }else{
            return response()->json(['result' =>  false, 'message' => 'No messages'],200); 
        }
               

    }

    /**
     * @SWG\Post(
     *   path="/instagram/comment-sent",
     *   tags={"Instagram"},
     *   summary="send instagram comments",
     *   operationId="send-instagram-comment",
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
    public function commentSent(Request $request)
    {
        $id = $request->id;
        $comment = InstagramCommentQueue::find($id);
        $comment->is_send = 1;
        $comment->save();

    }    

    /**
     * @SWG\Get(
     *   path="/instagram/get-hashtag-list",
     *   tags={"Instagram"},
     *   summary="Get instagram hashtag list",
     *   operationId="get-instagram-hashtag-list",
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
    public function getHashtagList()
    {
        $hastags = HashTag::select('id','hashtag')->where('is_processed',0)->first();

        if(!$hastags){
            $hastags = HashTag::select('id','hashtag')->where('is_processed',2)->first();
        }
        
        if(!$hastags){
            return response()->json(['hastag' => ''],200);
        }

        return response()->json(['hastag' => $hastags ],200);

    }

    /**
    * @SWG\Post(
    *   path="/local/instagram-post",
    *   tags={"Local"},
    *   summary="Save Local instagram post",
    *   operationId="save-local-instagram-post",
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
    public function saveFromLocal(Request $request)
    {
        // Get raw JSON
        $receivedJson = json_decode($request->getContent());
        
        //Saving post details 
        if(isset($receivedJson->post)){
            
            $checkIfExist = InstagramPosts::where('post_id', $receivedJson->post->post_id)->first();

            if(empty($checkIfExist)){
                $media             = new InstagramPosts();
                $media->post_id    = $receivedJson->post->post_id;
                $media->caption    = $receivedJson->post->caption;
                $media->user_id    = $receivedJson->post->user_id;
                $media->username   = $receivedJson->post->username;
                $media->media_type = $receivedJson->post->media_type;
                $media->code       = $receivedJson->post->code;
                $media->location   = $receivedJson->post->location;
                $media->hashtag_id = $receivedJson->post->hashtag_id;
                $media->likes = $receivedJson->post->likes;
                $media->comments_count = $receivedJson->post->comments_count;
                $media->media_url = $receivedJson->post->media_url;
                $media->posted_at = $receivedJson->post->posted_at;
                $media->save();

            if($media){
                if(isset($receivedJson->comments)){
                    $comments = $receivedJson->comments;
                        foreach ($comments as $comment) {

                            $commentEntry = InstagramPostsComments::where('comment_id', $comment->comment_id)->where('user_id', $comment->user_id)->first();

                            if (!$commentEntry) {
                                $commentEntry = new InstagramPostsComments();
                            }

                            $commentEntry = new InstagramPostsComments();
                            $commentEntry->user_id = $comment->user_id;
                            $commentEntry->name = $comment->name;
                            $commentEntry->username = $comment->username;
                            $commentEntry->instagram_post_id = $comment->instagram_post_id;
                            $commentEntry->comment_id = $comment->comment_id;
                            $commentEntry->comment = $comment->comment;
                            $commentEntry->profile_pic_url = $comment->profile_pic_url;
                            $commentEntry->posted_at = $comment->posted_at;
                            $commentEntry->save();
                    }        
                        }
                }

            if(isset($receivedJson->userdetials)){    
                $detials = $receivedJson->userdetials;
                $userList = InstagramUsersList::where('user_id',$detials->user_id)->first();
                if(empty($userList)){
                    $user = new InstagramUsersList;
                    $user->username = $detials->username;
                    $user->user_id = $detials->user_id;
                    $user->image_url = $detials->image_url;
                    $user->bio = $detials->bio;
                    $user->rating = 0;
                    $user->location_id = 0;
                    $user->because_of = $detials->because_of;
                    $user->posts = $detials->posts;
                    $user->followers = $detials->followers;
                    $user->following = $detials->following;
                    $user->location = $detials->location;
                    $user->save();
                }else{
                    if($userList->posts == ''){
                        $userList->posts = $detials->posts;
                        $userList->followers = $detials->followers;
                        $userList->following = $detials->following;
                        $userList->location = $detials->location;
                        $userList->save();
                    }
                }        
            } 


          }     

            
        }
    }

    public function viewPost(Request $request)
    {
        $accounts = Account::where('platform','instagram')->whereNotNull('proxy')->get();

        $data = Post::whereNotNull('id')->paginate(10);
        
        return view('instagram.post.index', compact(
            'accounts',
            'data'
        ));
    }


    public function users(Request $request)
    {
        $users = \App\InstagramUsersList::whereNotNull('username')->where('is_manual',1)->orderBy('id','desc')->paginate(25);
        return view('instagram.users',compact('users'));
    }

    /**
    * @SWG\Get(
    *   path="/local/instagram-user-post",
    *   tags={"Local"},
    *   summary="Get Local instagram user post",
    *   operationId="get-local-instagram-user-post",
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
    public function getUserForLocal()
    {
        $users = \App\InstagramUsersList::select('id','user_id')->whereNotNull('username')->where('is_manual',1)->where('is_processed',0)->orderBy('id','desc')->first();
        return json_encode($users);
        
    }

    public function userPost($id)
    {
        $user  = InstagramUsersList::find($id);

        $ig = new \InstagramAPI\Instagram();

        try {
            $ig->login('satyam_t', 'Schoolrocks93');
        } catch (\Exception $e) {
            $msg = 'Instagram login failed: '.$e->getMessage();
            return response()->json(['message' => $msg, 'code' => 413],413);
        }
        try {
            $user_id = $ig->people->getUserIdForName($user->username);
        } catch (\Exception $e) {
            $msg = 'Something went wrong: '.$e->getMessage();
            return response()->json(['message' => $msg, 'code' => 413],413);
        }

        try {
            $feed = $ig->timeline->getUserFeed($user_id);
        } catch (\Exception $e) {
            $msg = 'Something went wrong: '.$e->getMessage();
            return response()->json(['message' => $msg, 'code' => 413],413);
        }

        $medias = $feed->asArray();
        $medias = $medias['items'];
        
        $count = 0;
        foreach ($medias as $media) {
            if($count == 200){
                break;
            }
            $postId = $media['id'];
            $caption = $media['caption']['text'];
            $user_id = $user_id;
            $mediaDetail = [];
            if ($media['media_type'] === 1) {
                $mediaDetail[] = [
                    'media_type' => 1,
                    'url' => $media['image_versions2']['candidates'][1]['url']
                ];
            } else if ($media['media_type'] === 2) {
                $mediaDetail[] = [
                    'media_type' => 2,
                    'url' => $media['video_versions'][0]['url']
                ];
            } else if ($media['media_type'] === 8) {
                $crousal = $media['carousel_media'];
                $mediaDetail = [];
                foreach ($crousal as $cro) {
                    if ($cro['media_type'] === 1) {
                        $mediaDetail[] = [
                            'media_type' => 1,
                            'url' => $cro['image_versions2']['candidates'][0]['url']
                        ];
                    } else if ($cro['media_type'] === 2) {
                        $mediaDetail[] = [
                            'media_type' => 2,
                            'url' => $cro['video_versions'][0]['url']
                        ];
                    }
                }
            }
            $mediaType = $media['media_type'];
            $comment_count = $media['comment_count'];
            $likes = $media['like_count'];
            $code = $media['code'];
            
            if(!$caption) {
                $caption = '';
            }


            $media             = new InstagramPosts();
            $media->post_id    = $postId;
            $media->caption    = $caption;
            $media->user_id    = $user_id;
            $media->username   = $user->username;
            $media->media_type = $mediaType;
            $media->code       = $code;
            $media->location   = '';
            $media->hashtag_id = 0;
            $media->likes = $likes;
            $media->comments_count = $comment_count;

            if (!is_array($mediaDetail)) {
                $mediaDetail = [$mediaDetail];
            }
            
            $media->media_url = json_encode($mediaDetail);
            $media->posted_at = now();
            $media->save();
            $mediaId = $media->id;

            foreach ($mediaDetail as $mediaFile) {
                $file = @file_get_contents($mediaFile['url']);
                if (!empty($file)) {
                    $mediaFileUpload = MediaUploader::fromString($file)
                        ->toDirectory('instagram')
                        ->useFilename(md5(date("Y-m-d H:i:s")))
                        ->upload();
                    $media->attachMedia($mediaFileUpload, 'instagram');
                    $imagesSave = true;
                }
            }

            $postData = $media->toArray();


            $comments = $ig->media->getComments($postId)->asArray();
                    
            if(isset($comments['comments'])){
                foreach ($comments['comments'] as $comment) {
                    $commentEntry = InstagramPostsComments::where('comment_id', $comment['pk'])->where('user_id', $comment['user']['pk'])->first();

                    if (!$commentEntry) {
                        $commentEntry = new InstagramPostsComments();
                    }

                    $commentEntry->user_id = $comment['user']['pk'];
                    $commentEntry->name = $comment['user']['full_name'];
                    $commentEntry->username = $comment['user']['username'];
                    $commentEntry->instagram_post_id = $mediaId;
                    $commentEntry->comment_id = $comment['pk'];
                    $commentEntry->comment = $comment['text'];
                    $commentEntry->profile_pic_url = $comment['user']['profile_pic_url'];
                    $commentEntry->posted_at = Carbon::createFromTimestamp($comment['created_at'])->toDateTimeString();
                    $commentEntry->save();
               
                }
            }
            
            sleep(5);
            $count++;
        }
        return redirect()->to('/instagram/users/'.$user->user_id);
        
    }

    public function resizeToRatio()
    {
        
    }

    public  function resize_image_crop($image,$width,$height) {
        
        $newImage = $image;
        $type = $image->mime_type;
        
        if($type == 'image/jpeg'){
            $src_img = imagecreatefromjpeg($image->getAbsolutePath());    
        }elseif($type == 'image/png'){
            $src_img = imagecreatefrompng($image->getAbsolutePath());
        }elseif ($type == 'image/gif') {
            $src_img = imagecreatefromgif($image->getAbsolutePath());
        }
        
        $image = $src_img;
        $w = imagesx($image); //current width
        
        $h = @imagesy($image); //current height
        
        if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image could not be resized because it was not a valid image.'; return false; }
        if (($w == $width) && ($h == $height)) { return $image; } //no resizing needed

        //try max width first...
        $ratio = $width / $w;
        $new_w = $width;
        $new_h = $h * $ratio;

        //if that created an image smaller than what we wanted, try the other way
        if ($new_h < $height) {
            $ratio = $height / $h;
            $new_h = $height;
            $new_w = $w * $ratio;
        }

        $image2 = imagecreatetruecolor ($new_w, $new_h);
        imagecopyresampled($image2,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);

        //check to see if cropping needs to happen

        $image3 = imagecreatetruecolor($width, $height);
        if ($new_h > $height) { //crop vertically
            $extra = $new_h - $height;
            $x = 0; //source x
            $y = round($extra / 2); //source y
            imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
        }
        else {
            $extra = $new_w - $width;
            $x = round($extra / 2); //source x
            $y = 0; //source y
            imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
        }
        imagedestroy($image2);
        imagejpeg($image3,$newImage->getAbsolutePath());
        return $image3;
     

    }

    public function hashtag(Request $request, $word)
    {

        if($word)
        {
            $response = $this->getHastagifyApiToken();

            

            if($response)
            {
                $json = $this->getHashTashSuggestions($response, $word);

                $arr = json_decode($json, true);

                $instaTags = [];
                if(isset($arr['code']) && $arr['code']=='404')
                {
                    //handle for error
                }else{
                    foreach ($arr as $tag) {
                       $instaTagData['name'] = $tag['name'];
                       $instaTagData['variants'] = [];
                       foreach ($tag['variants'] as $variant) {
                            $instaTagData['variants'][] = $variant[0];
                       }

                       $instaTagData['influencers'] = [];

                       foreach ($tag['top_influencers'] as $influencer) {
                            $instaTagData['influencers'][] = $influencer[0];
                       }

                       $instaTagData['popular'] = $tag['popularity'];

                       $instaTags[] = $instaTagData;
                    }
                }
                return response()->json(['response' => true , 'results' => $instaTags],200); 
            }
        }
    }


    public function getHastagifyApiToken()
    {
        
            $token = \Session()->get('hastagify');
            if($token){
                return $token;
            }else{
            
            $consumerKey = env('HASTAGIFY_CONSUMER_KEY');
            $consumerSecret = env('HASTAGIFY_CONSUMER_SECRET');
            \Log::error(" hashtagify credentials: " . $consumerKey.', '.$consumerSecret);
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.hashtagify.me/oauth/token",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=".$consumerKey."&client_secret=".$consumerSecret,
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            \Log::error(" hashtagify response " .$response);
            \Log::error(" hashtagify error" .$err);
            curl_close($curl);

            if ($err) {
                return false;
            } else {
                $response = json_decode($response);
                // dd($response);
                \Session()->put('hastagify', $response->access_token);
                return $response->access_token;          
            } 
        }
    }


    public function getHashTashSuggestions($token, $word)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.hashtagify.me/1.0/tag/".$word,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ".$token,
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          //echo "cURL Error #:" . $err;
        } else {
          return  $response;
        }
    }

    public function updateHashtagPost(Request $request)
    {
        try {
            $post_id = $request->get('post_id');
            $updateArr = [];


            if($request->get('account_id'))
            {
                $updateArr['account_id'] = $request->get('account_id');
            }
            if($request->get('comment'))
            {
                $updateArr['comment'] = $request->get('comment');
            }
            if($request->get('post_hashtags'))
            {
                $updateArr['hashtags'] = $request->get('post_hashtags');
            }
            if($request->get('type'))
            {
                $updateArr['type'] = $request->get('type');
            }
            Post::where('id', $post_id)->update($updateArr);
            $this->createPostLog($post_id,"success","Data Updated Succesfully");
            echo json_encode(array("message"=>"Data Updated Succesfully"));
        } catch (\Exception $e) {
            //throw $th;
            $this->createPostLog($post_id,"error",$e->getMessage());
            return response()->json(["message"=>"error while saving data"],500);
        }
       
    }

    public function getImages( Request $request )
    {   
        if( $request->type == 'user' ){

            $number = rand(1,500);
            $response = UnsplashSearch::users( $request->keyword ,['page' => $number]);
            $content =  $response->getContents();
            $lists = json_decode($content);
            $images = [];
            foreach ($lists->results as $list) {
                $images[] = $list->urls->full;
            }
            return $images ? $images : null;
        }else if( $request->type == 'collection' ){

            $number = rand(1,500);
            $response = UnsplashSearch::collections( $request->keyword ,['page' => $number]);
            $content =  $response->getContents();
            $lists = json_decode($content);
            
            $images = [];
            foreach ($lists->results as $list) {
                $images[] = $list->cover_photo->urls->full;
            }
            return $images ? $images : null;

        }else {
            $number = rand(1,500);
            $response = UnsplashSearch::photos( $request->keyword ,['page' => $number, 'order_by' => 'latest']);
            $content =  $response->getContents();
            $lists = json_decode($content);
            
            $images = [];
            foreach ($lists->results as $list) {
                $images[] = $list->urls->full;
            }
            return $images ? $images : null;
        }


    }

    public function getCaptions()
    {
        $captionArray = [];
        
        $captions = \App\Caption::all();

        foreach ($captions as $caption) {
            $captionArray[] = ['id' => $caption->id, 'caption' => $caption->caption];
            
               
        }

        return $captionArray;

    }

    public function postMultiple(Request $request)
    {   
        
        try {
            if($request->account_id){

                $account = \App\Account::find($request->account_id); 
    
                $images = $request->imageURI;
                $captions = $request->captions;
                for ($i=0; $i < count($request->captions); $i++) { 
                    $media = [];
                    $imageURL = $images[$i];
                    $captionId = $captions[$i];
                    $caption = \App\Caption::find($captionId);
    
                    $file = @file_get_contents($imageURL);
                    $savedMedia =   MediaUploader::fromString($file)
                    ->useFilename(uniqid(true))
                    ->toDirectory('instagram-media')
                    ->upload();
                    $account->attachMedia($savedMedia, 'instagram-profile');
    
                    //getting media id 
                    $lastMedia = $account->lastMedia('instagram-profile');
    
                    $media[] = $lastMedia->id;
                    
                    $post = new Post();
    
                    $post->account_id = $account->id;
                    $post->type       = 'post';
                    $post->caption    = isset($caption->caption) ? $caption->caption : "";

                    $post->ig         = json_encode([
                        'media'    => $media,
                        'location' => '',
                    ]);

                    $post->save();
                    $newPost = Post::find($post->id);
                    $media   = json_decode($newPost->ig,true);
                    $ig      = [
                        'media'    => $media['media'],
                        'location' => '',
                    ];

                    $newPost->ig = $ig;

                    $mediaFile = Media::where('id',$lastMedia->id)->first();
                    $image = self::resize_image_crop($mediaFile,640,640);
                        
    
                    if (new PublishPost($newPost)) {
                        sleep(10); 
                    } else {
                        sleep(30);
                    }
                }
                $this->instagramLog($request->account_id,"success","Post Added Succesfully");
                return response()->json(['Post Added Succesfully'], 200);
            }else{
                $this->instagramLog($request->account_id,"error","account id missing");
                return response()->json(['error'], 500);
            }
        } catch (\Exception $e) {
            $this->instagramLog($request->account_id,"error",$e->getMessage());
            return response()->json([$e->getMessage()], 500);
        }
    }

    public function likeUserPost(Request $request)
    {
      //  set_time_limit(300);
      try {
            if($request->account_id){
                $account = \App\Account::find($request->account_id); 
                $instagram = new Instagram();
                $instagram->login($account->last_name, $account->password);
                $getdatas=$instagram->people->getSelfFollowing($instagram->uuid);
                $decode= json_decode($getdatas);
                //return response()->json([$decode], 200);
                $count = 0;
                $lastCount = rand(5,10);
                $likedUserNameList = [];
                $likePostCount = 0;
                foreach ($decode->users as $value) {
                    if($count == $lastCount){
                        break;
                    }
                    $getdata=$instagram->timeline->getUserFeed($value->pk);
                    $decode_data= json_decode($getdata);
                    $likePostCount = 0;
                    $likePostCountLast = rand(5,10);

                    foreach ($decode_data->items as $data) {
                        if($likePostCount == $likePostCountLast){
                            break;
                        }
                        sleep(rand(5,10));
                        $getdatass=$instagram->media->like($data->id,'0');
                        $likePostCount++;
                    }
                    $count++;
                }

                $this->instagramLog($request->account_id,"success","Liked Post : ".$likePostCount);
                return response()->json(['Liked User Post Successfully'], 200);
            }else{
                $this->instagramLog($request->account_id,"error","account id missing");
                return response()->json(['error'], 500);
            }
        } catch (\Exception $e) {
            $this->instagramLog($request->account_id,"error",$e->getMessage());
            return response()->json([$e->getMessage()], 500);
        }
    }

    public function acceptRequest(Request $request)
    {
        try {
            if($request->account_id){
                    $account = \App\Account::find($request->account_id); 
                    $instagram = new Instagram();
                    $instagram->login($account->last_name, $account->password);
                    $getdatas=$instagram->people->getPendingFriendships();
                            
                        $decode= json_decode($getdatas);
                        $count = 0;
                        $lastCount = rand(5,10);
                        foreach($decode->users as $getdata){
                            if($count == $lastCount){
                                break;
                            }
                            sleep(rand(5,10));
                            $getdata=$instagram->people->approveFriendship($getdata->pk);
                            $count++;
                        }
                    $this->instagramLog($request->account_id,"success","Total request accepted : ".$count);
                    return response()->json(['All request accepted Successfully'], 200);

            }else{
                $this->instagramLog($request->account_id,"error","account id missing");
                return response()->json(['error'], 500);
            }
        } catch (\Exception $e) {
            $this->instagramLog($request->account_id,"error",$e->getMessage());
            return response()->json([$e->getMessage()], 500);
        }
    }

    public function sendRequest(Request $request)
    {
        try {
            if($request->account_id){
                $account = \App\Account::find($request->account_id); 
                $instagram = new Instagram();
                $instagram->login($account->last_name, $account->password);
                $pk = $instagram->people->getUserIdForName($account->last_name);
                $var =$instagram->people->getSuggestedUsers($pk);
                $data= json_decode($var);
                $count = 0;
                $lastCount = rand(5,10);
                foreach($data->users as $user){
                    if($count == $lastCount){
                            break;
                    }
                  sleep(rand(10,30));
                    $instagram->people->follow($user->pk);
                    $count++;
                }
                $this->instagramLog($request->account_id,"success","Total send request : ".$count);
                return response()->json(['Post Added Succesfully'], 200);

            }else{
                $this->instagramLog($request->account_id,"error","account id missing");
                return response()->json(['error'], 500);
            }
        } catch (\Exception $e) {
            $this->instagramLog($request->account_id,"error",$e->getMessage());
            return response()->json([$e->getMessage()], 500);
        }
    }

    public function instagramLog($account_id,$title,$description){
            $InstagramLog = new InstagramLog();
            $InstagramLog->account_id = $account_id;
            $InstagramLog->log_title = $title;
            $InstagramLog->log_description = $description;
            $InstagramLog->save();
            return true;
    }


    public function createPostLog($postId=null,$title=null,$message=null)
    {
        $InstagramPostLog = new InstagramPostLog();
        $InstagramPostLog->post_id = $postId;
        $InstagramPostLog->log_title = $title; 
        $InstagramPostLog->log_description = $message;
        $InstagramPostLog->save();
        return true;
    }

    public function history(Request $request)
    {   
        
    	$productCategory = InstagramLog::where("account_id", $request->account_id)->orderBy("created_at","desc")->get();
        return response()->json(["code" => 200 , "data" => $productCategory]);
    }

}
