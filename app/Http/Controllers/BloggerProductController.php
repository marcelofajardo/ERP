<?php

namespace App\Http\Controllers;

use App\Blogger;
use App\BloggerProduct;
use App\Brand;
use App\Helpers;
use App\Http\Requests\CreateBloggerProductRequest;
use App\ReplyCategory;
use App\User;
use Illuminate\Http\Request;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class BloggerProductController extends Controller
{
    public function __construct()
    {
       // $this->middleware('permission:blogger-all');
        $this->middleware(function ($request, $next) {
            session()->forget('active_tab');
            return $next($request);
        });
    }

    public function store(BloggerProduct $bloggerProduct, CreateBloggerProductRequest $request)
    {
        $bloggerProduct->create($request->all());
        return redirect()->route('blogger.index')->withSuccess('You have successfully saved a blogger product record');
    }

    public function update(BloggerProduct $bloggerProduct, CreateBloggerProductRequest $request)
    {
        $blogger = $bloggerProduct->blogger;
        if($request->has('default_phone')){
            $blogger->default_phone = $request->get('default_phone');
        }
        if($request->has('whatsapp_number')){
            $blogger->default_phone = $request->get('whatsapp_number');
        }
        $blogger->save();
        $bloggerProduct->fill($request->all())->save();
        return redirect()->route('blogger.index')->withSuccess('You have successfully updated a blogger product record');
    }

    public function show(BloggerProduct $blogger_product, Blogger $blogger, Brand $brand)
    {
        $this->data['bloggers'] = $blogger->pluck('name','id');
        $this->data['brands'] = $brand->pluck('name','id');
        $this->data['blogger_product'] = $blogger_product;
        $this->data['reply_categories'] = ReplyCategory::all();
        $this->data['users_array'] = Helpers::getUserArray(User::all());

        return view('blogger.show', $this->data);
    }

    public function uploadImages(BloggerProduct $bloggerProduct, Request $request)
    {
        $this->validate($request, [
            'images.*' => 'image'
        ]);

        $uploaded_images = [];
        if ($request->hasFile('images')) {
            try{
                foreach ($request->file('images') as $image) {
                    $media = MediaUploader::fromSource($image)->toDirectory('blogger-images')->upload();
                    array_push($uploaded_images, $media);
                    $bloggerProduct->attachMedia($media,config('constants.media_tags'));
                }
            }catch (\Exception $exception){
                return response($exception->getMessage(), $exception->getCode());
            }
        }
        return response($uploaded_images, 200);
    }

    public function getImages(BloggerProduct $bloggerProduct)
    {
        return response($bloggerProduct->getMedia(config('constants.media_tags')), 200);
    }
}
