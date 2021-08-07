<?php

namespace App\Http\Controllers;

use App\Account;
use App\BrandTaggedPosts;
use App\Product;
use Illuminate\Http\Request;
use InstagramAPI\Instagram;
use InstagramAPI\Media\Photo\InstagramPhoto;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class BrandTaggedPostsController extends Controller
{
    public function index () {
        $posts = BrandTaggedPosts::all();

        return view('instagram.bt.index', compact('posts'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'account_id' => 'required',
            'receipts' => 'required|array',
        ]);

        $account = Account::find($request->get('account_id'));

        $instagram = new Instagram();
        $instagram->login($account->last_name, $account->password);

        $message = $request->get('message');

        $usernames = $request->get('receipts');

        foreach ($usernames as $username) {

            $id = $instagram->people->getUserIdForName($username);

            $rec = ['users' => [$id]];

            $instagram->direct->sendText($rec, $message);

            if ($request->hasFile('image'))
            {
//                dd('here');
                $file = $request->file('image');
                $photo = new InstagramPhoto($file);
                $instagram->direct->sendPhoto($rec, $photo->getFile());
            }
        }

        return redirect()->back()->with('message', 'Message sent successfully!');



    }
}
