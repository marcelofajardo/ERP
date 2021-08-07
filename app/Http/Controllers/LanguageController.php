<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Language;
use App\Setting;

class LanguageController extends Controller {

    public function index(Request $request) {
        $query = Language::query();

        if ($request->term) {
            $query = $query->where('locale', 'LIKE', '%' . $request->term . '%')
                    ->orWhere('code', 'LIKE', '%' . $request->term . '%');
        }
        $languages = $query->orderBy('code')->paginate(Setting::get('pagination'));

        return view('language.index', [
            'languages' => $languages,
        ]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'locale' => 'sometimes|nullable|string|max:255',
            'code' => 'required'
        ]);

        $data = $request->except('_token');
        Language::create($data);

        return redirect()->route('language.index')->withSuccess('You have successfully stored language');
    }

    public function update(Request $request) {
        $language = Language::find($request->id);
        $language->locale = $request->locale;
        $language->code = $request->code;
        $language->store_view = $request->store_view;
        $language->status = $request->status;
        $language->update();

        return response()->json(['success'], 200);
    }

    public function delete(Request $request) {
        $language = Language::find($request->id);
        $language->delete();
        return response()->json(['success'], 200);
    }

}
