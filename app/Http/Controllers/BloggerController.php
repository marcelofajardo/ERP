<?php

namespace App\Http\Controllers;

use App\Blogger;
use App\BloggerProduct;
use App\Brand;
use App\ContactBlogger;
use App\Email;
use App\Helpers;
use App\Http\Requests\CreateBloggerRequest;
use App\ReplyCategory;
use App\User;
use Illuminate\Http\Request;

class BloggerController extends Controller
{
    protected $data;

    public function __construct()
    {
//        $this->middleware('permission:blogger-all');
        $this->middleware(function ($request, $next) {
            session()->flash('active_tab','blogger_list_tab');
            return $next($request);
        });
    }

    public function index(Blogger $blogger, Request $request, BloggerProduct $blogger_product, ContactBlogger $contactBlogger)
    {
        session()->forget('active_tab');
        $this->data['bloggers'] = $blogger;
        $this->data['blogger_products'] = $blogger_product;
        $order_by = 'DESC';
        if ($request->orderby == '')
            $order_by = 'ASC';

        $this->data['orderby'] = $order_by;
        $this->data['bloggers'] = $this->data['bloggers']->with(['chat_message' => function ($chat_message) {
            $chat_message->select('id', 'message', 'blogger_id', 'status')->orderBy('id', 'desc');
        }]);
        $this->data['bloggers'] = $this->data['bloggers']->paginate(50);
        $this->data['select_bloggers'] = Blogger::pluck('name','id');
        $this->data['select_brands'] = Brand::pluck('name','id');
        $this->data['blogger_products'] = $this->data['blogger_products']->paginate(50);
        $this->data['contact_histories'] = $contactBlogger->paginate(50);
        return view('blogger.index', $this->data);
    }

    public function store(CreateBloggerRequest $request)
    {
        $blogger = new Blogger($request->all());
        $blogger->default_phone = $request->get('phone');
        $blogger->save();
        return redirect()->route('blogger.index')->withSuccess('You have successfully saved a blogger!');
    }

    public function update(CreateBloggerRequest $request, Blogger $blogger)
    {
        $blogger->fill($request->all())->save();
        return redirect()->route('blogger.index')->withSuccess('You have successfully saved a blogger!');
    }

    public function show(Blogger $blogger)
    {
        $this->data['blogger'] = $blogger;
        $this->data['reply_categories'] = ReplyCategory::all();
        $this->data['users_array'] = Helpers::getUserArray(User::all());

        return view('blogger.show', $this->data);
    }

    public function destroy(Blogger $blogger)
    {
        $blogger->delete();
        return redirect()->route('blogger.index')->withSuccess('You have successfully deleted a blogger');
    }
}

