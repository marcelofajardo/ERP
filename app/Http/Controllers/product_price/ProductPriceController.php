<?php

namespace App\Http\Controllers\product_price;

use App\Http\Controllers\Controller;
use App\CountryGroup;
use App\StoreWebsite;
use App\Product;
use App\Setting;
use App\SimplyDutyCountry;
use App\Helpers\StatusHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        ini_set("memory_limit", -1);

        $countryGroups = SimplyDutyCountry::getSelectList();
        $cCodes = $countryGroups;
        if(!empty($request->country_code)) {
            $cCodes = SimplyDutyCountry::where("country_code",$request->country_code)->pluck("country_name","country_code")->toArray();//$request->country_code;
        }

        $product_list = [];
        $storeWebsites = StoreWebsite::select('title', 'id','website')->where("is_published","1")->get()->toArray();
        if(strtolower($request->random) == "yes" && empty($request->product)) {
            $products = Product::where( 'status_id', StatusHelper::$finalApproval)->groupBy('category')->limit(50)->latest()->get();
        }else{
            $products = Product::where( 'id', $request->product )->orWhere( 'sku', $request->product )->get();
        }

        if($products->isEmpty()){
            return redirect()->back()->with('error','No product found');
        }

        foreach ($storeWebsites as $key => $value) {
            foreach($products as $product) {
                foreach($cCodes as $ckey => $cco) {
                    $dutyPrice = $product->getDuty( $ckey );
                    $price = $product->getPrice( $value['id'], $ckey,null, true,$dutyPrice);
                    $ivaPercentage = \App\Product::IVA_PERCENTAGE;

                    $product_list[] = [
                        'getPrice'     => $price,
                        'id'           => $product->id,
                        'sku'          => $product->sku,
                        'brand'        => ($product->brands) ? $product->brands->name : "-",
                        'segment'      => ($product->brands) ? $product->brands->brand_segment : "-",
                        'website'      => $value['website'],
                        'eur_price'    => number_format($price['original_price'],2,'.',''),
                        'seg_discount' => (float)$price['segment_discount'],
                        'iva'          => \App\Product::IVA_PERCENTAGE."%",
                        'add_duty'     => $product->getDuty( $ckey)."%",
                        'add_profit'   => number_format($price['promotion'],2,'.',''),
                        'final_price'  => number_format($price['total'],2,'.',''),
                        'country_code' => $ckey,
                        'country_name' => $cco,
                    ];
                }
            }
        }

        return view('product_price.index',compact('countryGroups','product_list'));
    }

}
