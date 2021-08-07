<?php

namespace App\Http\Controllers;

use App\Account;
use App\AdsSchedules;
use App\Brand;
use App\Category;
use App\Customer;
use App\HashTag;
use App\HashtagPostHistory;
use App\Image;
use App\ImageSchedule;
use App\Influencers;
use App\InfluencersDM;
use App\InstagramAutomatedMessages;
use App\Product;
use App\ScheduleGroup;
use App\Services\Instagram\DirectMessage;
use App\Services\Instagram\Instagram;
use App\TargetLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\Facebook\Facebook;
use App\Priority;
use App\Library\Instagram\Helper;
use App\InstagramUsersList;

class InstagramController extends Controller
{
    private $instagram;
    private $facebook;
    private $messages;

    /**
     * InstagramController constructor.
     * @param Instagram $instagram
     * @param Facebook $facebook
     * @param DirectMessage $messages
     */
    public function __construct(Instagram $instagram, Facebook $facebook, DirectMessage $messages)
    {
        $this->instagram = $instagram;
        $this->facebook = $facebook;
        $this->messages = $messages;
    }

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     *  Simply returns the count of automated messages, accounts, total influencers and other details as given in below variables
     */
    public function index() {
        $accounts = Account::where('platform', 'instagram')->get()->count();
        $automatedMessages = InstagramAutomatedMessages::get()->count();
        $commentsToday = HashtagPostHistory::where('type', 'comment')->where('post_date', date('Y-m-d'))->count();
        $commentsTotal = HashtagPostHistory::where('type', 'comment')->count();
        $influencersTotal = Influencers::get()->count();
        $date = date('Y-m-d');
        $infDmToday = InfluencersDM::where('created_at', 'LIKE', "%$date%")->get()->count();
        $infDm = InfluencersDM::get()->count();
        return view('instagram.dashboard', compact('accounts', 'automatedMessages', 'commentsToday', 'commentsTotal', 'influencersTotal', 'infDmToday', 'infDm'));
    }


    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * This method gives the list of posts
     * that is in Instagram account
     */
    public function showPosts(Request $request) {
        $url = null;

        if ($request->has('next') && !empty($request->get('next'))) {
            $url = substr($request->get('next'), 32);
        } else if ($request->has('previous') && !empty($request->get('previous'))) {
            $url = substr($request->get('previous'), 32);
        }

        [$posts, $paging] = $this->instagram->getMedia($url);

        return view('instagram.index', compact(
            'posts',
            'paging'
        ));
    }

    /**
     * @param Request $request
     * This method will store photo to
     * Instagram Business account
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
        ]);

        $account = new Account();
        $account->first_name = $request->get('first_name');
        $account->last_name = $request->get('last_name');
        $account->password = $request->get('password');
        $account->email = $request->get('email');
        $account->broadcast = $request->get('broadcast') == 'on' ? 1 : 0;
        $account->manual_comment = $request->get('manual_comments') == 'on' ? 1 : 0;
        $account->bulk_comment = $request->get('bulk_comments') == 'on' ? 1 : 0;
        $account->dob = '1996-02-02';
        $account->platform = 'instagram';
        $account->gender = $request->get('gender');
        $account->country = $request->get('country');
        $account->save();

        return redirect()->back()->with('message', 'Account added successfully!');

    }

    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * SHow account data
     */
    public function edit($id) {
        $account = Account::findOrFail($id);
        $countries = TargetLocation::all();

        return view('instagram.am.edit-account', compact('account', 'countries'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Delete an account by ID, this will come from Instagram module
     */
    public function deleteAccount($id) {
        $account = Account::findOrFail($id);

        if ($account){
            $account->delete();
        }

        return redirect()->back()->with('success', 'Account deleted successfully!');

    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * Update the account details and status like first name, email, manual_comment, etc
     */
    public function update($id, Request $request) {
        $this->validate($request, [
            'last_name' => 'required',
            'password' => 'required',
        ]);

        $account = Account::findOrFail($id);
        $account->first_name = $request->get('first_name');
        $account->last_name = $request->get('last_name');
        $account->password = $request->get('password');
        $account->email = $request->get('email');
        $account->broadcast = $request->get('broadcast') == 'on' ? 1 : 0;
        $account->manual_comment = $request->get('manual_comments') == 'on' ? 1 : 0;
        $account->bulk_comment = $request->get('bulk_comments') == 'on' ? 1 : 0;
        $account->blocked = $request->get('blocked') == 'on' ? 1 : 0;
        $account->country = $request->get('country');
        $account->save();

        return redirect()->back()->with('message', 'Account added successfully!');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * get Instagram acomments for the post ID...
     */
    public function getComments(Request $request) {
        $this->validate($request, [
            'post_id' => 'required'
        ]);

        $comments = $this->instagram->getComments($request->get('post_id'));

        return response()->json($comments);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Facebook\Exceptions\FacebookSDKException
     * Post the comment to given post ID
     */
    public function postComment(Request $request) {
        $this->validate($request, [
            'message' => 'required',
            'post_id' => 'required'
        ]);

        if ($request->has('comment_id') && !empty($request->get('comment_id'))) {
            $commentId = $request->get('comment_id');
            $comment = $this->instagram->postReply($commentId, $request->get('message'));
            return response()->json($comment);
        }

        $comment = $this->instagram->postComment($request->get('post_id'), $request->get('message'));
        return response()->json($comment);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * This is the list of images to be posted
     */
    public function showImagesToBePosted(Request $request) {
        $images = Image::where('status', 2);

        $selected_categories = 1;
        $selected_brands = [];
        $price = [0, 10000000];

        if ($request->has('category')) {
            $selected_categories = $request->get('category');
            $categories = Category::whereIn('id', $selected_categories)->with('childs')->get();
        }

        if ($request->has('price')) {
            $price = $request->get('price');
            $price = explode(',', $price);
            $images = $images->whereBetween('price', $price);
        }

        if ($request->has('brand')) {
            $selected_brands = $request->get('brand');
            $images = $images->whereIn('brand', $selected_brands);
        }

        $images = $images->orderBy('created_at', 'DESC')->paginate(25);

        $category_selection = Category::attr(['name' => 'category[]','class' => 'form-control'])
            ->selected($selected_categories)
            ->renderAsDropdown();


        $brands = Brand::all();

        return view('instagram.images_to_be_posted', compact('images', 'categories', 'brands', 'category_selection', 'selected_brands', 'price'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Posts the media to respective platforms, image_id is passed which exists in images table
     */
    public function postMedia(Request $request) {
        $this->validate($request, [
            'image_id' => 'required|exists:images,id',
        ]);

        if ($request->get('is_scheduled') === 'on') {
            $this->validate($request, [
                'date' => 'required|date',
                'hour' => 'required|numeric|min:0|max:23',
                'minute' => 'required|numeric|min:0|max:59',
            ]);

            $date = explode('-', $request->get('date'));
            $date = Carbon::create($date[0], $date[1], $date[2], $request->get('hour'), $request->get('minute'), 0);
            $date = $date->toDateTimeString();

            $image = Image::findOrFail($request->get('image_id'));
            $image->is_scheduled = 1;
            $image->save();


            $schedule = new ImageSchedule();
            $schedule->image_id = $request->get('image_id');
            $schedule->facebook = ($request->get('facebook') === 'on') ? 1 : 0;
            $schedule->instagram = ($request->get('instagram') === 'on') ? 1 : 0;
            $schedule->description = $request->get('description');
            $schedule->scheduled_for = $date;
            $schedule->status = 0;
            $schedule->save();

            $scheduleGroup = new ScheduleGroup();
            $scheduleGroup->images = [$request->get('image_id')];
            $scheduleGroup->scheduled_for = $date;
            $scheduleGroup->description = $request->get('description');
            $scheduleGroup->status = 1;
            $scheduleGroup->save();

            return response()->json([
                'status' => 'success',
                'post_status' => $schedule->status,
                'time' => $schedule->scheduled_for->diffForHumans(),
                'posted_to' => [
                    'facebook' => $schedule->facebook,
                    'instagram' => $schedule->instagram
                ],
                'message' => 'This post has been scheduled for post.'
            ]);

        }

        $image = Image::findOrFail($request->get('image_id'));
        $image->is_scheduled = 1;
        $image->save();

        $schedule = new ImageSchedule();
        $schedule->image_id = $request->get('image_id');
        $schedule->facebook = ($request->get('facebook') === 'on') ? 1 : 0;
        $schedule->instagram = ($request->get('instagram') === 'on') ? 1 : 0;
        $schedule->description = $request->get('description');
        $schedule->scheduled_for = date('Y-m-d');
        $schedule->status = 0;
        $schedule->save();

        if ($request->get('facebook') === 'on') {
            $this->facebook->postMedia($image, $request->get('description'));
            ImageSchedule::whereIn('image_id', $this->facebook->getImageIds())->update([
                'status' => 1
            ]);
        }

        if ($request->get('instagram') === 'on') {
            $this->instagram->postMedia($image, $request->get('description'));
            ImageSchedule::whereIn('image_id', $this->instagram->getImageIds())->update([
                'status' => 1
            ]);
        }

        return response()->json([
            'status' => 'success',
            'post_status' => $schedule->status,
            'time' => $schedule->scheduled_for->diffForHumans(),
            'message' => 'This post has been scheduled for post.'
        ]);

    }

    /**
     * @param $schedule
     * @return \Illuminate\Http\JsonResponse
     * Whenever you need to post the media which has been scheduled, we post using this method
     */
    public function postMediaNow($schedule)
    {
        $schedule = ScheduleGroup::findOrFail($schedule);
        $images = $schedule->images->get()->all();


        if ($images[0]->schedule->facebook) {
            $this->facebook->postMedia($images, $schedule->description);
            ImageSchedule::whereIn('image_id', $this->facebook->getImageIds())->update([
                'status' => 1,
                'scheduled_for' => date('Y-m-d h:i:00')
            ]);
        }
        if ($images[0]->schedule->instagram) {
            $this->instagram->postMedia($images);
            ImageSchedule::whereIn('image_id', $this->instagram->getImageIds())->update([
                'status' => 1,
                'scheduled_for' => date('Y-m-d h:i:00')
            ]);
        }

        $schedule->status = 2;
        $schedule->scheduled_for = date('Y-m-d h:i:00');
        $schedule->save();

        return response()->json([
            'status' => 'success',
            'post_status' => $schedule->status,
            'time' => $schedule->scheduled_for->diffForHumans(),
            'message' => 'This schedule has been posted successfully!.'
        ]);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * Show the images which are scheduled for posting
     */
    public function showSchedules(Request $request) {
        $imagesWithoutSchedules = Image::whereDoesntHave('schedule')->where('status', 2)->orderBy('created_at', 'DESC')->get();
        $imagesWithSchedules = ScheduleGroup::where('status', '!=', 2)->get();
        $postedImages = Image::whereHas('schedule', function($query) {
            $query->where('status', 1);
        })->orderBy('created_at', 'DESC')->get();

        return view('instagram.schedules', compact('imagesWithoutSchedules', 'imagesWithSchedules', 'postedImages'));

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * All the scheduled images which has not been posted yet
     */
    public function getScheduledEvents() {
        $imagesWithSchedules = ScheduleGroup::where('status', 1)->get()->toArray();
        $imagesWithSchedules = array_map(function($item) {
            return [
                'id' => $item['id'],
                'title' => substr($item['description'], 0, 500).'...',
                'start' => $item['scheduled_for'],
                'image_names' => array_map(function ($img) {
                    return [
                        'id' => $img['id'],
                        'name' => $img['filename'] ? asset('uploads/social-media') . '/' . $img['filename'] : 'http://lorempixel.com/555/300/black',
                    ];
                }, $item['images']->get(['id', 'filename'])->toArray())
            ];
        }, $imagesWithSchedules);

        return response()->json($imagesWithSchedules);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * This method will post the schedule
     */
    public function postSchedules(Request $request) {
        $this->validate($request, [
            'description' => 'required',
            'date' => 'required|date',
            'hour' => 'required|numeric|min:0|max:23',
            'minute' => 'required|numeric|min:0|max:59',
        ]);


        $images = $request->get('images') ?? [];
        $descriptions = $request->get('description');
        $date = explode('-', $request->get('date'));
        $date = Carbon::create($date[0], $date[1], $date[2], $request->get('hour'), $request->get('minute'), 0);
        $date = $date->toDateTimeString();

        foreach ($images as $image) {
            $schedule = new ImageSchedule();
            $schedule->image_id = $image;
            $schedule->facebook = ($request->get('facebook') === 'on') ? 1 : 0;
            $schedule->instagram = ($request->get('instagram') === 'on') ? 1 : 0;
            $schedule->description = $descriptions[$image] ?? '';
            $schedule->scheduled_for = $date;
            $schedule->status = 0;
            $schedule->save();
        }

        $scheduleGroup = new ScheduleGroup();
        $scheduleGroup->images = $images;
        $scheduleGroup->description = $request->get('caption') ?? '';
        $scheduleGroup->scheduled_for = $date;
        $scheduleGroup->status = 1;
        $scheduleGroup->save();

        if ($request->isXmlHttpRequest()) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        return redirect()->action('InstagramController@editSchedule', $scheduleGroup->id);

    }

    /**
     * @param $schedule
     * @return \Illuminate\Http\JsonResponse
     * Cancel the schedule by simply deleting it
     */
    public function cancelSchedule($schedule)
    {
        $schedule = ScheduleGroup::findOrFail($schedule);

        $images = $schedule->images->get();
        foreach ($images as $image) {
            $image->is_scheduled = 0;
            $image->save();
            $image->schedule()->delete();
        }

        $schedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'This schedule has been deleted successfully!.'
        ]);

    }

    /**
     * @SWG\Get(
     *   path="/messages/{thread}",
     *   tags={"Instagram"},
     *   summary="Instagram get Message Thread",
     *   operationId="instagram-get-thread",
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
    /**
     * @param $thread
     * @return \Illuminate\Http\JsonResponse
     * Get the Instragram message thread..
     */
    public function getThread($thread) {
        $thread = $this->messages->getThread($thread)->asArray();
        $thread = $thread['thread'];
        $currentUserId = $this->messages->getCurrentUserId();
        $threadJson['messages'] = array_map(function($item) use ($currentUserId) {
            $text = '';
            if ($item['item_type'] == 'text') {
                $text = $item['text'];
            } else if ($item['item_type'] == 'like') {
                $text = $item['like'];
            } else if ($item['item_type'] == 'media') {
                $text = $item['media']['image_versions2']['candidates'][0]['url'];
            }
            return [
                'id' => $item['item_id'],
                'text' => $text,
                'item_type' => $item['item_type'],
                'type' => ($item['user_id']===$currentUserId) ? 'sent' : 'received'
            ];
        }, $thread['items']);

        $threadJson['profile_picture'] = $thread['users'][0]['profile_pic_url'];
        $threadJson['username'] = $thread['users'][0]['username'];
        $threadJson['name'] = $thread['users'][0]['full_name'];

        return response()->json($threadJson);
    }

    /**
     * @SWG\Post(
     *   path="/messages/{thread}",
     *   tags={"Instagram"},
     *   summary="Instagram Post Message Thread",
     *   operationId="instagram-post-thread",
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
    
    /**
     * @param $thread
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Reply to the Instagram thread
     */
    public function replyToThread($thread, Request $request)
    {
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $this->messages->sendImage(['thread' => $thread], $file);
        }
        $this->messages->sendMessage(['thread' => $thread], $request->get('message'));
        return $this->getThread($thread);
    }

    /**
     * @param $scheduleId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * Simply view the edit form which can be edited
     */
    public function editSchedule($scheduleId)
    {
        $schedule = ScheduleGroup::find($scheduleId);
        return view('instagram.schedule', compact('schedule'));
    }

    /**
     * @param Request $request
     * @param $scheduleId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     * Attach a media to the schedule ID
     */
    public function attachMedia(Request $request, $scheduleId) {

        $schedule = ScheduleGroup::find($scheduleId);

        if ($request->has('save')) {
            $selectedImages = $request->get('images') ?? [];
            $selectedImages  = Product::whereIn('id', $selectedImages)->get();
            $imagesIds = [];

            // create schedile by looping through the selected images
            foreach ($selectedImages as $selectedImage) {
                $imageUrl = explode('/', $selectedImage->imageurl);
                $image = new Image();
                $image->brand = $selectedImage->brand;
                $image->filename = $imageUrl[count($imageUrl)-1];
                $image->is_scheduled = 1;
                $image->status = 2;
                $image->save();

                $is = new ImageSchedule();
                $is->image_id = $image->id;
                $is->description = 'Auto Scheduled';
                $is->scheduled_for = $schedule->scheduled_for;
                $is->facebook = 1;
                $is->instagram = 0;
                $is->save();

                $imagesIds[] = $image->id;
            }

            $schedule->images = $imagesIds;
            $schedule->save();

            return redirect()->action('InstagramController@editSchedule', $scheduleId);
        }

        $selectedImages = $request->get('images') ?? [];

        $selectedImages  = Product::whereIn('id', $selectedImages)->get();

        $products = Product::whereNotNull('sku')->whereNotIn('id', $selectedImages)->latest()->paginate(40);

        return view('instagram.attach_image', compact('schedule', 'products', 'selectedImages', 'request'));

    }

    /**
     * @param $scheduleId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * Update the schedule
     */
    public function updateSchedule($scheduleId, Request $request)
    {
        $schedule = ScheduleGroup::findOrFail($scheduleId);

        $schedule->status = 0;
        if ($request->get('approval') === 'on') {
            $schedule->status = 1;
        }
        $schedule->description = trim($request->get('description'));

        $images = $request->get('images') ?? [];
        $selectedImages = $request->get('selected_images') ?? [];

        foreach ($images as $image)
        {
            $img = Image::find($image);

            if (!in_array($image, $selectedImages, false)) {
                if ($img && $img->schedule)
                {
                    $img->schedule->delete();
                }
                continue;
            }

            $img->schedule->description = trim($request->get('description_'.$image));
            $img->schedule->save();
        }


        $schedule->images = $selectedImages;
        $schedule->save();


        return redirect()->back()->with('message', 'Schedule updated successfully!');

    }

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * SHow all the hagshtags from hash_tags table
     */
    public function showHahstags() {
        $posts = HashTag::all();

        return view('instagram.hahstags', compact('posts'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * delete comment from a post , with a comment key id
     */
    public function deleteComment(Request $request) {
        $postId = $request->get('post_id');
        $commentKey = $request->get('comment_key');
        $hashtag = HashTag::find($postId);

        if (!$hashtag) {
            return response()->json([
                'status' => 'Not found!'
            ]);
        }

        $comments = $hashtag->comments;
        $filteredComments = [];

        foreach ($comments as $key=>$comment) {
            if ($key == $commentKey) {
                $filteredComments[] = [$comment[0], $comment[1], 0];
                continue;
            }

            $filteredComments[] = [$comment[0], $comment[1], 1];
        }

        $hashtag->comments = $filteredComments;
        $hashtag->save();

        return response()->json([
            'status' => 'Success deleting product!'
        ]);


    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * SHoe the hashtag grid , this will show the posts that we have for related hashtag
     */
    public function hashtagGrid(Request $request) {
        if ($request->has('query')) {
            $hashTag = HashTag::where('hashtag', $request->get('query'))->get();

            $comments = $hashTag;

            $hashtext = $request->get('query');

            return view('instagram.hashtag_grid', compact('comments', 'hashtext', 'request'));
        }

        $hashTags = HashTag::distinct('hashtag')->get();
        $comments = [];
        foreach ($hashTags as $hash) {
            $comments[$hash->hashtag] = HashTag::where('hashtag', $hash->hashtag)->get();
        }

        return view('instagram.hashtag_grid', compact('comments', 'request'));


    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * Show Instagram account
     */
    public function accounts(Request $request) {
        $accounts = Account::where('platform', 'instagram');

        if ($request->get('query') != '') {
            $accounts->where(function($query) use ($request) {
                $q = $request->get('query');
                $query->where('first_name','LIKE', "%$q%")->orWhere('last_name', 'LIKE', "%$q%");
            });
        }

        if ($request->get('filter') == 'broadcast') {
            $accounts = $accounts->where('broadcast', 1);
        }

        if ($request->get('filter') == 'manual_comment') {
            $accounts = $accounts->where('manual_comment', 1);
        }

        if ($request->get('filter') == 'bulk_comment') {
            $accounts = $accounts->where('bulk_comment', 1);
        }

        if ($request->get('blocked') == 'on') {
            $accounts = $accounts->where('blocked', 1);
        }

        $accounts = $accounts->orderBy('id', 'DESC')->get();
        $total = $accounts->count();

        $countries = TargetLocation::all();
        return view('instagram.am.accounts', compact('accounts', 'countries', 'request', 'total'));
    }


    public function priority()
    {
        $priority = Priority::all();
        dd($priority);
    }

    public function addUserForPost(Request $request)
    {
        if (strpos($request->userlink, 'instagram.com') !== false) {
           //Get username from link
            $username = str_replace(['https://www.instagram.com/','/'], '', $request->userlink);

            $reAccount =  $request->get("account", 0);
            $account  = \App\Marketing\InstagramConfig::find($reAccount);
            $usernameI = false;
            $passwordI = false;
            if($account) {
                $usernameI = $account->username;
                $passwordI = $account->password;
            }

            $username = Helper::getUserIdFromUsername($username,$usernameI,$passwordI);
            if($username['status'] == 'ok'){
                $user = $username['user'];
                $userList = InstagramUsersList::where('user_id',$user['pk'])->first();
                    if(empty($userList)){
                        $userDetail = new InstagramUsersList;
                        $userDetail->username = $user['username'];
                        $userDetail->user_id = $user['pk'];
                        $userDetail->image_url = $user['profile_pic_url'];
                        $userDetail->bio = $user['biography'];
                        $userDetail->rating = 0;
                        $userDetail->location_id = 0;
                        $userDetail->because_of = 'instagram_link';
                        $userDetail->posts = $user['media_count'];
                        $userDetail->followers = $user['follower_count'];
                        $userDetail->following = $user['following_count'];
                        $userDetail->is_manual = 1;
                        $userDetail->save();
                    }
                return \Redirect::back()->withSuccess(['msg', 'User Saved']);
            }else{
                return \Redirect::back()->withErrors(['msg', 'No User Found !']);
            }
        
        }else{
            return \Redirect::back()->withErrors(['msg', 'Please enter full instagram url']);
        }
        
    }
}
