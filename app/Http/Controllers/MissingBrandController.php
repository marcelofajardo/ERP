<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MissingBrand;
use App\Brand;
use App\Activity;
use Auth;

class MissingBrandController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/missing-brand/save",
     *   tags={"Scraper"},
     *   summary="Save unknown brand",
     *   operationId="scraper-save-missing-brand",
     *   @SWG\Response(response=200, description="successful operation"),
     *      @SWG\Parameter(
     *          name="name",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     *      @SWG\Parameter(
     *          name="supplier",
     *          in="path",
     *          required=true, 
     *          type="string" 
     *      ),
     * )
     *
     */
    public function saveMissingBrand(Request $request)
    {
    	$name = $request->name;
    	$supplier = $request->supplier;
		if($name){
			$checkIfExist = MissingBrand::where('name',$name)->where('supplier',$supplier)->first();
			if($checkIfExist){
				return response()->json([
				    'message' => 'Missing Brand Already Exist'
				]);
			}else{
				$brand = new MissingBrand();
				$brand->name = $name;
				$brand->supplier = $supplier;
				$brand->save();
				return response()->json([
				    'message' => 'Missing Brand Saved'
				]);
			}
    	}else{
			return response()->json([
			    'message' => 'Please Send Brand Name'
			]);
    	}
    }


    public function index(Request $request)
    {
    	$missingBrands = MissingBrand::query();

        //orderBy('id','desc')->paginate(20);
        if(!empty($request->term)){
            $missingBrands = $missingBrands->where('name','LIKE','%'.$request->term.'%')->orWhere('supplier','LIKE','%'.$request->term.'%');
        }

        if(!empty($request->select)){
            $missingBrands = $missingBrands->where('supplier',$request->select);
        }

        $scrapers = MissingBrand::select('supplier')->groupBy('supplier')->get();
        $missingBrands = $missingBrands->paginate(20);
        if ($request->ajax()) {
        return response()->json([
                'tbody' => view('missingbrand.partial.data', compact('missingBrands','scrapers'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$missingBrands->render(),
                'count' => $missingBrands->total(),
            ], 200);
        } 

    	$title = 'Missing Brands';
    	return view('missingbrand.index',['missingBrands' => $missingBrands,'title' => $title,'scrapers' => $scrapers]);
    }

    public function store(Request $request, Brand $brand)
    {
        $data = $request->except('_token', '_method');

        $brand->name = $data['name'];
        $brand->save();

        $mBrand = MissingBrand::find($data['id']);
        if($mBrand) {
            $mBrand->delete();
        }

        return redirect()->back()->with('success', 'Brand added successfully');
    }

    public function reference(Request $request, Brand $brand)
    {

        $brand = $brand->find($request->brand);
        if($brand) {
            $ref = explode(",", $brand->references);
            $ref[]  = $request->name;
            $brand->references = implode(",",array_filter($ref));
            $brand->save();
        }

        $mBrand = MissingBrand::find($request->id);
        if($mBrand) {
            $mBrand->delete();
        }
        Activity::create([
            'subject_type' => "Brand",
            'subject_id' => $brand->id,
            'causer_id' => Auth::user()->id,
            'description' => Auth::user()->name ." has merged ".$brand->name. " to ".$mBrand->brand_name
        ]);

        return redirect()->back()->with('success', 'Brand reference added successfully');

    }


    public function multiReference (Request $request)
    {
        $ids = $request->ids;
        if(!empty($ids)) {
            $brand = Brand::find($request->brand);
            $ref   = explode(",", $brand->references);
            if($brand) {
                $mIds = MissingBrand::whereIn("id",$ids)->get();
                if(!$mIds->isEmpty()) {
                    foreach($mIds as $m) {
                        $ref[] = $m->name;
                        $m->delete();
                    }
                }
                $brand->references = implode(",",array_filter($ref));
                $brand->save();
            }
        }

        return response()->json(["code" => 200 , "data" => [], "message" => "Brand reference added successfully"]);
    }

    public function automaticMerge()
    {

        $missingBrands = MissingBrand::all();
        $brands = Brand::select('name','id','references')->get();
        foreach ($missingBrands as $missingBrand) {
            $isFind = 0;
            foreach ($brands as $brand) {
                
                $word = $brand->name;

                $input = $missingBrand->name;
                
                //remove space
                $input = preg_replace('/\s+/', '', $input);

                $input = strip_tags($input);

                $word = preg_replace('/\s+/', '', $word);

                //remove all special character
                $input = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $input);

                $word = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $word);

                similar_text(strtolower($input), strtolower($word), $percent);

                if ($percent >= 70) {
                    $brand->references .= ',' . $missingBrand->name;
                    $brand->save();
                    $isFind = 1;
                    break;
                }
            }

            if($isFind){
                //deleting the missing brand
                $missingBrand->delete();

            }else{
                //creating new brand  
                $newBrand = new Brand;
                $newBrand->name = strip_tags($missingBrand->name);
                $newBrand->euro_to_inr = 0;
                $newBrand->save();
                
                //deleting missing brand
                $missingBrand->delete();
            }
        }
        return response()->json('Missing brands updated',200);
    }
}
