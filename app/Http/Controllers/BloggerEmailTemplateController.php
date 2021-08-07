<?php

namespace App\Http\Controllers;

use App\BloggerEmailTemplate;
use Illuminate\Http\Request;

class BloggerEmailTemplateController extends Controller
{
    protected $data;

    public function index()
    {
        $template = BloggerEmailTemplate::first();
        if(!$template){
            $template = BloggerEmailTemplate::create([]);
        }
        $this->data['template'] = $template;
        return view('blogger.email-template', $this->data);
    }

    public function update(BloggerEmailTemplate $bloggerEmailTemplate, Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'message' => 'required',
        ]);
        $bloggerEmailTemplate->fill($request->all())->save();
        return redirect()->back()->withSuccess('Template Successfully Updated');
    }
}
