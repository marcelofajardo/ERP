<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::paginate(100);
        return View('articles.index',
        compact('articles')
        );
    }

    /**
     * Get Broken Links Details
     * Function for display
     * 
     * @return json response
     */
    public function updateTitle(Request $request) {
        $article = Article::findOrFail($request['id']);
        $article->title = $request['article_title'];
        $article->save();
        return response()->json([
            'type' => 'success',
            'message' => 'Title Updated'
        ]);
    }

    /**
     * Updated Title
     * Function for display
     * 
     * @return json response
     */
    public function updateDescription(Request $request) {
        $article = Article::findOrFail($request['id']);
        $article->description = $request['article_desc'];
        $article->save();
        return response()->json([
            'type' => 'success',
            'message' => 'Description Updated'
        ]);
    }

}
