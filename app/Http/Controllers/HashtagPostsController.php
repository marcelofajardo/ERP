<?php

namespace App\Http\Controllers;

use App\HashtagPosts;
use Illuminate\Http\Request;

class HashtagPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HashtagPosts $hashtagPosts
     * @return \Illuminate\Http\Response
     */
    public function show(HashtagPosts $hashtagPosts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HashtagPosts $hashtagPosts
     * @return \Illuminate\Http\Response
     */
    public function edit(HashtagPosts $hashtagPosts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\HashtagPosts $hashtagPosts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HashtagPosts $hashtagPosts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HashtagPosts $hashtagPosts
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hashtagPosts = HashtagPosts::find($id);
        if ($hashtagPosts) {
            $hashtagPosts->delete();
        }

        return redirect()->back()->with('message', 'Post Deleted!');
    }
}
