<?php

namespace App\Http\Controllers;

use App\Compositions;
use Illuminate\Http\Request;

class CompositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matchedArray = [];
        $compositions =Compositions::withCount('productCounts');

        if ($request->keyword != null) {
            //getting search results based on two words
            $comps = $compositions->where("name", "LIKE", "%{$request->term}%")->get();
            foreach ($comps as $comp) {
                $searchWord      = $request->keyword;
                $searchWordArray = explode(' ', $searchWord);
                if (count($searchWordArray) != 0) {
                    $isMatched = 1;
                    foreach ($searchWordArray as $word) {
                        if (strpos($comp->name, $word) !== false) {

                        } else {
                            $isMatched = 0; 
                        }
                    }
                    if ($isMatched == 1) {
                        $matchedArray[] = $comp->id;
                    }
                }
            }
        }

        if ($request->keyword != null) {
            $compositions = $compositions->whereIn('id', $matchedArray);
        }

        $listcompostions = Compositions::where('replace_with', '!=', '')->groupBy('replace_with')->pluck('replace_with', 'replace_with')->toArray();
// dd($compositions->get());
        // foreach($compositions  as  $com){
        //         dump($com->title);
        //     }


// foreach($compositions  as  $com){
//     dump($com->title);
// }

        if ($request->with_ref == 1) {
            $compositions = $compositions->where(function ($q) use ($request) {
                $q->orWhere('replace_with', "!=", '')->WhereNotNull('replace_with');
            });
        } else {
            $compositions = $compositions->where(function ($q) use ($request) {
                $q->orWhere('replace_with', '')->orWhereNull('replace_with');
            });
            
            $compositions = $compositions->orderBy('product_counts_count', 'desc')->paginate(200);
        }



        return view('compositions.index', compact('compositions', 'listcompostions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name'         => 'required',
            'replace_with' => 'required',
        ]);

        $c = Compositions::create($request->all());

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Compositions  $compositions
     * @return \Illuminate\Http\Response
     */
    public function show(Compositions $compositions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Compositions  $compositions
     * @return \Illuminate\Http\Response
     */
    public function edit(Compositions $compositions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Compositions  $compositions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compositions $compositions, $id)
    {
        //
        $c = $compositions->find($id);
        if ($c) {
            $c->fill($request->all());
            $c->save();
        }

        if ($request->ajax()) {
            return response()->json(["code" => 200, "data" => []]);
        }

        return redirect()->back();
    }

    public function updateName(Request $request)
    {
        //
        $validator = \Validator::make($request->all(), [
            'id'   => 'required',
            'name' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(["code" => 500, 'message' => $validator->errors()->all()]);
        }

        try {
            Compositions::where('id',$request->id)->update(['name'=> $request->name]);
            return response()->json(["code" => 200, 'message' => 'Successfully updated']);

        } catch (\Throwable $th) {
            return response()->json(["code" => 500, 'message' => $th->getMessage()]);
        }
        $c = $compositions->find($id);
        if ($c) {
            $c->fill($request->all());
            $c->save();
        }

        if ($request->ajax()) {
            return response()->json(["code" => 200, "data" => []]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Compositions  $compositions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compositions $compositions, $id)
    {
        //
        $compositions->find($id)->delete();

        return redirect()->back();
    }

    public function usedProducts(Compositions $compositions, Request $request, $id)
    {
        $compositions = $compositions->find($id);

        if ($compositions) {
            // check the type and then
            $name     = '"' . $compositions->name . '"';
            $products = \App\ScrapedProducts::where("properties", "like", '%' . $name . '%')->latest()->limit(5)->get();

            $view = (string) view("compositions.preview-products", compact('products'));
            return response()->json(["code" => 200, "html" => $view]);
        }

        return response()->json(["code" => 200, "html" => ""]);

    }

    public function affectedProduct(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!empty($from) && !empty($to)) {
            // check the type and then
            $q     = '"' . $from . '"';
            $total = \App\ScrapedProducts::where("properties", "like", '%' . $q . '%')
                ->join("products as p", "p.sku", "scraped_products.sku")
                ->where("p.composition", "")
                ->groupBy("p.id")
                ->get()->count();

            $view = (string) view("compositions.partials.affected-products", compact('total', 'from', 'to'));

            return response()->json(["code" => 200, "html" => $view]);
        }
    }

    public function updateComposition(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;
        $userId = \Auth::user()->id;

        $updateWithProduct = $request->with_product;
        if ($updateWithProduct == "yes") {
            \App\Jobs\UpdateProductCompositionFromErp::dispatch([
                "from"    => $from,
                "to"      => $to,
                "user_id" => $userId,
            ])->onQueue("supplier_products");
        }

        $c = Compositions::where("name", $from)->get();
        if (!$c->isEmpty()) {
            foreach($c as $b) {
                //once it is save let's store to the user updated attributes table as well
                $userUpdatedAttributeHistory = \App\UserUpdatedAttributeHistory::create([
                    'old_value'      => $b->replace_with,
                    'new_value'      => $to,
                    'attribute_name' => 'compositions',
                    'attribute_id'   => $b->id,
                    'user_id'        => $userId,
                ]);

                $b->replace_with = $to;
                $b->save();
            }
        }

        return response()->json(["code" => 200, "message" => "Your request has been pushed successfully"]);
    }

    public function updateMultipleComposition(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        if (!empty($from) && is_array($from)) {
            foreach ($from as $f) {
                $c = Compositions::find($f);
                if ($c) {
                    \App\Jobs\UpdateProductCompositionFromErp::dispatch([
                        "from"    => $c->name,
                        "to"      => $to,
                        "user_id" => \Auth::user()->id,
                    ])->onQueue("supplier_products");

                    //once it is save let's store to the user updated attributes table as well
                    $userUpdatedAttributeHistory = \App\UserUpdatedAttributeHistory::create([
                        'old_value'      => $c->replace_with,
                        'new_value'      => $to,
                        'attribute_name' => 'compositions',
                        'attribute_id'   => $c->id,
                        'user_id'        => $userId,
                    ]);

                    $c->replace_with = $to;
                    $c->save();
                }
            }
        }

        return response()->json(["code" => 200, "message" => "Your request has been pushed successfully"]);

    }

    public function updateAllComposition( Request $request ){

        $from = $request->from;
        $to   = $request->to;

        if (!empty($from) && is_array($from)) {
            foreach ($from as $key => $f  ) {
                
                if ( empty( $to[$key] ) ) {
                    continue;
                }

                $c = Compositions::find($f);
                if ($c) {
                    \App\Jobs\UpdateProductCompositionFromErp::dispatch([
                        "from"    => $c->name,
                        "to"      => $to[$key],
                        "user_id" => \Auth::user()->id,
                    ])->onQueue("supplier_products");

                    //once it is save let's store to the user updated attributes table as well
                    $userUpdatedAttributeHistory = \App\UserUpdatedAttributeHistory::create([
                        'old_value'      => $c->replace_with,
                        'new_value'      => $to[$key],
                        'attribute_name' => 'compositions',
                        'attribute_id'   => $c->id,
                        'user_id'        => \Auth::user()->id,
                    ]);

                    $c->replace_with = $to[$key];
                    $c->save();
                }
            }
        }

        return response()->json(["code" => 200, "message" => "Your request has been pushed successfully"]);
    }

    public function replaceComposition(Request $request)
    {
        ini_set("memory_limit","-1");
        set_time_limit(0);
        
        $from = $request->name;
        $to   = $request->replace_with;
        if (!empty($from) && !empty($to)) {

            // remove here the word
            $compositionList = \App\Compositions::where("replace_with","like","%".$from."%")->orWhere("name","like","%".$from."%")->get();
            if(!$compositionList->isEmpty()) {
                foreach($compositionList as $cl) {
                    $cl->replace_with = str_ireplace($from, $to, $cl->replace_with);
                    $cl->name = str_ireplace($from, $to, $cl->name);
                    $cl->save();
                }
            }

            $products = \App\Product::where('composition', 'LIKE', '%' . $from . '%')->get();
            $user = \Auth::user();

            if ($products) {
                foreach ($products as $product) {
                    $composition          = $product->composition;
                    $replaceWords         = [];
                    $replaceWords[]       = ucwords($from);
                    $replaceWords[]       = strtoupper($from);
                    $replaceWords[]       = strtolower($from);
                    $newComposition       = str_replace($replaceWords, $to, $composition);
                    $product->composition = $newComposition;
                    $product->update();
                }

                $c = Compositions::where("name", $from)->first();
                if ($c) {

                    //once it is save let's store to the user updated attributes table as well
                    $userUpdatedAttributeHistory = \App\UserUpdatedAttributeHistory::create([
                        'old_value'      => $c->replace_with,
                        'new_value'      => $to,
                        'attribute_name' => 'compositions',
                        'attribute_id'   => $c->id,
                        'user_id'        => $user->id,
                    ]);

                    $c->replace_with = $to;
                    $c->save();
                } else {
                    if (!empty($from)) {
                        $comp               = new Compositions();
                        $comp->name         = $from;
                        $comp->replace_with = $to;
                        $comp->save();

                        //once it is save let's store to the user updated attributes table as well
                        $userUpdatedAttributeHistory = \App\UserUpdatedAttributeHistory::create([
                            'old_value'      => null,
                            'new_value'      => $to,
                            'attribute_name' => 'compositions',
                            'attribute_id'   => $comp->id,
                            'user_id'        => $user->id,
                        ]);
                    }
                }
            }
        }
        return redirect()->back();
    }

    public function history(Request $request , $id)
    {
        $records = \App\UserUpdatedAttributeHistory::where("attribute_id",$id)->where("attribute_name","compositions")->latest()->get();
        return view("compositions.partials.show-update-history",compact('records'));
    }

    public function deleteUnused()
    {
        \Artisan::call("delete-composition:with-no-products");
        return redirect()->back()->with('success', 'Your request has been finished successfully!');;
    }
}
