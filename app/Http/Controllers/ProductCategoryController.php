<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{

    public function index()
    {
        $title = "Product Category";
        $brands = \App\Brand::pluck("name","id")->toArray();
        $users = \App\User::pluck("name","id")->toArray();

        return view("product-category.index",compact('title','brands','users'));
    }

    public function history(Request $request , $id)
    {
    	$productCategory = \App\ProductCategoryHistory::leftJoin("categories as c","c.id","product_category_histories.category_id")
    	->leftJoin("categories as d","d.id","product_category_histories.old_category_id")
    	->leftJoin("users as u","u.id","product_category_histories.user_id")
    	->where("product_id",$id)
    	->orderBy("product_category_histories.created_at","desc")
    	->select(["product_category_histories.*","c.title as new_cat_name","d.title as old_cat_name","u.name as user_name"])
    	->get();

        return response()->json(["code" => 200 , "data" => $productCategory]);
    }

    public function records(Request $request)
    {
        $brands = $request->get("brands",[]);
        $usresIds = $request->get("user_ids",[]);
        $keywords = $request->get("keyword");


        $productCategory = \App\ProductCategoryHistory::leftJoin("categories as c","c.id","product_category_histories.category_id")
        ->leftJoin("products as p","p.id","product_category_histories.product_id")
        ->leftJoin("categories as d","d.id","product_category_histories.old_category_id")
        ->leftJoin("users as u","u.id","product_category_histories.user_id");

        if(!empty($brands)) {
            $productCategory = $productCategory->whereIn("p.brand",$brands);
        }

        if(!empty($usresIds)) {
           $productCategory = $productCategory->whereIn("product_category_histories.user_id",$usresIds);
        }

        if($keywords) {
           $productCategory = $productCategory->where(function($q) use($keywords) {
                $q->orWhere("p.id","like","%".$keywords."%")->orWhere("p.name","like","%".$keywords."%");
           });
        }

        $updatedHistory = clone $productCategory;
        $updatedHistory = $updatedHistory->groupBy("product_category_histories.user_id");
        $updatedHistory = $updatedHistory->select(["u.name as user_name",\DB::raw("count(u.id) as total_updated")]);
        $updatedHistory = $updatedHistory->get()->toArray();

        $productCategory = $productCategory->orderBy("product_category_histories.created_at","desc")
        ->select(["product_category_histories.*","c.title as new_cat_name","d.title as old_cat_name","u.name as user_name","p.name as product_name"])
        ->paginate();

        // total product without category by supplier
        $productsLeft =  \App\Product::join("product_suppliers as ps","ps.product_id","products.id")
        ->join("suppliers as s","s.id","ps.supplier_id")
        ->leftJoin("category_update_users as cuu","cuu.supplier_id","s.id")
        ->leftJoin("users as u","u.id","cuu.user_id")
        ->where(function($q){
            $q->whereNull("products.category")->orWhere("products.category","")->orWhere("products.category",1);
        })
        ->groupBy("s.id")
        ->select(["s.supplier as supplier_name",\DB::raw("count(s.id) as total_left"),"u.name as user_name","u.phone","u.id as user_id","s.id as supplier_id"])
        ->get()
        ->toArray();

        return response()->json([
            "code"        => 200,
            "data"        => $productCategory->items(),
            "pagination"  => (string) $productCategory->render(),
            "total"       => $productCategory->total(),
            "updated_history" => $updatedHistory,
            "products_left" => $productsLeft
        ]);

    }

    public function updateCategoryAssigned(Request $request)
    {
        if(!empty($request->user_id) && $request->supplier_id != null) 
        {
            $categoryUpdate = \App\CategoryUpdateUser::where("supplier_id",$request->supplier_id)->where("user_id",$request->user_id)->first();
            if(!$categoryUpdate) {
                $categoryUpdate = new \App\CategoryUpdateUser;
                $categoryUpdate->user_id = $request->user_id;
                $categoryUpdate->supplier_id = $request->supplier_id;
                $categoryUpdate->save();
            }

            if($request->comment != "") {
                $message = "WORK ON {$categoryUpdate->supplier->supplier}: " . $request->comment;
                \App\ChatMessage::sendWithChatApi($categoryUpdate->user->phone, null, $message);
            } 

            return response()->json(["code" => 200 , "data" => [] , "message" => "Request send succefully"]);
        }

        return response()->json(["code" => 500 , "data" => [] , "message" => "Required field is missing [user_id,supplier_id]"]);
    }
}
