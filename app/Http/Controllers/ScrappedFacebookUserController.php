<?php

namespace App\Http\Controllers;

use App\ScrappedFacebookUser;
use App\Services\Facebook\Facebook;
use Illuminate\Http\Request;


class ScrappedFacebookUserController extends Controller
{
    public function index(Request $request) {
        $query = ScrappedFacebookUser::query();

        $scrapeFacebookUsers = $query->orderBy('id', 'asc')->paginate(25)->appends(request()->except(['page']));
        return view('scrapefacebook.index', compact('scrapeFacebookUsers'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

}
