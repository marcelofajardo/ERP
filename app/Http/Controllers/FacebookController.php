<?php

namespace App\Http\Controllers;

use App\BrandFans;
use App\GroupMembers;
use App\HashtagPosts;
use App\ScrappedFacebookUser;
use App\Services\Facebook\Facebook;
use Illuminate\Http\Request;


class FacebookController extends Controller
{
    public function index() {
        $posts = HashtagPosts::all();

        return view('scrap.facebook', compact('posts'));
    }

    public function show($type) {
        if ($type == 'group') {
            $groups = GroupMembers::all();

            return view('scrap.facebook_groups', compact('groups'));
        }
        if ($type == 'brand') {
            $brands = BrandFans::all();

            return view('scrap.facebook_brand_fans', compact('brands'));
        }
    }

    public function getInbox() {
        $facebook = new Facebook(new \Facebook\Facebook());

        return $facebook->getConversations();

    }

    /**
     * @SWG\Post(
     *   path="/facebook/scrape-user",
     *   tags={"Facebook"},
     *   summary="post facebook scrape user",
     *   operationId="post-facebook-scrape-user",
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
        $file = $request->file('file');

        $f = File::get($file);

        $payLoad = json_decode($f);

        // NULL? No valid JSON
        if ($payLoad == null) {
            return response()->json([
                'error' => 'Invalid json'
            ], 400);
        }
        if (is_array($payLoad) && count($payLoad) > 0) {
            $payLoad = json_decode(json_encode($payLoad), true);
            foreach ($payLoad as $postJson) {
                if($postJson['Owner'])
                {
                    $inf = ScrappedFacebookUser::where('owner',$postJson['Owner'])->first();
                    if($inf == null)
                    {
                        $scrapeFacebook = new ScrappedFacebookUser;
                        $scrapeFacebook->url = $postJson['URL'];
                        $scrapeFacebook->owner = $postJson['Owner'];
                        $scrapeFacebook->bio = $postJson['Bio'];
                        if(isset($postJson['keyword'])){
                            $scrapeFacebook->keyword = $postJson['keyword'];
                        }
                        $scrapeFacebook->save();
                    }
                }
            }
        }
        return response()->json([
            'ok'
        ], 200);
    } 


    public function facebookPost(Request $request)
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
                        $influencer              = new ScrapInfluencer;
                        $influencer->name        = $postJson['Owner'];
                        $influencer->url         = $postJson['URL'];
                        $influencer->followers   = $postJson['Followers'];
                        $influencer->following   = $postJson['Following'];
                        $influencer->posts       = $postJson['Posts'];
                        $influencer->description = $postJson['Bio'];
                        
                        $influencer->profile_pic = $postJson['profile_pic'];
                        $influencer->friends     = $postJson['friends'];
                        $influencer->cover_photo = $postJson['cover_photo'];
                        $influencer->interests   = $postJson['interests'];
                        $influencer->work_at     = $postJson['work_at'];



                        if(isset($postJson['keyword'])){
                            $influencer->keyword = $postJson['keyword'];
                        }
                        $influencer->save();
                    }
                }else{
                    //
                }
                
            }
        }

        // Return
        return response()->json([
            'ok'
        ], 200);
    }

}
