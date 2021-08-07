<?php

namespace App\Http\Controllers;

use App\AutoRefreshPage;
use Illuminate\Http\Request;

class AutoRefreshController extends Controller
{
    public function index(Request $request)
    {
        $pages = \App\AutoRefreshPage::query();

        if($request->term) {
            $pages = $pages->where("page","like","%".$request->term."%");
        }

        $pages = $pages->paginate(10);

        return view('auto-refresh-page.index', compact('pages'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'page' => 'required',
            'time' => 'required',
        ]);

        $params = [
            "page"    => $request->get("page"),
            "time"    => $request->get("time"),
            "user_id" => \Auth::user()->id,
        ];

        AutoRefreshPage::create($params);

        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {
        $autoRefresh = \App\AutoRefreshPage::find($id);

        return view("auto-refresh-page.partials.edit", compact('autoRefresh'));
    }

    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'page' => 'required',
            'time' => 'required',
        ]);

        $page = AutoRefreshPage::find($id);
        $page->fill($request->all());

        if ($page->save()) {
            return redirect()->back()->withSuccess('Record updated succesfully');
        }

        return redirect()->back()->withErrors('Please Provide with required data for update');
    }
    public function delete(Request $request,$id)
    {
        $page = AutoRefreshPage::find($id);
        if ($page) {
            $page->delete();
            return redirect()->back()->withSuccess('Record deleted succesfully');
        }
        return redirect()->back()->withErrors('Record not found');
    }
}
