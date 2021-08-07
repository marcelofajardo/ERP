<?php

namespace App\Http\Controllers;

use App\MessageQueue;
use App\Product;
use App\Task;
use App\Helpers;
use App\User;
use App\Instruction;
use App\InstructionCategory;
use App\ReplyCategory;
use App\DeveloperTask;
use App\DailyActivity;
use App\Order;
use App\Purchase;
use App\Email;
use App\Supplier;
use App\Review;
use App\PushNotification;
use App\CronJob;
use App\UserProduct;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CroppedImageReference;
use App\Helpers\StatusHelper;
use Cache;
use App\Vendor;
use App\ChatMessage;
use App\Customer;


class MasterControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start = $request->range_start ?  "$request->range_start 00:00" : Carbon::now()->subDay()->format('Y-m-d 00:00');
        $end = $request->range_end ? "$request->range_end 23:59" : Carbon::now()->subDay()->format('Y-m-d 23:59');

        $cropReference = Cache::get( 'cropped_image_references' );
        $cropReference = !empty($cropReference) ? $cropReference : 0;

        $pendingCropReferenceProducts = Cache::get( 'pending_crop_reference' );
        $pendingCropReferenceProducts = !empty($pendingCropReferenceProducts) ? $pendingCropReferenceProducts : 0;

        $cropReferenceWeekCount = Cache::get( 'crop_reference_week_count' );
        $cropReferenceWeekCount = !empty($cropReferenceWeekCount) ? $cropReferenceWeekCount : 0;

        $cropReferenceDailyCount = Cache::get( 'crop_reference_daily_count' );
        $cropReferenceDailyCount = !empty($cropReferenceDailyCount) ? $cropReferenceDailyCount : 0;

        $pendingCropReferenceCategory = Cache::get( 'pending_crop_category' );
        $pendingCropReferenceCategory = !empty($pendingCropReferenceCategory) ? $pendingCropReferenceCategory : 0;

        $productStats = Cache::get( 'status_count' );
        $productStats = !empty($productStats) ? $productStats : [];

        $resultScrapedProductsInStock = Cache::get( 'result_scraped_product_in_stock' );
        $resultScrapedProductsInStock = !empty($resultScrapedProductsInStock) ? $resultScrapedProductsInStock : [];

        $customers = Cache::get( 'result_customer_chat' );
        $customers = !empty($customers) ? $customers : [];

        //Getting Supplier Chat  
        $suppliers = Cache::get( 'result_supplier_chat' );
        $suppliers = !empty($suppliers) ? $suppliers : [];

        //Getting Vendor Chat  
        $vendors = Cache::get( 'result_vendor_chat' );
        $vendors = !empty($vendors) ? $vendors : [];

        $reply_categories = Cache::get( 'reply_categories' );
        $reply_categories = !empty($reply_categories) ? $reply_categories : [];

        $vendorReplier = Cache::get( 'vendorReplier' );
        $vendorReplier = !empty($vendorReplier) ? $vendorReplier : [];

        $supplierReplier = Cache::get( 'supplierReplier' );
        $supplierReplier = !empty($supplierReplier) ? $supplierReplier : [];

        $cronLastErrors = Cache::get( 'cronLastErrors' );
        $cronLastErrors = !empty($cronLastErrors) ? $cronLastErrors : [];

        $latestRemarks = Cache::get('latestScrapRemarks');
        $latestRemarks = !empty($latestRemarks) ? $latestRemarks : [];

        $todaytaskhistory = Cache::get('todaytaskhistory');
        $todaytaskhistory = !empty($todaytaskhistory) ? $todaytaskhistory : [];

        $hubstaff_notifications = Cache::get('hubstafftrackingnotiification');
        $hubstaff_notifications = !empty($hubstaff_notifications) ? $hubstaff_notifications : [];

        // For ajax
        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('mastercontrol.partials.data', 
                 [
                    'start' => $start, 
                    'end' => $end , 
                    'cropReference' => $cropReference,
                    'pendingCropReferenceProducts' => $pendingCropReferenceProducts , 
                    'pendingCropReferenceCategory' => $pendingCropReferenceCategory,
                    'productStats' => $productStats,
                    'resultScrapedProductsInStock' => $resultScrapedProductsInStock,
                    'cropReferenceWeekCount' => $cropReferenceWeekCount,
                    'cropReferenceDailyCount' => $cropReferenceDailyCount,
                    'chatSuppliers' => $suppliers,
                    'chatCustomers' => $customers,
                    'chatVendors' => $vendors,
                    'reply_categories' => $reply_categories,
                    'vendorReplier' => $vendorReplier,
                    'supplierReplier' => $supplierReplier,
                    'cronLastErrors' => $cronLastErrors,
                    'latestRemarks' => $latestRemarks,
                    'todaytaskhistory' => $todaytaskhistory,
                    'hubstaffNotifications' => $hubstaff_notifications,

                ])->render()
            ], 200);
        }

       return view('mastercontrol.index', [
          'start' => $start, 
          'end' => $end , 
          'cropReference' => $cropReference,
          'pendingCropReferenceProducts' => $pendingCropReferenceProducts , 
          'pendingCropReferenceCategory' => $pendingCropReferenceCategory,
          'productStats' => $productStats,
          'resultScrapedProductsInStock' => $resultScrapedProductsInStock,
          'cropReferenceWeekCount' => $cropReferenceWeekCount,
          'cropReferenceDailyCount' => $cropReferenceDailyCount,
          'chatSuppliers' => $suppliers,
          'chatCustomers' => $customers,
          'chatVendors' => $vendors,
          'reply_categories' => $reply_categories,
          'vendorReplier' => $vendorReplier,
          'supplierReplier' => $supplierReplier,
          'cronLastErrors' => $cronLastErrors,
          'latestRemarks' => $latestRemarks,
          'todaytaskhistory' => $todaytaskhistory,
          'hubstaffNotifications' => $hubstaff_notifications,
      ]);
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

    public function clearAlert(Request $request)
    {
      PushNotification::where('model_type', 'MasterControl')->delete();

      return redirect()->route('mastercontrol.index');
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
        //
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
        //
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
