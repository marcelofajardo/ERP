<?php

namespace App\Http\Controllers\Products;

use App\ListingHistory;
use App\Product;
use App\ScrapedProducts;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class ManualCroppingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('manual_crop', 1)
            ->where('stock', '>=', 1)
            ->where('is_crop_approved', 0)
            ->where('is_manual_cropped', 0)
            ->whereIn('id', DB::table('user_manual_crop')->where('user_id', Auth::id())->pluck('product_id')->toArray())
            ->get();

        return view('products.crop.manual.index', compact('products'));
    }

    public function assignProductsToUser() {
        $currentUser = Auth::user();

        $reservedProductIds = DB::table('user_manual_crop')->pluck('product_id')->toArray();
        $products = Product::whereNotIn('id', $reservedProductIds)
            ->where('manual_crop', 1)
            ->where('is_crop-approved', 0)
            ->where('is_manual_cropped', 0)
            ->take(25)
            ->get();

        if ($products->count() === 0) {
            return redirect()->back()->with('message', 'There are no products to be assigned!');
        }

        $currentUser->manualCropProducts()->attach($products);

        return redirect()->back()->with('message', $products->count() .' new products assigned successfully!');

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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->action('Products\ManualCroppingController@index')->with('message', 'The product you were trying to open does not exist anymore.');
        }

        $originalMediaCount = 0;

        $medias = $product->getMedia(config('constants.media_tags'));
        foreach ($medias as $media) {
            if (stripos(strtoupper($media->filename), 'CROPPED') === false) {
                $originalMediaCount++;
            }
        }

        $references = ScrapedProducts::where('sku', $product->sku)->pluck('url', 'website');

        return view('products.crop.manual.show', compact('product','references', 'originalMediaCount'));

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
            'images' => 'required'
        ]);

        $product = Product::find($id);
        $files = $request->allFiles();

	    if ($files !== []) {
            $this->deleteCroppedImages($product);
            foreach ($files['images'] as $file) {
                $media = MediaUploader::fromSource($file)
                                        ->useFilename(uniqid('cropped_', true))
                                        ->toDirectory('product/'.floor($product->id / config('constants.image_per_folder')))
                                        ->upload();
                $product->attachMedia($media, config('constants.media_tags'));
            }
        }

	    $product->is_crop_rejected = 0;
	    $product->cropped_at = Carbon::now()->toDateTimeString();
	    $product->manual_cropped_at = Carbon::now()->toDateTimeString();
	    $product->is_image_processed = 1;
	    $product->is_manual_cropped = 1;
	    $product->manual_crop = 1;
	    $product->manual_cropped_by = Auth::id();
	    $product->save();

        $e = new ListingHistory();
        $e->user_id = Auth::user()->id;
        $e->product_id = $product->id;
        $e->content = ['action' => 'MANUAL_CROPPED', 'page' => 'Manual Crop Page'];
        $e->action = 'MANUAL_CROPPED';
        $e->save();

        $product = Product::where('manual_crop', 1)
            ->where('is_crop_approved', 0)
            ->where('is_manual_cropped', 0)
            ->whereIn('id', DB::table('user_manual_crop')->where('user_id', Auth::id())->pluck('product_id')->toArray())
            ->first();

        if (!$product) {
            return redirect()->action('Products\ManualCroppingController@index')->with('message', 'There are no assigned products available for cropping anymore.');
        }

        return redirect()->action('Products\ManualCroppingController@show', $product->id)->with('message', 'The previous product has been sent for approval!');

    }

    private function deleteCroppedImages($product) {
        if ($product->hasMedia(config('constants.media_tags'))) {
            foreach ($product->getMedia(config('constants.media_tags')) as $key=>$image) {
                if (stripos(strtoupper($image->filename), 'CROPPED') !== false) {
                    $image_path = $image->getAbsolutePath();

                    if (File::exists($image_path)) {
                        try {
                            File::delete($image_path);
                        } catch (\Exception $exception) {

                        }
                    }

                    $image->delete();
                }
            }

            $product->is_image_processed = 1;
            $product->save();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
