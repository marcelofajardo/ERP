<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Customer;
use App\Supplier;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;

class Select2Controller extends Controller
{

    public function customers(Request $request)
    {

        $customers = Customer::select('id', 'name', 'email');

        if (!empty($request->q)) {

            $customers->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->q . '%');
            });
        }

        $customers = $customers->paginate(30);

        $result['total_count'] = $customers->total();
        $result['incomplete_results'] = $customers->nextPageUrl() !== null;

        foreach ($customers as $customer) {

            $result['items'][] = [
                'id' => $customer->id,
                'text' => $customer->name
            ];
        }

        return response()->json($result);
    }
    public function suppliers(Request $request)
    {

        $suppliers = Supplier::select('id', 'supplier');

        if (!empty($request->q)) {

            $suppliers->where(function ($q) use ($request) {
                $q->where('supplier', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->q . '%');
            });
        }
        $suppliers = $suppliers->paginate(30);
        $result['total_count'] = $suppliers->total();
        $result['incomplete_results'] = $suppliers->nextPageUrl() !== null;

        foreach ($suppliers as $supplier) {

            $result['items'][] = [
                'id' => $supplier->id,
                'text' => $supplier->supplier
            ];
        }
        return response()->json($result);
    }


    public function scrapedBrand(Request $request)
    {

        $scrapedBrandsRaw = Supplier::selectRaw('scraped_brands_raw')->whereNotNull('scraped_brands_raw')->get();
        $rawBrands = [];

        foreach ($scrapedBrandsRaw as $key => $value) {
            array_push($rawBrands, array_unique(array_filter(array_column(json_decode($value->scraped_brands_raw, true), 'name'))));
        }

        $finalBrands = [];

        foreach ($rawBrands as $key => $brand) {

            $finalBrands +=  $brand;
        }
        $finalBrands = array_unique($finalBrands);
        if (!empty($request->q)) {
            $finalBrands = array_filter($finalBrands, function ($ele) use ($request) {
                return strpos(strtolower($ele), strtolower($request->q));
            });
        }
        foreach ($finalBrands as $key => $supplier) {
            if (strip_tags($supplier)) {

                $result['items'][] = [
                    'id' => strip_tags($supplier),
                    'text' => strip_tags($supplier)
                ];
            }
            $result['total_count'] = count($finalBrands);
        }
        return response()->json($result);
    }


    public function updatedbyUsers(Request $request)
    {

        $suppliers = User::select('id', 'name');


        $suppliers = $suppliers->paginate(30);

        $result['total_count'] = $suppliers->total();
        $result['incomplete_results'] = $suppliers->nextPageUrl() !== null;

        foreach ($suppliers as $supplier) {

            $result['items'][] = [
                'id' => $supplier->id,
                'text' => $supplier->name
            ];
        }

        return response()->json($result);
    }

    public function users(Request $request)
    {

        $users = User::select('id', 'name', 'email');

        if (!empty($request->q)) {

            $users->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->q . '%');
            });
        }

        $users = $users->orderBy('name','asc')->paginate(30);

        $result['total_count'] = $users->total();
        $result['incomplete_results'] = $users->nextPageUrl() !== null;

        foreach ($users as $user) {

            $text = $user->name;

            if ($request->format === 'name-email') {
                $text = $user->name . ' - ' . $user->email;
            }

            $result['items'][] = [
                'id' => $user->id,
                'text' => $text
            ];
        }

        return response()->json($result);
    }

    public function users_vendors(Request $request)
    {
        $users = User::select('id', 'name', 'email');

        if (!empty($request->q)) {

            $users->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->q . '%');
            });
        }

        $users = $users->orderBy('name','asc')->paginate(30);

        $result['total_count'] = $users->total();
        $result['incomplete_results'] = $users->nextPageUrl() !== null;

        foreach ($users as $user) {

            $text = $user->name;

            if ($request->format === 'name-email') {
                $text = $user->name . ' - ' . $user->email;
            }

            $result['items'][] = [
                'id' => $user->id,
                'text' => $text
            ];
        }

        $vendors = Vendor::select('id', 'name', 'email');
        if (!empty($request->q)) {

            $vendors->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->q . '%');
            });
        }
        $vendors = $vendors->paginate(30);

        $result_vendors['vendors_total_count'] = $vendors->total();
        $result_vendors['vendors_incomplete_results'] = $vendors->nextPageUrl() !== null;

        foreach ($vendors as $user) {

            $text = $user->name;

            if ($request->format === 'name-email') {
                $text = $user->name . ' - ' . $user->email;
            }

            $result_vendors['items'][] = [
                'id' => $user->id,
                'text' => $text
            ];
        }

        array_push($result, $result_vendors);

        return response()->json($result);
    }

    public function allBrand(Request $request)
    {
        if (isset($request->sort)) {
            $brands = Brand::select('id', 'name')->orderBy('name', 'ASC');
        } else {
    
            $brands = Brand::select('id', 'name');
        }
    
        if (!empty($request->q)) {
    
            $brands->where(function ($q) use ($request) {
                $q->where('name', 'LIKE',  $request->q . '%');
            });
        }
    
        $brands = $brands->paginate(30);
    
        $result['total_count'] = $brands->total();
        $result['incomplete_results'] = $brands->nextPageUrl() !== null;
    
        foreach ($brands as $brand) {
    
            $result['items'][] = [
                'id' => $brand->id,
                'text' => $brand->name
            ];
        }
    
        return response()->json($result);
    }


    public function allCategory(Request $request)
    {
            $category = Category::select('id', 'title');
    
    
        if (!empty($request->q)) {
    
            $category->where(function ($q) use ($request) {
                $q->where('title', 'LIKE',  $request->q . '%');
            });
        }
    
        $category = $category->paginate(30);
    
        $result['total_count'] = $category->total();
        $result['incomplete_results'] = $category->nextPageUrl() !== null;
    
        foreach ($category as $cat) {
    
            $result['items'][] = [
                'id' => $cat->id,
                'text' => $cat->title
            ];
        }
    
        return response()->json($result);
    }  

    public function customersByMultiple(Request $request){

        $term = request()->get("q", null);
        $customers = \App\Customer::select('id', 'name', 'phone')->where("name", "like", "%{$term}%")->orWhere("phone", "like", "%{$term}%")->orWhere("id", "like", "%{$term}%");
 
        $customers = $customers->paginate(30);

        $result['total_count'] = $customers->total();
        $result['incomplete_results'] = $customers->nextPageUrl() !== null;

        foreach ($customers as $customer) {

            $result['items'][] = [
                'id' => $customer->id,
                'text' => '<strong>Name</strong>: ' . $customer->name . ' <strong>Phone</strong>: ' . $customer->phone
            ];
        }

        return response()->json($result);

    }
   
}
