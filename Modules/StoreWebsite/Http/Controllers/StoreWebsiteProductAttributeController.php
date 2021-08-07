<?php

namespace Modules\StoreWebsite\Http\Controllers;

use App\StoreWebsite;
use App\StoreWebsiteProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class StoreWebsiteProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $title = "Product attribute | Store Website";

        $storeWebsites = StoreWebsite::pluck('title', 'id');

        return view('storewebsite::product-attribute.index', compact('title', 'storeWebsites'));
    }

    /**
     * records Page
     * @param  Request $request [description]
     * @return
     */
    public function records(Request $request)
    {
        $records = StoreWebsiteProductAttribute::join("store_websites as sw", "sw.id", "store_website_product_attributes.store_website_id");

        $keyword = request("keyword");
        if (!empty($keyword)) {
            $records = $records->where(function ($q) use ($keyword) {
                $q->where("description", "LIKE", "%$keyword%")->orWhere("product_id", "LIKE", "%$keyword%");
            });
        }

        $records = $records->select(["store_website_product_attributes.*", "sw.title as store_website_name"])->paginate(10);

        $items = [];
        foreach ($records->items() as $k => &$item) {
            $item->description = strlen($item->description) > 15 ? substr($item->description, 0, 15) . "..." : $item->description;
            $items[]           = $item;
        }

        return response()->json(["code" => 200, "data" => $items, "total" => count($records)]);
    }

    /**
     * records Page
     * @param  Request $request [description]
     * @return
     */
    public function store(Request $request)
    {
        $post      = $request->all();
        $validator = Validator::make($post, [
            'product_id'   => 'required',
            'description'   => 'required',
            'price'         => 'required',
            'discount'      => 'required',
            'discount_type' => 'required',
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

        $records = StoreWebsiteProductAttribute::find($id);

        if (!$records) {
            $records = new StoreWebsiteProductAttribute;
        }

        $records->fill($post);
        $records->save();

        return response()->json(["code" => 200, "data" => $records]);
    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $storeWebsiteProductAttr = StoreWebsiteProductAttribute::where("id", $id)->first();

        if ($storeWebsiteProductAttr) {
            return response()->json(["code" => 200, "data" => $storeWebsiteProductAttr]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $storeWebsiteAttr = StoreWebsiteProductAttribute::where("id", $id)->first();

        if ($storeWebsiteAttr) {
            $storeWebsiteAttr->delete();
            return response()->json(["code" => 200]);
        }

        return response()->json(["code" => 500, "error" => "Wrong site id!"]);
    }

}
