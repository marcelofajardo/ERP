<?php

namespace Modules\StoreWebsite\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\StoreWebsiteColor;
use App\StoreWebsite;
use App\Colors;
use Log;
use App\ColorReference;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Colors | Store Website";
        $store_colors = StoreWebsiteColor::all();


        if ($request->get("push") == 1) {
            $website    = \App\StoreWebsite::where("website_source", "magento")->where("id",$request->get("store_website_id"))->where("api_token", "!=", "")->get();
            $colorsData = \App\ColorNamesReference::groupBy('erp_name')->get();
            if (!$colorsData->isEmpty()) {
                foreach ($colorsData as $cd) {
                    foreach ($website as $web) {
                        $checkSite = \App\StoreWebsiteColor::where("erp_color", $cd->erp_name)->where("store_website_id", $web->id)->where("platform_id", ">", 0)->first();
                        if (!$checkSite) {
                            $id = \seo2websites\MagentoHelper\MagentoHelper::addColor($cd->erp_name, $web);
                            if (!empty($id)) {
                                \App\StoreWebsiteColor::where("erp_color", $cd->erp_name)->where("store_website_id", $web->id)->delete();
                                $swc                   = new \App\StoreWebsiteColor;
                                $swc->erp_color        = $cd->erp_name;
                                $swc->store_website_id = $web->id;
                                $swc->platform_id      = $id;
                                $swc->save();
                            }
                        }
                    }
                }
            }

            return redirect()->back()->with('success','Color Request finished successfully');;
        }

        // Check for keyword search
        if ($request->keyword != null) {
            $store_colors = $store_colors->where("erp_color", "like", "%" . $request->keyword . "%");
        }

        return view('storewebsite::color.index', [
            'erp_colors' => (new Colors())->all(),
            'store_websites' => StoreWebsite::pluck('title', 'id')->toArray(),
            'store_colors' => $store_colors,
            'title' => $title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'store_website_id' => 'required|integer',
            'store_color' => 'required|string|max:255',
            'erp_color' => 'required|string|max:255',
        ]);

        $data = $request->except('_token');
        StoreWebsiteColor::create( $data );
        return redirect()->route('store-website.color.list')->withSuccess('New Color added successfully.' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'store_website_id' => 'required|integer',
            'store_color' => 'required|string|max:255',
            'erp_color' => 'required|string|max:255',
        ]);

        $data = $request->except('_token');
        Log::debug(print_r($data,true));
        StoreWebsiteColor::find($id)->update($data);

        return redirect()->route('store-website.color.list')->withSuccess('You have successfully updated a store color!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $storeColor = StoreWebsiteColor::where("id", $id)->first();
        if ($storeColor) {
            $storeColor->delete();
            return redirect()->route('store-website.color.list')->withSuccess('You have successfully deleted a store color');
        }
        return redirect()->route('store-website.color.list')->withErrors('Unable to delete a store color');
    }

    public function pushToStore(Request $request)
    {
        $id = $request->get("id", 0);

        if ($id > 0) {
            $color    = \App\StoreWebsiteColor::where("id", $id)->first();
            if($color) {
                $website = \App\StoreWebsite::find($color->store_website_id);
                if($website) {
                    $colorName = !empty($color->store_color) ? $color->store_color : $color->erp_color;
                    $id = \seo2websites\MagentoHelper\MagentoHelper::addColor($colorName, $website);
                    if(!empty($id)) {
                        $color->platform_id = $id;
                        $color->save();
                    }
                }
                return response()->json(["code" => 200, "data" => $color]);
            }
        }

        return response()->json(["code" => 500, "error" => "Wrong row id!"]);

    }

    public function colorReference()
    {
        $colors = ColorReference::select('original_color')->get();
        $colorArray = [];
        foreach ($colors as $color) {
            $colorArray[] = $color->original_color;
        }

        return json_encode($colorArray);
    }
}
