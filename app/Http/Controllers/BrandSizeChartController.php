<?php

namespace App\Http\Controllers;

use App\Brand;
use App\BrandCategorySizeChart;
use App\Category;
use App\StoreWebsite;
use Illuminate\Http\Request;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class BrandSizeChartController extends Controller
{
    //

    public function __construct()
    {

    }

    public function index()
    {
        $storeWebsite = \App\StoreWebsite::get();
        $sizeChart    = BrandCategorySizeChart::get();

        return view('brand-size-chart.index', compact('storeWebsite', 'sizeChart'));
    }

    /**
     * Create size chart
     */
    public function createSizeChart()
    {
        $brands       = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $category     = Category::orderBy('title', 'asc')->pluck('title', 'id');
        $storeWebsite = StoreWebsite::orderBy('website', 'asc')->pluck('website', 'id');

        return view('brand-size-chart.create', ['brands' => $brands, 'category' => $category, 'storeWebsite' => $storeWebsite]);
    }

    /**
     * Store brand size chart
     */
    public function storeSizeChart(Request $request)
    {
        $this->validate($request, [
            'brand_id' => 'required',
            'size_img' => 'required|mimes:jpeg,jpg,png',
        ]);
        $brandCat = BrandCategorySizeChart::create([
            'brand_id'         => $request->brand_id,
            'category_id'      => $request->category_id,
            'store_website_id' => $request->store_website_id,
        ]);

        if ($request->hasfile('size_img')) {
            $media = MediaUploader::fromSource($request->file('size_img'))
                ->toDirectory('brand-size-chart')
                ->upload();
            $brandCat->attachMedia($media, ['size_chart']);
        }

        session()->flash('success', 'Brand size chart uploaded successfully');
        return redirect()->route('brand/size/chart');
    }
}
