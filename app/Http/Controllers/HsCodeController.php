<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HsCode;
use App\Setting;
use App\HsCodeSetting;
use Redirect;
use App\Product;
use DB;
use App\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use App\HsCodeGroup;
use App\HsCodeGroupsCategoriesComposition;
use App\SimplyDutyCategory;


class HsCodeController extends Controller
{
    public function index(Request $request){
    	if($request->code || $request->description){
           $query = HsCode::query();

            if(request('code') != null){
                $query->where('code', request('code'));
            }
            if(request('description') != null){
                $query->where('description','LIKE', "%{$request->description}%");
            }
            $categories = $query->paginate(Setting::get('pagination'));
        }else{
            $categories = HsCode::paginate(Setting::get('pagination'));
        }
        
         if ($request->ajax()) {
            return response()->json([
                'tbody' => view('simplyduty.hscode.partials.data', compact('categories'))->render(),
                'links' => (string)$categories->render()
            ], 200);
            }

        return view('simplyduty.hscode.index',compact('categories'));
    }

    public function saveKey(Request $request)
    {
        $setting = HsCodeSetting::all();
        if(count($setting) == 0){
            $set = new HsCodeSetting();
            $set->from_country = $request->from;
            $set->destination_country = $request->destination;
            $set->key = $request->key;
            $set->save();
        }else{
            $set = HsCodeSetting::first();
            $set->from_country = $request->from;
            $set->destination_country = $request->destination;
            $set->key = $request->key;
            $set->save();
        }

        return Redirect::to('/product/hscode');

    }

    public function mostCommon(Request $request)
    {
        $query = Product::query();
        
        if($request->category || $request->combination){

            $query->select('*', DB::raw('count(*) as total'))
                 ->where('category','>',3)->where('stock',1)->groupBy('category')->groupBy('composition');

            if($request->category != null){
                $query->where('category',$request->category);
            }

            if($request->combination != null){
               $query->where('composition','LIKE', "%".$request->combination."%"); 
            }

         $productss = $query->orderBy('total','desc')->take(100)->get();  
        }else{

         $productss = $query->select('*', DB::raw('count(*) as total'))
                 ->where('category','>',3)->where('stock',1)->groupBy('category')->groupBy('composition')->whereNotNull('composition')->where('composition','!=','')->orderBy('total','desc')->get();
          }
          
          if($productss->count() != 0){

            $count = 0;
          foreach ($productss as $product) {
                    if($count == 100){
                        break;
                    }

                    $categoryTree = CategoryController::getCategoryTree($product->category);
                    if(is_array($categoryTree)){
                        $childCategory = implode(' > ',$categoryTree);
                    }

                    $parentCategory = $product->product_category->title;
                    $name = $childCategory.' > '.$parentCategory;

                    $hscodeSearchString = str_replace(['&gt;','>'],'', $product->composition.' '.$name);
                    
                    $hscode = HsCode::where('description',$hscodeSearchString)->first();
                    
                    if($hscode == null){
                        $productArray[] = $product->setAttribute('category_name', $name);
                        $count++;
                    }
                    
                 }       

          }      
          

        
        if(!isset($productArray)){
            $productArray = [];
        }
        
        $selected_categories = $request->category ? $request->category : 1;

        $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control category_class select-multiple2','id' => 'category_value'])
            ->selected($selected_categories)
            ->renderAsDropdown();
                     
        $p = $productArray;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = Setting::get('pagination'); 
        
        $currentItems = array_slice($p, $perPage * ($currentPage - 1), $perPage);

        $products = new LengthAwarePaginator($currentItems, count($p), $perPage, $currentPage, [
            'path'  => LengthAwarePaginator::resolveCurrentPath()
        ]);
        $groups = HsCodeGroup::all();
        $cate = HsCodeGroupsCategoriesComposition::groupBy('category_id')->pluck('category_id')->toArray();
        $hscodes = SimplyDutyCategory::all();

        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('simplyduty.most-common.partials.data', compact('products','category_selection','groups','cate','hscodes'))->render(),
                'links' => (string)$products->appends($request->all())->render(),
                'total' => $products->total(),
            ], 200);
            }

        return view('simplyduty.most-common.index',compact('products','category_selection','groups','cate','hscodes'));         
    }



    public function mostCommonByCategory(Request $request)
    {

        if($request->category != null){
            $categories = DB::table('categories')->select('id','title')->where('id',$request->category)->orderBy('title','asc')->get()->toArray(); 
        }else{
            $categories = DB::table('categories')->orderBy('title','asc')->whereNotIn('id',[1,2,3])->get();  
        }
        
        
        foreach ($categories as $category) {

            $categoryTree = CategoryController::getCategoryTree($category->id);
            if(is_array($categoryTree)){
                $childCategory = implode(' > ',$categoryTree);
            }

            $parentCategory = $category->title;
            $name = $childCategory.' > '.$parentCategory;
            if($request->combination != null){
                
                $products = Product::select('composition')->where('category',$category->id)->where('category','>',3)->where('stock',1)->where('composition','LIKE','%'.$request->combination.'%')->whereNotNull('composition')->groupBy('composition')->limit(3)->get(); 

            }else{
             $products = Product::select('composition')->where('category',$category->id)->where('stock',1)->groupBy('composition')->whereNotNull('composition')->where('composition','!=','')->limit(3)->get(); 
         }

         foreach ($products as $product) {
            //Check if product hscode exist

            $hscodeSearchString = str_replace(['&gt;','>'],'', $product->composition.' '.$name);
            
            $hscode = HsCode::where('description',$hscodeSearchString)->first();

            if($hscode == null){
                $data[] = array('name' => $name,'id' => $category->id , 'composition' => $product->composition);
            }
           
        }

    }

    if(!isset($data)){
        $data = [];
    }
    $categories = $data;    
    
    $hscodes = SimplyDutyCategory::all();
    $groups = HsCodeGroup::all();
    $cate = HsCodeGroupsCategoriesComposition::groupBy('category_id')->pluck('category_id')->toArray();


    $selected_categories = $request->category ? $request->category : 1;

    $category_selection = Category::attr(['name' => 'category[]', 'class' => 'form-control category_class select-multiple2','id' => 'category_value'])
    ->selected($selected_categories)
    ->renderAsDropdown();

    if ($request->ajax()) {
        return response()->json([
            'tbody' => view('simplyduty.most-common-category.partials.data', compact('categories','hscodes','groups','cate'))->render(),
            'links' => (string)$categories->render(),
            'total' => count($categories),
        ], 200);
    }

    return view('simplyduty.most-common-category.index',compact('categories','hscodes','groups','cate','category_selection'));
    }
}
 