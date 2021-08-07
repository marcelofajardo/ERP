<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\Category;
use App\PriceOverride;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PriceOverrideController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $title = "Price override | Store Website";

        $allCategoriesDropdown         = Category::attr(['name' => 'category_id', 'class' => 'form-control cat-selection-dropdown', 'style' => 'width:100%;'])->renderAsDropdown();
        $allMultipleCategoriesDropdown = Category::attr(['name' => 'category_ids[]', 'class' => 'form-control select2', 'style' => 'width:100%;', "multiple" => true])->renderAsDropdown();

        return view('storewebsite::price-override.index', compact('title', 'allCategoriesDropdown', 'allMultipleCategoriesDropdown'));
    }

    public function records(Request $request)
    {
        $modal = PriceOverride::leftJoin("brands as b", "b.id", "price_overrides.brand_id")
            ->leftJoin("store_websites as sw", "sw.id", "price_overrides.store_website_id")
            ->leftJoin("categories as c", "c.id", "price_overrides.category_id")
            ->leftJoin("simply_duty_countries as sc", "sc.country_code", "price_overrides.country_code");

        if (!empty($request->keyword)) {
            $modal = $modal->where(function ($q) use ($request) {
                $q->orWhere("c.title", "like", "%" . $request->keyword . "%")->orWhere("b.name", "like", "%" . $request->keyword . "%")
                    ->orWhere("sc.country_name", "like", "%" . $request->keyword . "%");
            });
        }

        $modal = $modal->select([
            "price_overrides.*",
            "b.name as brand_name",
            "c.title as category_name",
            "sc.country_name as country_name",
            "sw.website as store_website_name",
        ]);

        $modal = $modal->orderby("price_overrides.id", "DESC")->paginate(12);

        return response()->json([
            "code"       => 200,
            "data"       => $modal->items(),
            "pagination" => (string) $modal->links(),
            "total"      => $modal->total(),
            "page"       => $modal->currentPage(),
        ]);
    }

    public function save(Request $request)
    {
        $post = $request->all();

        $validator = Validator::make($post, [
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }

        $id = $request->get("id", 0);

        $records = PriceOverride::find($id);

        if (!$records) {
            $records = new PriceOverride;

            //get all brand segement
            $allBrandSegments = $request->get("brand_segments");
            $allCategories    = $request->get("category_ids");
            $allCountries     = $request->get("country_codes");

            $isRun = false;
            if (!empty($allBrandSegments)) {
                foreach ($allBrandSegments as $brandSegment) {
                    if (!empty($allCategories)) {
                        foreach ($allCategories as $allCat) {
                            if (!empty($allCountries)) {
                                foreach ($allCountries as $allCountry) {

                                    $post["country_code"]  = $allCountry;
                                    $post["category_id"]   = $allCat;
                                    $post["brand_segment"] = $brandSegment;

                                    $records = new PriceOverride;
                                    $records->fill($post);
                                    $records->save();

                                    $isRun = true;
                                }
                            }
                        }
                    }
                }
            }

            if(!$isRun) {
                if (!empty($allBrandSegments)) {
                    foreach ($allBrandSegments as $brandSegment) {
                        if (!empty($allCategories)) {
                            foreach ($allCategories as $allCountry) {
                                
                                $post["category_id"]   = $allCountry;
                                $post["brand_segment"] = $brandSegment;

                                $records = new PriceOverride;
                                $records->fill($post);
                                $records->save();

                                $isRun = true;
                            }
                        }
                    }
                }
            }

            if(!$isRun) {
                if (!empty($allBrandSegments)) {
                    foreach ($allBrandSegments as $brandSegment) {
                        if (!empty($allCountries)) {
                            foreach ($allCountries as $allCountry) {
                                
                                $post["country_code"]   = $allCountry;
                                $post["brand_segment"] = $brandSegment;

                                $records = new PriceOverride;
                                $records->fill($post);
                                $records->save();

                                $isRun = true;
                            }
                        }
                    }
                }
            }

            if(!$isRun) {
                if (!empty($allBrandSegments)) {
                    foreach ($allBrandSegments as $brandSegment) {
                        $post["brand_segment"] = $brandSegment;
                        $records = new PriceOverride;
                        $records->fill($post);
                        $records->save();

                        $isRun = true;
                    }
                }
            }

            if(!$isRun) {
                if (!empty($allCountries)) {
                    foreach ($allCountries as $allCountry) {
                        $post["country_code"] = $allCountry;
                        $records = new PriceOverride;
                        $records->fill($post);
                        $records->save();

                        $isRun = true;
                    }
                }
            }

            if(!$isRun) {
                if (!empty($allCategories)) {
                    foreach ($allCategories as $allCountry) {
                        $post["category_id"] = $allCountry;
                        $records = new PriceOverride;
                        $records->fill($post);
                        $records->save();

                        $isRun = true;
                    }
                }
            }

            //$categories =

        } else {
            $records->fill($post);
            $records->save();
        }

        return response()->json(["code" => 200, "data" => $records]);

    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $po = PriceOverride::where("id", $id)->first();

        if ($po) {
            return response()->json(["code" => 200, "data" => $po]);
        }

        return response()->json(["code" => 500, "error" => "Wrong id!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $po = PriceOverride::where("id", $id)->first();

        if ($po) {
            $po->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong id!"]);
    }

    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required',
            'store_website' => 'required',
            //'country_id'       => 'required',
        ]);

        $error = self::validationResult($validator);

        if (!empty($error)) {
            return $error;
        }

        $product = \App\Product::find($request->product_id);

        if ($product) {
            $price         = $product->getPrice($request->store_website, $request->country_code);
            $price["duty"] = $product->getDuty($request->country_code);

            return response()->json(["code" => 200, "data" => $price]);
        }

        return response()->json(["code" => 200, "data" => []]);
    }

    public static function validationResult($validator)
    {
        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }
    }

}
