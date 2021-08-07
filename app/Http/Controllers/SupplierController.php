<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\ChatMessage;
use App\Email;
use App\Helpers;
use App\Mail\PurchaseEmail;
use App\Marketing\WhatsappConfig;
use App\Product;
use App\ProductQuicksellGroup;
use App\QuickSellGroup;
use App\ReadOnly\SoloNumbers;
use App\ReplyCategory;
use App\Setting;
use App\Supplier;
use App\SupplierBrandCount;
use App\SupplierBrandCountHistory;
use App\SupplierCategory;
use App\SupplierCategoryCount;
use App\SupplierPriceRange;
use App\SupplierSize;
use App\SupplierStatus;
use App\SupplierSubCategory;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use seo2websites\ErpExcelImporter\ErpExcelImporter;
use Validator;

class SupplierController extends Controller
{

    const DEFAULT_FOR = 3; //For Supplier
    /**
     * Add/Edit Remainder functionality
     */
    public function updateReminder(Request $request)
    {
        $supplier                   = Supplier::find($request->get('supplier_id'));
        $supplier->frequency        = $request->get('frequency');
        $supplier->reminder_message = $request->get('message');
        $supplier->save();

        return response()->json([
            'success',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $suppliers = Supplier::with('agents')->paginate(Setting::get('pagination'));
        $solo_numbers    = (new SoloNumbers)->all();
        $term            = $request->term ?? '';
        $type            = $request->type ?? '';
        $supplier_filter = $request->supplier_filter ?? '';
        $scrappertype    = $request->scrappertype ?? '';
        //$status = $request->status ?? '';
        $supplier_category_id    = $request->supplier_category_id ?? '';
        $supplier_status_id      = $request->supplier_status_id ?? '';
        $supplier_price_range_id = $request->supplier_price_range_id ?? '';
        $updated_by              = $request->updated_by ?? '';
        $source                  = $request->get('source') ?? '';
        $typeWhereClause         = '';

        if ($type != '' && $type == 'has_error') {
            $typeWhereClause = ' AND has_error = 1';
        }
        if ($type != '' && $type == 'not_updated') {
            $typeWhereClause = ' AND is_updated = 0';
        }
        if ($type != '' && $type == 'updated') {
            $typeWhereClause = ' AND is_updated = 1';
        }

        /*if ( $status != '' ) {
        $typeWhereClause .= ' AND status=1';
        }*/

        if ($supplier_price_range_id != '') {
            $typeWhereClause .= ' AND supplier_price_range_id=' . $supplier_price_range_id;
        }

        if ($supplier_category_id != '') {
            $typeWhereClause .= ' AND supplier_category_id=' . $supplier_category_id;
        }
        if ($supplier_status_id != '') {
            $typeWhereClause .= ' AND supplier_status_id=' . $supplier_status_id;
        }
        if ($scrappertype != '') {
            $typeWhereClause .= ' AND suppliers.scrapper=' . $scrappertype;
        }
        if ($updated_by != '') {
            $typeWhereClause .= ' AND updated_by=' . $updated_by;
        }

        if ($request->status) {
            $typeWhereClause .= ' AND suppliers.status=' . $request->status;
        }

        if ($supplier_filter) {
            $typeWhereClause .= ' AND suppliers.id IN (' . implode(",", $supplier_filter) . ')';
        }
        if (!empty($request->brand)) {
            $brands     = array();
            $references = array();
            foreach ($request->brand as $key => $value) {
                $selecteBrandById = Brand::where('id', $value)->get()->first();
                if (!empty($selecteBrandById->name)) {
                    array_push($brands, $selecteBrandById->name);
                }
                if (!empty($selecteBrandById->references)) {
                    array_push($references, $selecteBrandById->references);
                }
            }
            $filterBrands     = implode("|", $brands);
            $filterReferences = str_replace(";", "|", implode("|", $references));
            if (!empty($filterBrands)) {
                $typeWhereClause .= ' AND (brands RLIKE "' . $filterBrands . '"';
                $typeWhereClause .= 'OR scraped_brands RLIKE "' . $filterBrands . '"';
                $typeWhereClause .= 'OR scraped_brands_raw RLIKE "' . $filterBrands . '")';
            }
            if (!empty($filterReferences)) {
                $typeWhereClause .= ' OR (brands RLIKE "' . $filterReferences . '"';
                $typeWhereClause .= 'OR scraped_brands RLIKE "' . $filterReferences . '"';
                $typeWhereClause .= 'OR scraped_brands_raw RLIKE "' . $filterReferences . '")';
            }

        } else {

            if (!empty($request->scrapedBrand)) {
                $scrapedBrands = implode("|", $request->scrapedBrand);
                $typeWhereClause .= ' AND (brands RLIKE "' . $scrapedBrands . '"';
                $typeWhereClause .= 'OR scraped_brands RLIKE "' . $scrapedBrands . '"';
                $typeWhereClause .= 'OR scraped_brands_raw RLIKE "' . $scrapedBrands . '")';
            }
        }

        $runQuery = 0;
        // if(count($userCategoryPermissionId) && !auth()->user()->isAdmin()) {
        //     $userCategoryPermissionId1 = implode(',', $userCategoryPermissionId);
        //     $typeWhereClause .= "AND suppliers.supplier_category_id IN ($userCategoryPermissionId1)";
        //     $runQuery = 1;
        // } else {
        //     if(auth()->user()->isAdmin()) {
        //         $runQuery = 1;
        //     }
        // }

        if (!auth()->user()->isAdmin()) {
            $userCategoryPermissionId  = auth()->user()->supplierCategoryPermission->pluck('id')->toArray() + [0];
            $userCategoryPermissionId1 = implode(',', $userCategoryPermissionId);
            $typeWhereClause .= "AND suppliers.supplier_category_id IN ($userCategoryPermissionId1)";
            $runQuery = 1;
        } else {
            $runQuery = 1;
        }
        $suppliers = [];

        if ($runQuery) {
            $suppliers = DB::select('
                                    SELECT suppliers.frequency,suppliers.supplier_sub_category_id,suppliers.supplier_status_id,suppliers.supplier_size_id,suppliers.scrapper, suppliers.reminder_message, suppliers.id, suppliers.is_blocked , suppliers.supplier, suppliers.phone, suppliers.source,suppliers.supplier_price_range_id, suppliers.brands, suppliers.email, suppliers.default_email, suppliers.address, suppliers.social_handle, suppliers.gst, suppliers.is_flagged, suppliers.has_error, suppliers.whatsapp_number, suppliers.status, sc.scraper_name, suppliers.supplier_category_id, suppliers.supplier_status_id, sc.inventory_lifetime,suppliers.created_at,suppliers.updated_at,suppliers.updated_by,u.name as updated_by_name, suppliers.scraped_brands_raw,suppliers.language,
                                    suppliers.est_delivery_time,suppliers.size_system_id,suppliers.priority,
                  (SELECT mm1.message FROM chat_messages mm1 WHERE mm1.id = message_id) as message,
                  (SELECT mm2.created_at FROM chat_messages mm2 WHERE mm2.id = message_id) as message_created_at,
                  (SELECT mm3.id FROM purchases mm3 WHERE mm3.id = purchase_id) as purchase_id,
                  (SELECT mm4.created_at FROM purchases mm4 WHERE mm4.id = purchase_id) as purchase_created_at,
                  (SELECT mm5.message FROM emails mm5 WHERE mm5.id = email_id) as email_message,
                  (SELECT mm6.seen FROM emails mm6 WHERE mm6.id = email_id) as email_seen,
                  (SELECT mm7.created_at FROM emails mm7 WHERE mm7.id = email_id) as email_created_at,
                  CASE WHEN IFNULL(message_created_at, "1990-01-01 00:00") > IFNULL(email_created_at, "1990-01-01 00:00") THEN "message" WHEN IFNULL(message_created_at, "1990-01-01 00:00") < IFNULL(email_created_at, "1990-01-01 00:00") THEN "email" ELSE "none" END as last_type,
                  CASE WHEN IFNULL(message_created_at, "1990-01-01 00:00") > IFNULL(email_created_at, "1990-01-01 00:00") THEN message_created_at WHEN IFNULL(message_created_at, "1990-01-01 00:00") < IFNULL(email_created_at, "1990-01-01 00:00") THEN email_created_at ELSE "1990-01-01 00:00" END as last_communicated_at

                  FROM (SELECT * FROM suppliers

                  LEFT JOIN (SELECT MAX(id) as message_id, supplier_id, message, MAX(created_at) as message_created_at FROM chat_messages GROUP BY supplier_id ORDER BY created_at DESC) AS chat_messages
                  ON suppliers.id = chat_messages.supplier_id

                  LEFT JOIN (SELECT MAX(id) as purchase_id, supplier_id as purchase_supplier_id, created_at AS purchase_created_at FROM purchases GROUP BY purchase_supplier_id ORDER BY created_at DESC) AS purchases
                  ON suppliers.id = purchases.purchase_supplier_id

                  LEFT JOIN (SELECT MAX(id) as email_id, model_id as email_model_id, MAX(created_at) AS email_created_at FROM emails WHERE model_type LIKE "%Supplier%" OR "%Purchase%" GROUP BY model_id ORDER BY created_at DESC) AS emails
                  ON suppliers.id = emails.email_model_id)

                  AS suppliers
                  left join scrapers as sc on sc.supplier_id = suppliers.id
                  left join users as u on u.id = suppliers.updated_by
                  WHERE (

                  source LIKE "%' . $source . '%" AND
                  (sc.parent_id IS NULL AND
                  (supplier LIKE "%' . $term . '%" OR
                  suppliers.phone LIKE "%' . $term . '%" OR
                  suppliers.email LIKE "%' . $term . '%" OR
                  suppliers.address LIKE "%' . $term . '%" OR
                  suppliers.social_handle LIKE "%' . $term . '%" OR
                  sc.scraper_name LIKE "%' . $term . '%" OR
                  brands LIKE "%' . $term . '%" OR
                   suppliers.id IN (SELECT model_id FROM agents WHERE model_type LIKE "%Supplier%" AND (name LIKE "%' . $term . '%" OR phone LIKE "%' . $term . '%" OR email LIKE "%' . $term . '%")))))' . $typeWhereClause . '
                  ORDER BY last_communicated_at DESC, status DESC
              ');
        }

        $suppliers_all = null;

        if($request->supplier_filter){

            $suppliers_all = Supplier::where(function ($query) {
                $query->whereNotNull('email')->orWhereNotNull('default_email');
            })->whereIn('id',$request->supplier_filter)->get();
        }
        // $suppliers_all = Supplier::whererIn([2712,2713,2715])->get();  
        // print_r($suppliers_all);
// dd($suppliers_all);
        $currentPage  = LengthAwarePaginator::resolveCurrentPage();
        $perPage      = Setting::get('pagination');
        $currentItems = array_slice($suppliers, $perPage * ($currentPage - 1), $perPage);

        $supplierscnt = count($suppliers);
        $suppliers    = new LengthAwarePaginator($currentItems, count($suppliers), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $suppliercategory    = SupplierCategory::pluck('name', 'id')->toArray();
        $suppliersubcategory = SupplierSubCategory::pluck('name', 'id')->toArray();
        $supplierstatus      = SupplierStatus::pluck('name', 'id')->toArray();
        $suppliersize        = SupplierSize::pluck('size', 'id')->toArray();

        //SELECT supplier_status_id, COUNT(*) AS number_of_products FROM suppliers WHERE supplier_status_id IN (SELECT id from supplier_status) GROUP BY supplier_status_id
        $statistics = DB::select('SELECT supplier_status_id, ss.name, COUNT(*) AS number_of_products FROM suppliers s LEFT join supplier_status ss on ss.id = s.supplier_status_id WHERE supplier_status_id IN (SELECT id from supplier_status) GROUP BY supplier_status_id');

        // $brands           = Brand::whereNotNull('magento_id')->get()->all();
        // $brands= null;
        $scrapedBrandsRaw = Supplier::whereNotNull('scraped_brands_raw')->get()->all();
        $rawBrands        = array();
        foreach ($scrapedBrandsRaw as $key => $value) {
            array_push($rawBrands, array_unique(array_filter(array_column(json_decode($value->scraped_brands_raw, true), 'name'))));
            array_push($rawBrands, array_unique(array_filter(explode(",", $value->scraped_brands))));
        }
        $scrapedBrands = array_unique(array_reduce($rawBrands, 'array_merge', []));



        $data          = Setting::where('type', "ScrapeBrandsRaw")->get()->first();
        // dd($data);
        if (!empty($data)) {
            $selectedBrands = json_decode($data->val, true);
        } else {
            $selectedBrands = [];
        }

        // dd($);
        $whatsappConfigs = WhatsappConfig::where('provider', 'LIKE', '%Chat-API%')->get();

        //Get All Product Supplier
        $allSupplierProduct = []; //DB::select('SELECT ps.product_id, ps.supplier_id, pp.name, ss.supplier FROM product_suppliers ps JOIN suppliers ss on ps.supplier_id = ss.id JOIN products pp on ps.product_id = pp.id');

        //Get All supplier price range
        $allSupplierPriceRanges = SupplierPriceRange::select("supplier_price_range.*", DB::raw("CONCAT(supplier_price_range.price_from,'-',supplier_price_range.price_to) as full_range"))->get()->toArray();
        /* echo "<pre>";
        print_r($allSupplierPriceRanges);
        exit; */
        $reply_categories = ReplyCategory::with('supplier')->get();
        $sizeSystem       = \App\SystemSize::pluck('name', 'id')->toArray();


        return view('suppliers.index', [
            'suppliers'              => $suppliers,
            'suppliers_all'          => $suppliers_all,
            'solo_numbers'           => $solo_numbers,
            'term'                   => $term,
            'type'                   => $type,
            'scrappertype'           => $scrappertype,
            'supplier_filter'        => $supplier_filter,
            'source'                 => $source,
            'suppliercategory'       => $suppliercategory,
            'suppliersubcategory'    => $suppliersubcategory,
            'supplierstatus'         => $supplierstatus,
            'suppliersize'           => $suppliersize,
            'supplier_category_id'   => $supplier_category_id,
            'supplier_status_id'     => $supplier_status_id,
            'count'                  => $supplierscnt,
            'statistics'             => $statistics,
            'total'                  => 0,
            // 'brands'                 => $brands,
            'scrapedBrands'          => $scrapedBrands,
            'selectedBrands'         => $selectedBrands,
            'whatsappConfigs'        => $whatsappConfigs,
            'allSupplierProduct'     => $allSupplierProduct,
            'allSupplierPriceRanges' => $allSupplierPriceRanges,
            'reply_categories'       => $reply_categories,
            'sizeSystem'             => $sizeSystem,
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            //'supplier_category_id' => 'required|string|max:255',
            'supplier'           => 'required|string|unique:suppliers|max:255',
            'address'            => 'sometimes|nullable|string',
            'phone'              => 'sometimes|nullable|numeric',
            'default_phone'      => 'sometimes|nullable|numeric',
            'whatsapp_number'    => 'sometimes|nullable|numeric',
            'email'              => 'sometimes|nullable|email',
            'social_handle'      => 'sometimes|nullable',
            'scraper_name'       => 'sometimes|nullable',
            'inventory_lifetime' => 'sometimes|nullable',
            'gst'                => 'sometimes|nullable|max:255',
            //'supplier_status_id' => 'required'
        ]);

        $data                  = $request->except('_token');
        $data['default_phone'] = $request->phone ?? '';
        $data['default_email'] = $request->email ?? '';

        $source = $request->get("source", "");

        if (!empty($source)) {
            $data["supplier_status_id"] = 0;
        }

        //get default whatsapp number for vendor from whatsapp config
        if (empty($data["whatsapp_number"])) {
            $task_info = DB::table('whatsapp_configs')
                ->select('*')
                ->whereRaw("find_in_set(" . self::DEFAULT_FOR . ",default_for)")
                ->first();

      if($task_info){
			   $data["whatsapp_number"] = $task_info->number;
      }
		
		}
        $scrapper_name = preg_replace("/\s+/", "", $request->supplier);
        $supplier = Supplier::where('supplier',$scrapper_name)->get();
        
        // if(empty($supplier)){
        if($supplier->isEmpty()){   
          $supplier = Supplier::create($data);
          if ($supplier->id > 0) {
              $scraper = \App\Scraper::create([
                  "supplier_id" => $supplier->id,
                  "scraper_name" => $request->get("scraper_name", $scrapper_name),
                  "inventory_lifetime" => $request->get("inventory_lifetime", ""),
              ]);
          }
          $supplier->scrapper = $scraper->id;
          $supplier->save();
        }else{
          $scraper = \App\Scraper::where('scraper_name',$scrapper_name)->get();
          if(empty($scraper)){
            $scraper = \App\Scraper::create([
                  "supplier_id" => $supplier->id,
                  "scraper_name" => $request->get("scraper_name", $scrapper_name),
                  "inventory_lifetime" => $request->get("inventory_lifetime", ""),
              ]);
            $supplier->scrapper = $scraper->id;
            $supplier->save();
          }else{
            $supplier->scrapper = $scraper->id;
            $supplier->save();
          }
        }

        if (!empty($source)) {
            return redirect()->back()->withSuccess('You have successfully saved a supplier!');
        }

        return redirect()->route('supplier.index')->withSuccess('You have successfully saved a supplier!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $supplier               = Supplier::find($id);
        $user                   = User::where('id', $supplier->updated_by)->first();
        $suppliers              = Supplier::select(['id', 'supplier'])->where('supplier_status_id', 1)->orderby('supplier', 'asc')->get();
        $reply_categories       = ReplyCategory::all();
        $users_array            = Helpers::getUserArray(User::all());
        $emails                 = [];
        $suppliercategory       = SupplierCategory::pluck('name', 'id');
        $supplierstatus         = SupplierStatus::pluck('name', 'id');
        $new_category_selection = Category::attr(['name' => 'category', 'class' => 'form-control', 'id' => 'category'])->renderAsDropdown();
        $locations              = \App\ProductLocation::pluck("name", "name");

        $category_selection = Category::attr(['name' => 'category', 'class' => 'form-control', 'id' => 'category_selection'])
            ->renderAsDropdown();

        return view('suppliers.show', [
            'supplier'               => $supplier,
            'reply_categories'       => $reply_categories,
            'users_array'            => $users_array,
            'emails'                 => $emails,
            'suppliercategory'       => $suppliercategory,
            'supplierstatus'         => $supplierstatus,
            'suppliers'              => $suppliers,
            'new_category_selection' => $new_category_selection,
            'locations'              => $locations,
            'category_selection'     => $category_selection,
            'user'                   => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            //'supplier_category_id'        => 'required|string|max:255',
            'supplier'           => 'required|string|max:255',
            'address'            => 'sometimes|nullable|string',
            'phone'              => 'sometimes|nullable|numeric',
            'default_phone'      => 'sometimes|nullable|numeric',
            'whatsapp_number'    => 'sometimes|nullable|numeric',
            'email'              => 'sometimes|nullable|email',
            'default_email'      => 'sometimes|nullable|email',
            'social_handle'      => 'sometimes|nullable',
            'scraper_name'       => 'sometimes|nullable',
            'inventory_lifetime' => 'sometimes|nullable',
            'gst'                => 'sometimes|nullable|max:255',
            //'supplier_status_id' => 'required'
            //'status' => 'required'
        ]);

        $data                  = $request->except('_token');
        $data['default_phone'] = $request->default_phone != '' ? $request->default_phone : $request->phone;
        $data['default_email'] = $request->default_email != '' ? $request->default_email : $request->email;
        $data['is_updated']    = 1;
        Supplier::find($id)->update($data);

        $scrapers     = \App\Scraper::where('supplier_id', $id)->get();
        $multiscraper = explode(",", $request->get("scraper_name", ""));
        $multiscraper = array_map('strtolower', $multiscraper);
        if (!$scrapers->isEmpty()) {
            foreach ($scrapers as $scr) {
                if (!in_array(strtolower($scr->scraper_name), $multiscraper)) {
                    $scr->delete();
                }
            }
        }

        if (!empty($multiscraper)) {
            foreach ($multiscraper as $multiscr) {
                $scraper = \App\Scraper::where('supplier_id', $id)->where('scraper_name', $multiscr)->first();
                if ($scraper) {
                    $scraper->inventory_lifetime = $request->get("inventory_lifetime", "");
                } else {
                    $scraper                     = new \App\Scraper;
                    $scraper->supplier_id        = $id;
                    $scraper->inventory_lifetime = $request->get("inventory_lifetime", "");
                    $scraper->scraper_name       = $multiscr;
                }
                $scraper->save();
            }
        }

        return redirect()->back()->withSuccess('You have successfully updated a supplier!');
    }

    /**
     * Ajax Load More message method
     */
    public function loadMoreMessages(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        $chat_messages = $supplier->whatsapps()->skip(1)->take(3)->pluck('message');

        return response()->json([
            'messages' => $chat_messages,
        ]);
    }

    /**
     * Ajax Flag Update method
     */
    public function flag(Request $request)
    {
        $supplier = Supplier::find($request->supplier_id);

        if ($supplier->is_flagged == 0) {
            $supplier->is_flagged = 1;
        } else {
            $supplier->is_flagged = 0;
        }

        $supplier->save();

        return response()->json(['is_flagged' => $supplier->is_flagged]);
    }

    /**
     * Send Bulk email to supplier
     */
    public function sendEmailBulk(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:3|max:255',
            'message' => 'required',
            'cc.*'    => 'nullable|email',
            'bcc.*'   => 'nullable|email',
        ]);

        if ($request->suppliers) {
            $suppliers = Supplier::whereIn('id', $request->suppliers)->where(function ($query) {
                $query->whereNotNull('default_email')->orWhereNotNull('email');
            })->get();
        } else {
            if ($request->not_received != 'on' && $request->received != 'on') {
                return redirect()->route('supplier.index')->withErrors(['Please select either suppliers or option']);
            }
        }

        if ($request->not_received == 'on') {
            $suppliers = Supplier::doesnthave('emails')->where(function ($query) {
                $query->whereNotNull('default_email')->orWhereNotNull('email');
            })->get();
        }

        if ($request->received == 'on') {
            $suppliers = Supplier::whereDoesntHave('emails', function ($query) {
                $query->where('type', 'incoming');
            })->where(function ($query) {
                $query->whereNotNull('default_email')->orWhereNotNull('email');
            })->where('has_error', 0)->get();
        }

        // foreach ($suppliers as $supplier) {
        //   if ($supplier->email == '' && $supplier->default_email == '') {
        //     dump($supplier->id);
        //   }
        // }
        // dd('stop');

        // $first_email = '';
        // $bcc_emails = [];
        // foreach ($suppliers as $key => $supplier) {
        //   if ($key == 0) {
        //     $first_email = $supplier->default_email ?? $supplier->email;
        //   } else {
        //     $bcc_emails[] = $supplier->default_email ?? $supplier->email;
        //   }
        // }

        $file_paths = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $filename = $file->getClientOriginalName();

                $file->storeAs("documents", $filename, 'files');

                $file_paths[] = "documents/$filename";
            }
        }

        $cc = $bcc = [];
        if ($request->has('cc')) {
            $cc = array_values(array_filter($request->cc));
        }
        if ($request->has('bcc')) {
            $bcc = array_values(array_filter($request->bcc));
        }

        foreach ($suppliers as $supplier) {
            $mail = Mail::to($supplier->default_email ?? $supplier->email);

            if ($cc) {
                $mail->cc($cc);
            }
            if ($bcc) {
                $mail->bcc($bcc);
            }

            $mail->send(new PurchaseEmail($request->subject, $request->message, $file_paths));

            $params = [
                'model_id'        => $supplier->id,
                'model_type'      => Supplier::class,
                'from'            => 'buying@amourint.com',
                'seen'            => 1,
                'to'              => $supplier->default_email ?? $supplier->email,
                'subject'         => $request->subject,
                'message'         => $request->message,
                'template'        => 'customer-simple',
                'additional_data' => json_encode(['attachment' => $file_paths]),
                'cc'              => $cc ?: null,
                'bcc'             => $bcc ?: null,
            ];

            Email::create($params);
        }

        return redirect()->route('supplier.index')->withSuccess('You have successfully sent emails in bulk!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);

//      $supplier->agents()->delete();
        //      $supplier->whatsapps()->delete();

        $supplier->delete();

        return redirect()->route('supplier.index')->withSuccess('You have successfully deleted a supplier');
    }

    /**
     * Add Notes method
     */
    public function addNote($id, Request $request)
    {
        $supplier = Supplier::findOrFail($id);
        $notes    = $supplier->notes;
        if (!is_array($notes)) {
            $notes = [];
        }

        $notes[]         = $request->get('note');
        $supplier->notes = $notes;
        $supplier->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function supplierupdate(Request $request)
    {
        $supplier            = Supplier::find($request->get('supplier_id'));
        $supplier->frequency = $request->get('id');
        $type                = $request->get('type');
        if ($type == 'category') {
            $supplier->supplier_category_id = $request->get('id');
        }
        if ($type == 'status') {
            $supplier->supplier_status_id = $request->get('id');
        }
        $supplier->save();
        return response()->json([
            'success',
        ]);
    }

    public function getsuppliers(Request $request)
    {

        $input = $request->all();

        $supplier_category_id = $input['supplier_category_id'];

        $supplier_status_id = $input['supplier_status_id'];

        $filter = $input['filter'];

        $data            = '';
        $typeWhereClause = '';
        $suppliers_all   = array();
        if ($supplier_category_id == '' && $supplier_status_id == '') {
            /* $suppliers_all = Supplier::where(function ($query) {
        $query->whereNotNull('email')->orWhereNotNull('default_email');
        })->get();*/
        } else {
            if ($supplier_category_id != '') {
                $typeWhereClause .= ' AND supplier_category_id=' . $supplier_category_id;
            }
            if ($supplier_status_id != '') {
                $typeWhereClause .= ' AND supplier_status_id=' . $supplier_status_id;
            }

            if ($filter != '') {
                $typeWhereClause .= ' AND supplier like "' . $filter . '%"';
            }
            $suppliers_all = DB::select('SELECT suppliers.id, suppliers.supplier, suppliers.email, suppliers.default_email from suppliers WHERE email != "" ' . $typeWhereClause . '');
        }

        if (count($suppliers_all) > 0) {

            foreach ($suppliers_all as $supplier) {
                $data .= '<option value="' . $supplier->id . '">' . $supplier->supplier . ' - ' . $supplier->default_email . ' / ' . $supplier->email . '</option>';
            }
        }
        return $data;
    }

    public function addSupplierCategoryCount()
    {

        $suppliercount   = SupplierCategoryCount::all();
        $category_parent = Category::where('parent_id', 0)->get();
        $category_child  = Category::where('parent_id', '!=', 0)->get();
        $supplier        = Supplier::where('supplier_status_id', 1)->orderby('supplier', 'asc')->get();

        return view('suppliers.supplier_category_count', compact('supplier', 'suppliercount', 'category_parent', 'category_child'));
    }

    public function saveSupplierCategoryCount(Request $request)
    {
        $category_id = $request->category_id;
        $supplier_id = $request->supplier_id;
        $count       = $request->count;

        $data['category_id'] = $category_id;
        $data['supplier_id'] = $supplier_id;
        $data['cnt']         = $count;
        SupplierCategoryCount::create($data);

        return 'Saved SucessFully';
    }

    public function getSupplierCategoryCount(Request $request)
    {
        $limit = $request->input('length');
        $start = $request->input('start');

        $suppliercount      = SupplierCategoryCount::query();
        $suppliercountTotal = SupplierCategoryCount::count();
        $supplier_list      = Supplier::where('supplier_status_id', 1)->orderby('supplier', 'asc')->get();
        $category_parent    = Category::where('parent_id', 0)->get();
        $category_child     = Category::where('parent_id', '!=', 0)->get();

        $suppliercount = $suppliercount->offset($start)->limit($limit)->orderBy('supplier_id', 'asc')->get();
        foreach ($suppliercount as $supplier) {
            $sup = "";
            foreach ($supplier_list as $v) {
                if ($v->id == $supplier->supplier_id) {
                    $sup .= '<option value="' . $v->id . '" selected>' . $v->supplier . '</option>';
                } else {
                    $sup .= '<option value="' . $v->id . '">' . $v->supplier . '</option>';
                }
            }

            $cat = "";
            foreach ($category_parent as $c) {
                if ($c->id == $supplier->category_id) {
                    $cat .= '<option value="' . $c->id . '" selected>' . $c->title . '</option>';
                } else {
                    $cat .= '<option value="' . $c->id . '">' . $c->title . '</option>';
                    if ($c->childs) {
                        foreach ($c->childs as $categ) {
                            $cat .= '<option value="' . $categ->id . '">-&nbsp;' . $categ->title . '</option>';
                        }
                    }
                }
            }
            foreach ($category_child as $c) {
                if ($c->id == $supplier->category_id) {
                    $cat .= '<option value="' . $c->id . '" selected>' . $c->title . '</option>';
                } else {
                    $cat .= '<option value="' . $c->id . '">' . $c->title . '</option>';
                    if ($c->childs) {
                        foreach ($c->childs as $categ) {
                            $cat .= '<option value="' . $categ->id . '">-&nbsp;' . $categ->title . '</option>';
                        }
                    }
                }
            }

            $sub_array   = array();
            $sub_array[] = '<select class="form-control update" data-column="supplier_id" data-id="' . $supplier["id"] . '">' . $sup . '</select>';
            $sub_array[] = '<select class="form-control update" data-id="' . $supplier["id"] . '" data-column="category_id">' . $cat . '</select>';
            $sub_array[] = '<input type="number"  data-id="' . $supplier["id"] . '" data-column="cnt" value="' . $supplier["cnt"] . '"  class="form-control update">';
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $supplier["id"] . '">Delete</button>';
            $data[]      = $sub_array;
        }
        if (!empty($data)) {
            $output = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => $suppliercountTotal,
                "recordsFiltered" => $suppliercountTotal,
                "data"            => $data,
            );
        } else {
            $output = array(
                "draw"            => 0,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => [],
            );
        }

        return json_encode($output);

    }

    public function updateSupplierCategoryCount(Request $request)
    {
        $id                          = $request->id;
        $column_name                 = $request->column_name;
        $value                       = $request->value;
        $suppliercount               = SupplierCategoryCount::findorfail($request->id);
        $suppliercount->$column_name = $value;
        $suppliercount->update();
        return 'Data Updated';

    }

    public function deleteSupplierCategoryCount(Request $request)
    {
        $id            = $request->id;
        $suppliercpunt = SupplierCategoryCount::findorfail($id);
        if ($suppliercpunt) {
            SupplierCategoryCount::destroy($id);
        }
        return 'Data Deleted';
    }

    public function addSupplierBrandCount()
    {

        $suppliercount   = SupplierBrandCount::all();
        $brand           = Brand::orderby('name', 'asc')->get();
        $supplier        = Supplier::where('supplier_status_id', 1)->orderby('supplier', 'asc')->get();
        $category_parent = Category::where('parent_id', 0)->get();
        $category_child  = Category::where('parent_id', '!=', 0)->get();

        return view('suppliers.supplier_brand_count', compact('supplier', 'suppliercount', 'brand', 'category_parent', 'category_child'));
    }

    public function saveSupplierBrandCount(Request $request)
    {
        $brand_id    = $request->brand_id;
        $supplier_id = $request->supplier_id;
        $count       = $request->count;
        $url         = $request->url;
        $category_id = $request->category_id;

        $data['brand_id']    = $brand_id;
        $data['supplier_id'] = $supplier_id;
        $data['cnt']         = $count;
        $data['url']         = $url;
        $data['category_id'] = $category_id;

        SupplierBrandCount::create($data);

        return 'Saved SucessFully';
    }

    public function getSupplierBrandCount(Request $request)
    {

        $columns = array(
            0 => 'supplier_id',
            1 => 'category_id',
            2 => 'brand_id',
            3 => 'count',
            4 => 'url',
            5 => 'action',
        );

        $limit = $request->input('length');
        $start = $request->input('start');

        $suppliercount      = SupplierBrandCount::query();
        $suppliercountTotal = SupplierBrandCount::count();
        $supplier_list      = Supplier::where('supplier_status_id', 1)->orderby('supplier', 'asc')->get();
        $brand_list         = Brand::orderby('name', 'asc')->get();
        $category_parent    = Category::where('parent_id', 0)->orderby('title', 'asc')->get();
        $category_child     = Category::where('parent_id', '!=', 0)->orderby('title', 'asc')->get();

        $suppliercount = $suppliercount->offset($start)->limit($limit)->orderBy('supplier_id', 'asc')->get();

        foreach ($suppliercount as $supplier) {
            $sup = "";

            foreach ($supplier_list as $v) {

                if ($v->id == $supplier->supplier_id) {
                    $sup .= '<option value="' . $v->id . '" selected>' . $v->supplier . '</option>';
                } else {
                    $sup .= '<option value="' . $v->id . '">' . $v->supplier . '</option>';
                }
            }

            $brands = "";
            foreach ($brand_list as $v) {
                if ($v->id == $supplier->brand_id) {
                    $brands .= '<option value="' . $v->id . '" selected>' . $v->name . '</option>';
                } else {
                    $brands .= '<option value="' . $v->id . '">' . $v->name . '</option>';
                }
            }

            $cat = "";
            $cat .= '<option>Select Category</option>';
            foreach ($category_parent as $c) {
                if ($c->id == $supplier->category_id) {
                    $cat .= '<option value="' . $c->id . '" selected>' . $c->title . '</option>';
                } else {
                    $cat .= '<option value="' . $c->id . '">' . $c->title . '</option>';
                    if ($c->childs) {
                        foreach ($c->childs as $categ) {
                            $cat .= '<option value="' . $categ->id . '">-&nbsp;' . $categ->title . '</option>';
                        }
                    }
                }
            }
            foreach ($category_child as $c) {
                if ($c->id == $supplier->category_id) {
                    $cat .= '<option value="' . $c->id . '" selected>' . $c->title . '</option>';
                } else {
                    $cat .= '<option value="' . $c->id . '">' . $c->title . '</option>';
                    if ($c->childs) {
                        foreach ($c->childs as $categ) {
                            $cat .= '<option value="' . $categ->id . '">-&nbsp;' . $categ->title . '</option>';
                        }
                    }
                }
            }

            $sub_array   = array();
            $sub_array[] = '<select disabled class="form-control">' . $sup . '</select>';
            $sub_array[] = '<select class="form-control" disabled>' . $cat . '</select>';
            $sub_array[] = '<select disabled class="form-control">' . $brands . '</select>';
            $sub_array[] = '<input type="number"  data-id="' . $supplier["id"] . '" data-column="cnt" value="' . $supplier["cnt"] . '"  class="form-control update">';
            $sub_array[] = $supplier["url"];
            $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $supplier["id"] . '">Delete</button>';
            $data[]      = $sub_array;
        }

        // dd(count($data));
        if (!empty($data)) {
            $output = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => $suppliercountTotal,
                "recordsFiltered" => $suppliercountTotal,
                "data"            => $data,
            );
        } else {
            $output = array(
                "draw"            => 0,
                "recordsTotal"    => 0,
                "recordsFiltered" => 0,
                "data"            => [],
            );
        }

        return json_encode($output);

    }

    public function updateSupplierBrandCount(Request $request)
    {
        $id            = $request->id;
        $column_name   = $request->column_name;
        $value         = $request->value;
        $suppliercount = SupplierBrandCount::findorfail($request->id);

        // Update in history
        $history                          = new SupplierBrandCountHistory();
        $history->supplier_brand_count_id = $suppliercount->id;
        $history->supplier_id             = $suppliercount->supplier_id;
        $history->brand_id                = $suppliercount->brand_id;
        $history->cnt                     = $suppliercount->cnt;
        $history->url                     = $suppliercount->url;
        $history->category_id             = $suppliercount->category_id;
        $history->save();
        //Update the value
        $suppliercount->$column_name = $value;
        $suppliercount->update();
        return 'Data Updated';

    }

    public function deleteSupplierBrandCount(Request $request)
    {
        $id            = $request->id;
        $suppliercount = SupplierBrandCount::findorfail($id);
        if ($suppliercount) {
            // Update in history
            $history                          = new SupplierBrandCountHistory();
            $history->supplier_brand_count_id = $suppliercount->id;
            $history->supplier_id             = $suppliercount->supplier_id;
            $history->brand_id                = $suppliercount->brand_id;
            $history->cnt                     = $suppliercount->cnt;
            $history->url                     = $suppliercount->url;
            $history->category_id             = $suppliercount->category_id;
            $history->save();
            SupplierBrandCount::destroy($id);
        }
        return 'Data Deleted';
    }

    public function block(Request $request)
    {
        $supplier = Supplier::find($request->supplier_id);

        if ($supplier->is_blocked == 0) {
            $supplier->is_blocked = 1;
        } else {
            $supplier->is_blocked = 0;
        }

        $supplier->save();

        return response()->json(['is_blocked' => $supplier->is_blocked]);
    }

    public function saveImage(Request $request)
    {
        // Only create Product
        if ($request->type == 1) {

            // Create Group ID with Product
            $images = explode(",", $request->checkbox1[0]);
            if ($images) {
                $createdProducts = [];
                foreach ($images as $image) {
                    if ($image != null) {
                        $product = Product::select('sku')->where('sku', 'LIKE', '%QUICKSELL' . date('yz') . '%')->orderBy('id', 'desc')->first();
                        if ($product) {
                            $number = str_ireplace('QUICKSELL', '', $product->sku) + 1;
                        } else {
                            $number = date('yz') . sprintf('%02d', 1);
                        }

                        $product = new Product;

                        $product->name     = 'QUICKSELL';
                        $product->sku      = 'QuickSell' . $number;
                        $product->size     = '';
                        $product->brand    = $product->brand    = $request->brand;
                        $product->color    = '';
                        $product->location = request("location", "");
                        if ($request->category == null) {
                            $product->category = '';
                        } else {
                            $product->category = $request->category;
                        }

                        if ($request->supplier == null) {
                            $product->supplier = 'QUICKSELL';
                        } else {
                            $sup               = Supplier::findorfail($request->supplier);
                            $product->supplier = $sup->supplier;
                        }
                        if ($request->buying_price == null) {
                            $product->price = 0;
                        } else {
                            $product->price = $request->buying_price;
                        }
                        if ($request->special_price == null) {
                            $product->price_inr_special = 0;
                        } else {
                            $product->price_inr_special = $request->special_price;
                        }
                        $product->stock         = 1;
                        $product->quick_product = 1;
                        $product->is_pending    = 1;
                        $product->save();
                        $createdProducts[] = $product->id;
                        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $image, $match);
                        $image = isset($match[0][0]) ? $match[0][0] : false;
                        if (!empty($image)) {
                            $jpg      = \Image::make($image)->encode('jpg');
                            $filename = substr($image, strrpos($image, '/'));
                            $filename = str_replace("/", "", $filename);
                            $media    = MediaUploader::fromString($jpg)->useFilename($filename)->upload();
                            $product->attachMedia($media, config('constants.media_tags'));
                        }

                        // return redirect()->back()->withSuccess('You have successfully saved product(s)!');
                    }
                }
                if (count($createdProducts) > 0) {
                    $message = count($createdProducts) . ' Product(s) has been created successfully, id\'s are ' . json_encode($createdProducts);
                    $code    = 200;
                } else {
                    $message = 'No Images selected';
                    $code    = 500;
                }
                return response()->json(['code' => $code, 'message' => $message]);
            } else {
                return response()->json(['code' => 500, 'message' => 'No Images selected']);
            }
        } elseif ($request->type == 3) {
            // Create Group ID with Product
            $images = $request->images;

            $images = explode('"', $images);
            if ($images) {
                $createdProducts = [];
                foreach ($images as $image) {

                    if ($image != null) {
                        if ($image != '[' && $image != ']' && $image != ',') {
                            $product           = new Product;
                            $product->name     = $request->name;
                            $product->sku      = $request->sku;
                            $product->size     = $request->size;
                            $product->brand    = $request->brand;
                            $product->color    = $request->color;
                            $product->location = $request->location;
                            $product->category = $request->category;
                            $product->supplier = $request->supplier;

                            if ($request->price == null) {
                                $product->price = 0;
                            } else {
                                $product->price = $request->price;
                            }

                            if ($request->price_special == null) {
                                $product->price_inr_special = 0;
                            } else {
                                $product->price_inr_special = $request->price_special;
                            }
                            $product->stock           = 1;
                            $product->purchase_status = 'InStock';
                            $product->save();
                            $createdProducts[] = $product->id;
                            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $image, $match);
                            $image = isset($match[0][0]) ? $match[0][0] : false;
                            if (!empty($image)) {
                                $jpg      = \Image::make($image)->encode('jpg');
                                $filename = substr($image, strrpos($image, '/'));
                                $filename = str_replace("/", "", $filename);
                                $media    = MediaUploader::fromString($jpg)->useFilename($filename)->upload();
                                $product->attachMedia($media, config('constants.media_tags'));
                            }
                        }

                    }
                }
                if (count($createdProducts) > 0) {
                    $message = count($createdProducts) . ' Product(s) has been created successfully, id\'s are ' . json_encode($createdProducts);
                    $code    = 200;
                } else {
                    $message = 'No Images selected';
                    $code    = 500;
                }
                return response()->json(['code' => $code, 'message' => $message]);
            } else {
                return response()->json(['code' => 500, 'message' => 'No Images selected']);
            }
        } else {

            // Create Group ID with Product
            $images = explode(",", $request->checkbox[0]);

            if ($images) {
                // Loop Over Images

                $group = QuickSellGroup::orderBy('id', 'desc')->first();
                if ($group != null) {
                    if ($request->groups != null) {
                        $group_create = QuickSellGroup::findorfail($request->groups);
                        $group_id     = $group_create->group;
                    } else {
                        $group_create = new QuickSellGroup();
                        $incrementId  = ($group->group + 1);
                        if ($request->group_id != null) {
                            $group_create->name = $request->group_id;
                        }
                        $group_create->suppliers     = json_encode($request->supplier);
                        $group_create->brands        = json_encode($request->brand);
                        $group_create->price         = $request->buying_price;
                        $group_create->special_price = $request->special_price;
                        $group_create->categories    = json_encode($request->category);
                        $group_create->group         = $incrementId;
                        $group_create->save();
                        $group_id = $group_create->group;
                    }
                } else {
                    $group                       = new QuickSellGroup();
                    $group->group                = 1;
                    $group_create->name          = $request->group_id;
                    $group_create->suppliers     = json_encode($request->suppliers);
                    $group_create->brands        = json_encode($request->brand);
                    $group_create->price         = $request->buying_price;
                    $group_create->special_price = $request->special_price;
                    $group_create->categories    = json_encode($request->categories);
                    $group->save();
                    $group_id = $group->group;
                }
                $createdProducts = [];
                foreach ($images as $image) {
                    //Getting the last created QUICKSELL
                    // MariaDB 10.0.5 and higher: $product = Product::select('sku')->where('sku', 'LIKE', '%QuickSell%')->whereRaw("REGEXP_REPLACE(products.sku, '[a-zA-Z]+', '') > 0")->orderBy('id', 'desc')->first();
                    $product = Product::select('sku')->where('sku', 'LIKE', '%QUICKSELL' . date('yz') . '%')->orderBy('id', 'desc')->first();
                    if ($product) {
                        $number = str_ireplace('QUICKSELL', '', $product->sku) + 1;
                    } else {
                        $number = date('yz') . sprintf('%02d', 1);
                    }
                    $product = new Product;

                    $product->name     = 'QUICKSELL';
                    $product->sku      = 'QuickSell' . $number;
                    $product->size     = '';
                    $product->brand    = $request->brand;
                    $product->color    = '';
                    $product->location = request("location", "");
                    if ($request->category == null) {
                        $product->category = '';
                    } else {
                        $product->category = $request->category;
                    }

                    if ($request->supplier == null) {
                        $product->supplier = 'QUICKSELL';
                    } else {
                        $sup               = Supplier::findorfail($request->supplier);
                        $product->supplier = $sup->supplier;
                    }
                    if ($request->buying_price == null) {
                        $product->price = 0;
                    } else {
                        $product->price = $request->buying_price;
                    }
                    if ($request->special_price == null) {
                        $product->price_inr_special = 0;
                    } else {
                        $product->price_inr_special = $request->special_price;
                    }

                    $product->stock         = 1;
                    $product->quick_product = 1;
                    $product->is_pending    = 1;
                    $product->save();
                    $createdProducts[] = $product->id;
                    preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $image, $match);
                    if (isset($match[0]) && isset($match[0][0])) {
                        $image = $match[0][0];
                        $jpg   = \Image::make($image)->encode('jpg');

                        $filename = substr($image, strrpos($image, '/'));
                        $filename = str_replace("/", "", $filename);
                        $media    = MediaUploader::fromString($jpg)->useFilename($filename)->upload();
                        $product->attachMedia($media, config('constants.media_tags'));
                    }
                    // if Product is true
                    if ($product == true) {
                        //Finding last created Product using sku
                        $product_id = Product::where('sku', $product->sku)->first();
                        if ($product_id != null) {
                            $id = $product_id->id;
                            //getting last group id

                            $group                     = new ProductQuicksellGroup();
                            $group->product_id         = $id;
                            $group->quicksell_group_id = $group_id;
                            $group->save();

                        }
                    }
                }
                if (count($createdProducts) > 0) {
                    $message = count($createdProducts) . ' Product(s) has been created successfully, id\'s are ' . json_encode($createdProducts);
                    $code    = 200;
                } else {
                    $message = 'No Images selected';
                    $code    = 500;
                }
                return response()->json(['code' => $code, 'message' => $message]);
            } else {
                return response()->json(['code' => 500, 'message' => 'No Images selected']);
            }
        }
    }

    /**
     * @SWG\Post(
     *   path="/supplier/brands-raw",
     *   tags={"Scraper"},
     *   summary="Update supplier brand raw",
     *   operationId="scraper-post-supplier-brands",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=403, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="supplier_id",
     *          in="formData",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="brands_raw",
     *          in="formData",
     *          required=true,
     *          type="string"
     *      ),
     * )
     *
     */
    public function apiBrandsRaw(Request $request)
    {
        // Get supplier ID
        $supplierId = (int) $request->supplier_id;
        $brandsRaw  = $request->brands_raw;

        if (empty($supplierId) || empty($brandsRaw)) {
            return response()->json(['error' => 'The fields supplier_id and brands_raw are obligated'], 403);
        }

        // Get Supplier model
        $supplier = Supplier::find($supplierId);

        // Do we have a result?
        if ($supplier != null) {
            $supplier->scraped_brands_raw = $brandsRaw;
            $supplier->save();

            return response()->json(['success' => 'Supplier updated'], 200);
        }

        // Still here? Return an error
        return response()->json(['error' => 'Supplier not found'], 403);
    }

    /**
     * Get scraped brand and scraped brands raw of a supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json response with brand and brand raw
     */
    public function getScrapedBrandAndBrandRaw(Request $request)
    {
        $supplierId = $request->id;

        $supplier = Supplier::find($supplierId);
        if ($supplier->scraped_brands != '') {
            $scrapedBrands = array_filter(explode(',', $supplier->scraped_brands));

            sort($scrapedBrands);
        } else {
            $scrapedBrands = array();
        }

        if ($supplier->scraped_brands_raw != '') {
            $rawBrands = array_unique(array_filter(array_column(json_decode($supplier->scraped_brands_raw, true), 'name')));

            sort($rawBrands);
        } else {
            $rawBrands = array();
        }

        return response()->json(['scrapedBrands' => $scrapedBrands, 'scrapedBrandsRaw' => $rawBrands], 200);
    }

    /**
     * Update scraped brand from scrapped brands raw for a supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json response with update status
     */
    public function updateScrapedBrandFromBrandRaw(Request $request)
    {
        $supplierId   = $request->id;
        $newBrandData = ($request->newBrandData) ? $request->newBrandData : array();

        // Get Supplier model
        $supplier = Supplier::find($supplierId);

        // Do we have a result?
        if ($supplier != null) {
            $supplier->scraped_brands = implode(',', $newBrandData);
            $supplier->save();

            return response()->json(['success' => 'Supplier brand updated'], 200);
        }

        // Still here? Return an error
        return response()->json(['error' => 'Supplier not found'], 403);

    }

    public function excelImport(Request $request)
    {

        if ($request->attachment) {
            $supplier = Supplier::find($request->id);
            $file     = explode('/', $request->attachment);
            if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                $excel = $supplier->getSupplierExcelFromSupplierEmail();
                $excel = ErpExcelImporter::excelFileProcess(end($file), $excel, $supplier->email);
                return response()->json(['success' => 'File Processed For Import'], 200);
            } else {
                return response()->json(['error' => 'File Couldnt Process For Import'], 200);
            }
        }

        if ($request->file('excel_file')) {
            $file = $request->file('excel_file');
            if ($file->getClientOriginalExtension() == 'xls' || $file->getClientOriginalExtension() == 'xlsx') {

                //SAve FIle
                if (!file_exists(storage_path('app/files/email-attachments/file/'))) {
                    mkdir(storage_path('app/files/email-attachments/file/'), 0777, true);
                }

                $path = storage_path('app/files/email-attachments/file/');
                $file->move($path, $file->getClientOriginalName());
                $filePath = '/file/' . $file->getClientOriginalName();
                $supplier = Supplier::find($request->id);
                if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                    $excel = $supplier->getSupplierExcelFromSupplierEmail();
                    $excel = ErpExcelImporter::excelFileProcess($filePath, $excel, $supplier->email);
                    return redirect()->back()->withSuccess('File Processed For Import');
                } else {
                    return redirect()->back()->withErrors('Excel Importer Not Found');
                }

            } else {
                return redirect()->back()->withErrors('Please Use Excel FIle');
            }
        }
    }

    /**
     * Remove particular scraped brand from scrapped brands for a supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json response with status, updated brand list, raw brand list
     */
    public function removeScrapedBrand(Request $request)
    {
        $supplierId      = $request->id;
        $removeBrandData = $request->removeBrandData;

        // Get Supplier model
        $supplier = Supplier::find($supplierId);

        // Do we have a result?
        if ($supplier != null) {
            if ($supplier->scraped_brands != '') {
                $scrapedBrands = array_filter(explode(',', $supplier->scraped_brands));

                $newBrandData = array_diff($scrapedBrands, array($removeBrandData));
                sort($newBrandData);
            } else {
                $newBrandData = array();
            }
            if ($supplier->scraped_brands_raw != '') {
                $rawBrands = array_unique(array_filter(array_column(json_decode($supplier->scraped_brands_raw, true), 'name')));
                sort($rawBrands);
            } else {
                $rawBrands = array();
            }

            $supplier->scraped_brands = implode(',', $newBrandData);
            $supplier->save();

            return response()->json(['scrapedBrands' => $newBrandData, 'scrapedBrandsRaw' => $rawBrands, 'success' => 'Scraped brand removed'], 200);
        }

        // Still here? Return an error
        return response()->json(['error' => 'Supplier not found'], 403);
    }

    public function changeMail(Request $request)
    {
        $supplier        = Supplier::find($request->supplier_id);
        $supplier->email = $request->email;
        $supplier->save();
        return response()->json(["code" => 200, "data" => [], "message" => "Email updated successfully"]);
    }

    public function changePhone(Request $request)
    {
        $supplier        = Supplier::find($request->supplier_id);
        $supplier->phone = $request->phone;
        $supplier->save();
        return response()->json(["code" => 200, "data" => [], "message" => "Telephone Number updated successfully"]);
    }
    public function changeSize(Request $request)
    {
        $supplier                   = Supplier::find($request->supplier_id);
        $supplier->supplier_size_id = $request->size;
        $supplier->save();
        return response()->json(["code" => 200, "data" => [], "message" => "Size updated successfully"]);
    }

    public function changeSizeSystem(Request $request)
    {
        $supplier                 = Supplier::find($request->supplier_id);
        $supplier->size_system_id = $request->size;
        $supplier->save();
        return response()->json(["code" => 200, "data" => [], "message" => "Size System updated successfully"]);
    }

    public function changeWhatsapp(Request $request)
    {
        $supplier                  = Supplier::find($request->supplier_id);
        $supplier->whatsapp_number = $request->whatsapp;
        $supplier->save();
        return response()->json(["code" => 200, "data" => [], "message" => "Whatsapp Number updated successfully"]);
    }
    /**
     * copy selected scraped brands to brand for a supplier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json response with update status, brands
     */
    public function copyScrapedBrandToBrand(Request $request)
    {
        $supplierId = $request->id;

        // Get Supplier model
        $supplier = Supplier::find($supplierId);

        // Do we have a result?
        if ($supplier != null) {
            $selectedScrapedBrand = ($supplier->scraped_brands) ? $supplier->scraped_brands : '';
            if ($selectedScrapedBrand != '') {
                //We have got selected scraped brands, now store that in brands
                $supplier->brands = '"[' . $selectedScrapedBrand . ']"';
                $supplier->save();

                $miniScrapedBrand = strlen($selectedScrapedBrand) > 10 ? substr($selectedScrapedBrand, 0, 10) . '...' : $selectedScrapedBrand;

                return response()->json(['success' => 'Supplier brand updated', 'mini' => $miniScrapedBrand, 'full' => $selectedScrapedBrand], 200);
            } else {
                return response()->json(['error' => 'Scraped brands not selected for the supplier'], 403);
            }
        }

        // Still here? Return an error
        return response()->json(['error' => 'Supplier not found'], 403);
    }

    public function languageTranslate(Request $request)
    {
        $supplier           = Supplier::find($request->id);
        $supplier->language = $request->language;
        $supplier->save();
        return response()->json(['success' => 'Supplier language updated'], 200);
    }

    public function priority(Request $request)
    {
        $supplier           = Supplier::find($request->id);
        $supplier->priority = $request->priority;
        $supplier->save();
        return response()->json(['success' => 'Supplier priority updated'], 200);
    }

    public function manageScrapedBrands(Request $request)
    {
        $arr  = [];
        $data = Setting::where('type', "ScrapeBrandsRaw")->get()->first();
        if (empty($data)) {
            $brand['type'] = "ScrapeBrandsRaw";
            $brand['val']  = json_encode($request->selectedBrands);
            Setting::create($brand);
        } else {
            $data->val = json_encode($request->selectedBrands);
            $data->save();
        }
        return "Scraped Brands Raw removed from dropdown successfully";
    }

    public function changeWhatsappNo(Request $request)
    {
        $supplier                  = Supplier::find($request->supplier_id);
        $supplier->whatsapp_number = $request->number;
        $supplier->update();
        return response()->json(['success' => 'Supplier Whatsapp updated'], 200);
    }

    public function changeStatus(Request $request)
    {
        $supplierId = $request->get("supplier_id");
        $statusId   = $request->get("supplier_status_id");

        if (!empty($supplierId)) {
            $supplier = \App\Supplier::find($supplierId);
            if (!empty($supplier)) {
                $supplier->supplier_status_id = ($statusId == "false") ? 0 : 1;
                $supplier->save();
            }
        }

        return response()->json(["code" => 200, "data" => [], "message" => "Status updated successfully"]);
    }

    /**
     * Change supplier category
     */
    public function changeCategory(Request $request)
    {
        $supplierId = $request->get("supplier_id");
        $categoryId = $request->get("supplier_category_id");

        if (!empty($supplierId)) {
            $supplier = \App\Supplier::find($supplierId);
            if (!empty($supplier)) {
                $supplier->fill(['supplier_category_id' => $categoryId])->save();
            }
        }
        return response()->json(["code" => 200, "data" => [], "message" => "Category updated successfully"]);
    }

    public function changeSupplierStatus(Request $request)
    {
        $supplierId = $request->get("supplier_id");
        $status     = $request->get("status");

        if (!empty($supplierId)) {
            $supplier = \App\Supplier::find($supplierId);
            if (!empty($supplier)) {
                $supplier->fill(['supplier_status_id' => $status])->save();
            }
        }
        return response()->json(["code" => 200, "data" => [], "message" => "Status updated successfully"]);
    }

    public function changeSubCategory(Request $request)
    {
        $supplierId = $request->get("supplier_id");
        $categoryId = $request->get("supplier_sub_category_id");

        if (!empty($supplierId)) {
            $supplier = \App\Supplier::find($supplierId);
            if (!empty($supplier)) {
                $supplier->fill(['supplier_sub_category_id' => $categoryId])->save();
            }
        }
        return response()->json(["code" => 200, "data" => [], "message" => "Sub Category updated successfully"]);
    }

    public function editInventorylifetime(Request $request)
    {
        $supplierId         = $request->get("supplier_id");
        $inventory_lifetime = $request->get("inventory_lifetime");

        if (!empty($supplierId)) {
            $supplier = \App\Scraper::where('supplier_id', $supplierId)->first();
            if (!empty($supplier)) {
                $supplier->fill(['inventory_lifetime' => $inventory_lifetime])->save();
            }
        }
        return response()->json(["code" => 200, "data" => [], "message" => "Inventory lifetime updated successfully"]);
    }

    public function changeScrapper(Request $request)
    {
        $supplierId = $request->get("supplier_id");
        $scrapperId = $request->get("scrapper");
        if(!empty($supplierId)) {
           $supplier = \App\Supplier::find($supplierId);
           $scrapper = \App\Scraper::where('supplier_id',$supplierId)->first();
           if(!empty($scrapper)) {
                $supplier->fill(['scrapper' => $scrapperId])->save();
           }else{
                $scrapper_name = preg_replace("/\s+/", "", $supplier->supplier);
                $scrapper_name = strtolower($scrapper_name);
                $scraper = \App\Scraper::create([
                      "supplier_id" => $supplier->id,
                      "scraper_name" => $request->get("scraper_name", $scrapper_name),
                      "inventory_lifetime" => $request->get("inventory_lifetime", ""),
                  ]);
                $supplier->fill(['scrapper' => $scrapperId])->save();
                //return $scraper;
           }
        }
        return response()->json(["code" => 200, "data" => [], "message" => "Scrapper updated successfully"]);
    }

    /**
     * Add supplier category
     */
    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SupplierCategory::create($request->all());

        return redirect()->route('supplier.index')->withSuccess('You have successfully saved a category!');
    }

    public function addSubCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SupplierSubCategory::create($request->all());

        return redirect()->route('supplier.index')->withSuccess('You have successfully saved a sub category!');
    }

    public function addStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SupplierStatus::create($request->all());

        return redirect()->route('supplier.index')->withSuccess('You have successfully saved a status!');
    }

    public function addSupplierSize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SupplierSize::create($request->all());

        return redirect()->route('supplier.index')->withSuccess('You have successfully saved a supplier size!');
    }

    public function MessageTranslateHistory( Request $request ){

        $history = \App\SupplierTranslateHistory::orderBy("id","desc")->where('supplier_id',$request->supplier)->get();
        return response()->json( ["code" => 200 , "data" => $history] );
        
    }

    public function sendMessage(Request $request)
    {
        // return $request->all();
        $suppliers = Supplier::whereIn('id', $request->suppliers)->get();
        $params    = [];
        if (count($suppliers)) {
            foreach ($suppliers as $key => $item) {
                $params = [
                    'supplier_id' => $item->id,
                    'number'      => null,
                    'message'     => $request->message,
                    'user_id'     => Auth::id(),
                    'status'      => 1,
                ];
                $chat_message   = ChatMessage::create($params);
                $approveRequest = new Request();
                $approveRequest->setMethod('GET');
                $approveRequest->request->add(['messageId' => $chat_message->id]);

                app(WhatsAppController::class)->approveMessage("supplier", $approveRequest);
            }
        }
        // return $params;

        return response()->json(["code" => 200, "data" => [], "message" => "Message sent successfully"]);
    }

    public function addPriceRange(Request $request)
    {
        SupplierPriceRange::create($request->all());
        return redirect()->route('supplier.index')->withSuccess('You have successfully saved a price range!');
    }

    public function changePriceRange(Request $request)
    {
        $supplierId   = $request->get("supplier_id");
        $priceRangeId = $request->get("price_range_id");

        if (!empty($supplierId)) {
            $supplier = \App\Supplier::find($supplierId);
            if (!empty($supplier)) {
                $supplier->fill(['supplier_price_range_id' => $priceRangeId])->save();
            }
        }
        return response()->json(["code" => 200, "data" => [], "message" => "Price Range updated successfully"]);
    }
}
