<?php

namespace App\Http\Controllers;

use App\ColdLeads;
use App\Customer;
use App\HashTag;
use Illuminate\Http\Request;
use InstagramAPI\Instagram;
use InstagramAPI\Signatures;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class InstagramProfileController extends Controller
{
    public function index() {
        $customers = Customer::where('instahandler', '!=', '')->where('rating', '>', 5)->orderBy('created_at', 'DESC')->orderBy('rating', 'DESC')->paginate(5);

        $customers = $customers->toArray();
        $customerProfiles = $customers['data'];

        $self = $this;
        $instagramProfiles = array_map(function($customer) use ($self) {
            return $self->getInstagramUserData($customer);
        }, $customerProfiles);


        return view('instagram.profile.list', compact('instagramProfiles'));
    }

    public function show($id, Request $request) {
        $customer = [
            'instahandler' => $id
        ];
        $instagramProfile = $this->getInstagramUserData($customer);

        return response()->json($instagramProfile);
    }

    private function getInstagramUserData($customer) {
        $instagram = new Instagram();
        $instagram->login(env('IG_USERNAME', 'sololuxury.official'), env('IG_PASSWORD', "NcG}4u'z;Fm7"));
        try {
            $profileData = $instagram->people->getInfoByName($customer['instahandler'])->asArray();
        } catch (\Exception $exception) {
            $profileData = [];
        }

        if (!isset($profileData['user'])) {
            return [];
        }


        $profileData = $profileData['user'];
        $rank = Signatures::generateUUID();
        $followers = $instagram->people->getFollowers($profileData['pk'], $rank)->asArray()['users'];
        $following = $instagram->people->getFollowing($profileData['pk'], $rank)->asArray()['users'];

        return [
            'id' => $profileData['pk'],
            'name' => $profileData['full_name'],
            'username' => $profileData['username'],
            'followers_count' => $profileData['follower_count'],
            'following_count' => $profileData['following_count'],
            'media' => $profileData['media_count'],
            'profile_pic_url' => $profileData['profile_pic_url'],
            'is_verified' => $profileData['is_verified'],
            'bio' => $profileData['biography'],
            'customer' => $customer,
            'followers' => $followers,
            'following' => $following
        ];

    }

    public function getFollowers($id) {
        $instagram = new Instagram();
        $instagram->login(env('IG_USERNAME', 'sololuxury.official'), env('IG_PASSWORD', "NcG}4u'z;Fm7"));
        $rankToken = Signatures::generateUUID();
        $followers = $instagram->people->getFollowers($id, $rankToken);

        dd($followers);

    }

    public function getPosts() {

        $customers = Customer::where('instahandler', '!=', '')->where('rating', '>', 5)->orderBy('rating', 'DESC')->get(10);

        $instagram = new Instagram();
        $instagram->login(env('IG_USERNAME', 'sololuxury.official'), env('IG_PASSWORD', "NcG}4u'z;Fm7"));
        $rankToken = Signatures::generateUUID();

        foreach ($customers as $customer) {
            $id = $instagram->people->getUserIdForName($customers->instahandler);
            $posts = $instagram->usertag->getUserFeed($id);

            dd($posts);

        }
    }

    public function add(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required',
            'id' => 'required',
            'type' => 'required',
            'rating' => 'required|integer'
        ]);

        if ($request->get('type') == 'customer') {
            $customer = new Customer();
            $customer->name = $request->get('name');
            $customer->instahandler = $request->get('username');
            $customer->ig_username = $request->get('id');
            $customer->rating = $request->get('rating');
            $customer->save();

            return response()->json([
                'status' => 'success'
            ]);
        }

        $customer = new ColdLeads();
        $customer->name = $request->get('name');
        $customer->username = $request->get('username');
        $customer->platform_id = $request->get('id');
        $customer->rating = $request->get('rating');
        $customer->platform = 'instagram';
        $customer->image = $request->get('image');
        $customer->bio = $request->get('bio');
        $customer->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function edit($d) {


        $customers = Customer::where('instahandler', '!=', '')->where('rating', '>', 5)->orderBy('rating', 'DESC')->get();

        $instagram = new Instagram();
        $instagram->login(env('IG_USERNAME', 'sololuxury.official'), env('IG_PASSWORD', "NcG}4u'z;Fm7"));
//        $rankToken = Signatures::generateUUID();

        $captions = '';

        foreach ($customers as $customer) {
            try {
                $id = $instagram->people->getUserIdForName($customer->instahandler);
            } catch (\Exception $exception) {
                continue;
            }
            $posts = $instagram->usertag->getUserFeed($id);

            $posts = $posts->asArray()['items'];

            foreach ($posts as $post) {
                $captions .= ($post['caption']['text'] . ' ');
            }
        }

        preg_match_all("/(#\w+)/", $captions, $matches);

        $hashtags = $matches[0];

        $hashlist = HashTag::get()->pluck('hashtag')->toArray();

        return view('instagram.profile.hashtags', compact('hashtags', 'hashlist'));

    }
}
