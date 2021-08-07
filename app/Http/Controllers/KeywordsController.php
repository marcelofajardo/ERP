<?php

namespace App\Http\Controllers;

use App\Keywords;
use Illuminate\Http\Request;

class KeywordsController extends Controller
{
    public function index() {
        $keywords = Keywords::all();

        return view('instagram.keywords.index', compact('keywords'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $k = new Keywords();
        $k->text = $request->get('name');
        $k->save();

        return redirect()->back()->with('message', 'Keyword added successfully!');
    }

    public function destroy($id) {
        $k = Keywords::findOrFail($id);
        $k->delete();

        return redirect()->back()->with('message', 'Keyword deleted successfully!');

    }
}
