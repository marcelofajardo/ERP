<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function store(Request $request){

    	$this->validate($request,[
    		'content' => 'required',
	    ]);

    	$data  = $request->all();
    	$data['user_id'] = \Auth::id();

    	$comment = Comment::create($data);

    	return redirect()->back()->with('success','Comment added');
    }

    public function destroy(Comment $comment){

    	$comment->delete();

	    return redirect()->back()->with('success','Comment deleted');
    }
}
