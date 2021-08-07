<?php

namespace App\Http\Controllers;

use App\CompetitorFollowers;
use App\CompetitorPage;
use Illuminate\Http\Request;
use InstagramAPI\Instagram;
use InstagramAPI\Signatures;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class CompetitorPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = CompetitorPage::all();
        return view('instagram.comp.index', compact('pages'));
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
            'name' => 'required',
            'username' => 'required',
            'platform' => 'required'
        ]);

        $com = new CompetitorPage();
        $com->name = $request->get('name');
        $com->username = $request->get('username');
        $com->platform = $request->get('platform');
        $com->save();

        return redirect()->back()->with('message', 'Competitor page added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CompetitorPage  $competitorPage
     * @return \Illuminate\Http\Response
     */
    public function show(CompetitorPage $competitorPage)
    {
        $username = $competitorPage->username;

        $item = $this->getInstagramUserData($username);

        return view('instagram.comp.grid', compact('item'));


    }

    private function getInstagramUserData($username) {
        $instagram = new Instagram();
        $instagram->login(env('IG_USERNAME', 'sololuxury.official'), env('IG_PASSWORD', "NcG}4u'z;Fm7"));

        $request = $instagram->request('https://www.instagram.com/'.$username.'/?__a=1')->getDecodedResponse();
        $preparedMedia = [];
        if (isset($request["graphql"]['user']['edge_owner_to_timeline_media']['edges'])) {
            $medias = $request["graphql"]['user']['edge_owner_to_timeline_media']['edges'];
            $captions = '';

        foreach ($medias as $key=>$media) {
            $preparedMedia[$key] = $media['node'];
            $preparedMedia[$key]['comments'] = $instagram->media->getComments($media['node']['id'])->asArray();
        }

        }
        try {
            $profileData = $instagram->people->getInfoByName($username)->asArray();
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
            'followers' => $followers,
            'following' => $following,
            'medias' => $preparedMedia
        ];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompetitorPage  $competitorPage
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $followers = CompetitorFollowers::where('competitor_id', $id)->paginate(10);

        $processedFollowers = [];

        foreach ($followers as $follower) {
            $processedFollowers[] = $this->getInstagramUserDataWithoutFollowers($follower->username, $follower->id);
        }

        return view('instagram.comp.followers', compact('processedFollowers', 'followers'));

    }

    private function getInstagramUserDataWithoutFollowers($user, $id) {
        $instagram = new Instagram();
        $instagram->login(env('IG_USERNAME', 'sololuxury.official'), env('IG_PASSWORD', "NcG}4u'z;Fm7"));
        try {
            $profileData = $instagram->people->getInfoByName($user)->asArray();
        } catch (\Exception $exception) {
            $profileData = [];
        }

        if (!isset($profileData['user'])) {
            return [];
        }


        $profileData = $profileData['user'];

        return [
            'id' => $profileData['pk'],
            'uid' => $id,
            'name' => $profileData['full_name'],
            'username' => $profileData['username'],
            'followers_count' => $profileData['follower_count'],
            'following_count' => $profileData['following_count'],
            'profile_pic_url' => $profileData['profile_pic_url'],
            'is_verified' => $profileData['is_verified'],
            'bio' => $profileData['biography']
        ];

    }

    public function hideLead($id) {
        $c = CompetitorFollowers::findOrFail($id);
        $c->status = 0;
        $c->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function approveLead($id) {
        $c = CompetitorFollowers::findOrFail($id);
        $c->status = 2;
        $c->save();

        return response()->json([
            'status' => 'success'
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompetitorPage  $competitorPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompetitorPage $competitorPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompetitorPage  $competitorPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompetitorPage $competitorPage)
    {
        //
    }
}
