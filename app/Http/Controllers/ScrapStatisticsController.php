<?php

namespace App\Http\Controllers;

use App\Exports\ScrapRemarkExport;
use App\Product;
use App\Scraper;
use App\ScrapHistory;
use App\ScrapRemark;
use App\ScrapStatistics;
use App\Supplier;
use App\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Zend\Diactoros\Response\JsonResponse;
use \Carbon\Carbon;

class ScrapStatisticsController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/stat",
     *   tags={"Statistic"},
     *   summary="Get Statistics",
     *   operationId="get-statistics",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="mytest",
     *          in="path",
     *          required=true,
     *          type="string"
     *      ),
     * )
     *
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Set dates
        $endDate = date('Y-m-d H:i:s');
        $keyWord = $request->get("term", "");
        $madeby = $request->get("scraper_made_by", 0);
        $scrapeType = $request->get("scraper_type", 0);

        $timeDropDown = self::get_times();

        $serverIds = Scraper::groupBy('server_id')->where('server_id', '!=', null)->pluck('server_id');
        $getLatestOptimization = \App\ScraperServerStatusHistory::whereRaw("id in (
            SELECT MAX(id)
            FROM scraper_server_status_histories
            GROUP BY server_id
        )")
            ->pluck('in_percentage', 'server_id')->toArray();


        // Get active suppliers
        $activeSuppliers = Scraper::with([
            'scraperDuration' => function ($q) {
                $q->orderBy('id', 'desc');
            },
            'scrpRemark' => function ($q) {
                $q->whereNull("scrap_field")->where('user_name', '!=', '')->orderBy('created_at', 'desc');
            },
            'latestMessageNew' => function ($q) {
                $q->whereNotIn('chat_messages.status', ['7', '8', '9', '10'])
                    ->take(1)
                    ->orderBy("id", "desc");
            },
            'lastErrorFromScrapLogNew',
            'developerTaskNew',
            'scraperMadeBy',
            'childrenScraper.scraperMadeBy',
            'mainSupplier',

        ])
            ->withCount('childrenScraper')
            ->join("suppliers as s", "s.id", "scrapers.supplier_id")
            // ->select('scrapers.id as scrapper_id', 'scrapers.*', "s.*", "scrapers.status as scrapers_status")
            ->where('supplier_status_id', 1)
            ->whereIn("scrapper", [1, 2])
            ->whereNull('parent_id');

        if (!empty($keyWord)) {
            $activeSuppliers->where(function ($q) use ($keyWord) {
                $q->where("s.supplier", "like", "%{$keyWord}%")
                    ->orWhere("scrapers.scraper_name", "like", "%{$keyWord}%");
            });
        }

        if ($madeby > 0) {
            $activeSuppliers->where("scrapers.scraper_made_by", $madeby);
        }

        if ($request->get("scrapers_status", "") != '') {
            $activeSuppliers->where("scrapers.status", $request->get("scrapers_status", ""));
        }

        if ($scrapeType > 0) {
            $activeSuppliers->where("scraper_type", $scrapeType);
        }

        if($request->task_assigned_to > 0) {
            $activeSuppliers->whereRaw('scrapers.id IN (SELECT scraper_id FROM developer_tasks WHERE assigned_to = '.$request->task_assigned_to.' and scraper_id > 0)');
        }

        $activeSuppliers = $activeSuppliers->orderby('scrapers.flag', 'desc')->orderby('s.supplier', 'asc');

        $ids = $activeSuppliers->pluck('supplier_id')->toArray();

        $activeSuppliers = $activeSuppliers->get();

            $suppliers = DB::table('products')
                ->select(DB::raw('count(*) as inventory'), 'supplier_id as id', DB::raw('max(created_at) as last_date'))
                ->groupBy('supplier_id')->orderBy('created_at', 'desc')->get();
    //        Supplier::with('inventory', 'lastProduct')->whereIn('id', $ids)->get();
            $data = [];

            foreach ($suppliers as $supplier) {

                if ($supplier->id !== null) {

                    $data[$supplier->id]['inventory'] = $supplier->inventory;
                    $data[$supplier->id]['last_date'] = $supplier->last_date;

    //            $data[$supplier->id]['last_date'] = $supplier->lastProduct !== null ? $supplier->lastProduct->created_at : null;
                }
            }
//        dd($suppliers, $data);


        foreach ($activeSuppliers as $activeSupplier) {

            if (isset($data[$activeSupplier->supplier_id])) {
                $activeSupplier->inventory = $data[$activeSupplier->supplier_id]['inventory'];
                $activeSupplier->last_date = $data[$activeSupplier->supplier_id]['last_date'];;
            } else {
                $activeSupplier->inventory = 0;
                $activeSupplier->last_date = null;
            }

        }


//

//        dd($ids, $data);


        //  dd($activeSuppliers[0]);
        // Get scrape data
        $yesterdayDate = date("Y-m-d", strtotime("-1 day"));
        $sql = '
            SELECT
                s.id,
                s.supplier,
                sc.inventory_lifetime,
                sc.scraper_new_urls,
                sc.scraper_existing_urls,
                sc.scraper_total_urls,
                sc.scraper_start_time,
                sc.scraper_logic,
                sc.scraper_made_by,
                sc.server_id,
                sc.id as scraper_id,
                ls.website,
                ls.ip_address,
                COUNT(ls.id) AS total,
                SUM(IF(ls.validated=0,1,0)) AS failed,
                SUM(IF(ls.validated=1,1,0)) AS validated,
                SUM(IF(ls.validation_result LIKE "%[error]%",1,0)) AS errors,
                SUM(IF(ls.validation_result LIKE "%[warning]%",1,0)) AS warnings,
                SUM(IF(ls.created_at LIKE "%' . $yesterdayDate . '%",1,0)) AS total_new_product,
                MAX(ls.last_inventory_at) AS last_scrape_date,
                IF(MAX(ls.last_inventory_at) < DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY),0,1) AS running
            FROM
                suppliers s
            JOIN
                scrapers sc
            ON
                sc.supplier_id = s.id
            JOIN
                scraped_products ls
            ON
                sc.scraper_name=ls.website
            WHERE
                sc.scraper_name IS NOT NULL AND
                
                ' . ($request->excelOnly == 1 ? 'ls.website LIKE "%_excel" AND' : '') . '
                ' . ($request->excelOnly == -1 ? 'ls.website NOT LIKE "%_excel" AND' : '') . '
                ls.last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY)
            GROUP BY
                sc.id
            ORDER BY
                sc.scraper_priority desc
        ';
        $scrapeData = DB::select($sql);

        $scrapper_total = count($scrapeData);//Purpose : Scrapper Count - DEVTASK-4219

        $allScrapperName = [];

        if (!empty($scrapeData)) {
            foreach ($scrapeData as $data) {
                if (isset($data->id) && $data->id > 0) {
                    $allScrapperName[$data->id] = $data->website;
                }
            }
        }

        /* Scrapper status count */

        $allStatus = Scraper::STATUS;

        $allStatusCounts = \App\Scraper::join("suppliers as s", "s.id", "scrapers.supplier_id")
            ->selectRaw('COUNT(s.id) as total_count, scrapers.status')
            ->whereIn('scrapers.status', $allStatus)
            ->where('supplier_status_id', 1)
            ->groupBy('scrapers.status')
            ->get()
            ->pluck('total_count', 'status');

        $lastRunAt = \DB::table("scraped_products")->groupBy("website")->select([\DB::raw("MAX(last_inventory_at) as last_run_at"), "website"])->pluck("last_run_at", "website")->toArray();

        $users = \App\User::all()->pluck("name", "id")->toArray();
        $allScrapper = Scraper::whereNull('parent_id')->pluck('scraper_name', 'id')->toArray();
        // Return view
        return view('scrap.stats', compact('allStatus', 'allStatusCounts', 'activeSuppliers', 'serverIds', 'scrapeData', 'users', 'allScrapperName', 'timeDropDown', 'lastRunAt', 'allScrapper', 'getLatestOptimization','scrapper_total'));
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickView(Request $request)
    {
        $endDate = date('Y-m-d H:i:s');
        $keyWord = $request->get("term", "");
        $column = request('column');
        $orderby = request('order_by', 'desc');
        $madeby = $request->get("scraper_made_by", 0);
        $scrapeType = $request->get("scraper_type", 0);

        $timeDropDown = self::get_times();

        $serverIds = Scraper::groupBy('server_id')->where('server_id', '!=', null)->pluck('server_id');
        $getLatestOptimization = \App\ScraperServerStatusHistory::whereRaw("id in (
            SELECT MAX(id)
            FROM scraper_server_status_histories
            GROUP BY server_id
        )")
            ->pluck('in_percentage', 'server_id')->toArray();

        // Get active suppliers
        $activeSuppliers = Scraper::with([
            'scrpRemark' => function ($q) {
                $q->whereNull("scrap_field")->where('user_name', '!=', '')->orderBy('created_at', 'desc');
            },
            'latestMessageNew' => function ($q) {
                $q->whereNotIn('chat_messages.status', ['7', '8', '9', '10'])
                    ->take(1)
                    ->orderBy("id", "desc");
            },
            'lastErrorFromScrapLogNew',
            'developerTaskNew',
            'scraperMadeBy',
            'childrenScraper.scraperMadeBy',
            'mainSupplier'
        ])
            ->withCount('childrenScraper')
            ->join("suppliers as s", "s.id", "scrapers.supplier_id")

            // Get active suppliers
            // $activeSuppliers = Scraper::join("suppliers as s", "s.id", "scrapers.supplier_id")
            //     ->select('scrapers.id as scrapper_id', 'scrapers.*', "s.*", "scrapers.status as scrapers_status")
            ->where('supplier_status_id', 1)
            ->whereIn("scrapper", [1, 2])
            ->whereNull('parent_id');

        if (!empty($keyWord)) {
            $activeSuppliers->where(function ($q) use ($keyWord) {
                $q->where("s.supplier", "like", "%{$keyWord}%")->orWhere("scrapers.scraper_name", "like", "%{$keyWord}%");
            });
        }

        if (!empty($column) && $column == 'last_started_at') {
            $activeSuppliers = $activeSuppliers->orderby('scrapers.' . $column . '', $orderby)->get();
        } else {
            $activeSuppliers = $activeSuppliers->orderby('scrapers.flag', 'desc')->orderby('s.supplier', 'asc')->get();
        }


        // Get scrape data
        $yesterdayDate = date("Y-m-d", strtotime("-1 day"));
        $sql = '
            SELECT
                s.id,
                s.supplier,
                sc.inventory_lifetime,
                sc.scraper_new_urls,
                sc.scraper_existing_urls,
                sc.scraper_total_urls,
                sc.scraper_start_time,
                sc.scraper_logic,
                sc.scraper_made_by,
                sc.server_id,
                sc.id as scraper_id,
                ls.website,
                ls.ip_address,
                COUNT(ls.id) AS total,
                SUM(IF(ls.validated=0,1,0)) AS failed,
                SUM(IF(ls.validated=1,1,0)) AS validated,
                SUM(IF(ls.validation_result LIKE "%[error]%",1,0)) AS errors,
                SUM(IF(ls.validation_result LIKE "%[warning]%",1,0)) AS warnings,
                SUM(IF(ls.created_at LIKE "%' . $yesterdayDate . '%",1,0)) AS total_new_product,
                MAX(ls.last_inventory_at) AS last_scrape_date,
                IF(MAX(ls.last_inventory_at) < DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY),0,1) AS running
            FROM
                suppliers s
            JOIN
                scrapers sc
            ON
                sc.supplier_id = s.id
            JOIN
                scraped_products ls
            ON
                sc.scraper_name=ls.website
            WHERE
                sc.scraper_name IS NOT NULL AND
                
                ' . ($request->excelOnly == 1 ? 'ls.website LIKE "%_excel" AND' : '') . '
                ' . ($request->excelOnly == -1 ? 'ls.website NOT LIKE "%_excel" AND' : '') . '
                ls.last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY)
            GROUP BY
                sc.id
            ORDER BY
                ' . ($column == "least_product" ? "total_new_product " . $orderby . " " : "sc.scraper_priority DESC") . '
            ';

        $scrapeData = DB::select($sql);

        $allScrapperName = [];

        if (!empty($scrapeData)) {
            foreach ($scrapeData as $data) {
                if (isset($data->id) && $data->id > 0) {
                    $allScrapperName[$data->id] = $data->website;
                }
            }
        }

        $allStatus = Scraper::STATUS;

        $allStatusCounts = \App\Scraper::join("suppliers as s", "s.id", "scrapers.supplier_id")
            ->selectRaw('COUNT(s.id) as total_count, scrapers.status')
            ->whereIn('scrapers.status', $allStatus)
            ->where('supplier_status_id', 1)
            ->groupBy('scrapers.status')
            ->get()
            ->pluck('total_count', 'status');

        $lastRunAt = \DB::table("scraped_products")->groupBy("website")->select([\DB::raw("MAX(last_inventory_at) as last_run_at"), "website"])->pluck("last_run_at", "website")->toArray();

        $users = \App\User::all()->pluck("name", "id")->toArray();
        $allScrapper = Scraper::whereNull('parent_id')->pluck('scraper_name', 'id')->toArray();
        // Return view
        try {
            return view('scrap.quick-stats', compact('allStatusCounts', 'allStatus', 'activeSuppliers', 'serverIds', 'scrapeData', 'users', 'allScrapperName', 'timeDropDown', 'lastRunAt', 'allScrapper', 'getLatestOptimization'));
        } catch (Exception $e) {
            \Log::error('Quick-stats-page :: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier' => 'required',
            'type' => 'required',
            'url' => 'required',
        ]);

        $stat = new ScrapStatistics();
        $stat->supplier = $request->get('supplier');
        $stat->type = $request->get('type');
        $stat->url = $request->get('url');
        $stat->description = $request->get('description');
        $stat->save();

        return response()->json([
            'status' => 'Added successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ScrapStatistics $scrapStatistics
     * @return \Illuminate\Http\Response
     */
    public function show(ScrapStatistics $scrapStatistics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ScrapStatistics $scrapStatistics
     * @return \Illuminate\Http\Response
     */
    public function edit(ScrapStatistics $scrapStatistics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ScrapStatistics $scrapStatistics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScrapStatistics $scrapStatistics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ScrapStatistics $scrapStatistics
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScrapStatistics $scrapStatistics)
    {
        //
    }

    public function assetManager()
    {
        $start = Carbon::now()->format('Y-m-d 00:00:00');
        $end = Carbon::now()->format('Y-m-d 23:59:00');
        // dd('hello');
        return view('scrap.asset-manager');
    }

    public function showHistory(Request $request)
    {
        $remarks = ScrapRemark::where('scrap_id', $request->search)->where('scrap_field', $request->field);

        if (in_array($request->field, ["scraper_start_time", "server_id", "status"])) {
            $remarks = $remarks->where(function ($q) {
                $q->orWhere("old_value", "!=", "")->orWhere("new_value", "!=", "");
            });
        }

        $remarks = $remarks->get();

        return response()->json($remarks, 200);
    }

    public function getRemark(Request $request)
    {
        $name = $request->input('name');

        $remarks = ScrapRemark::where('scraper_name', $name)->where("user_name", "!=", "");

        if ($request->get("auto") == "true") {
            $remarks = $remarks->whereNull("scrap_field");
        }

        $remarks = $remarks->latest()->get();

        $download = $request->input('download');
        return response()->json($remarks, 200);
    }

    public function addRemark(Request $request)
    {
        $remark = $request->input('remark');
        $name = $request->input('id');
        $created_at = date('Y-m-d H:i:s');
        $update_at = date('Y-m-d H:i:s');
        $last_rec = "";//Purpose : Last Record - DEVTASK-4219

        if (!empty($remark)) {
            $remark_entry = ScrapRemark::create([
                'scraper_name' => $name,
                'remark' => $remark,
                'user_name' => Auth::user()->name,
            ]);

            $needToSend = request()->get("need_to_send", false);
            $includeAssignTo = request()->get("inlcude_made_by", false);

            if ($needToSend == 1) {
                //Purpose : Comment Code - DEVTASK-4219
                // app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi('31629987287', '971502609192', "SCRAPER-REMARK#" . $name . "\n" . $remark);
                // app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi('919004780634', '971502609192', "SCRAPER-REMARK#" . $name . "\n" . $remark);

                //START - Purpose : Send message Posted by user - DEVTASK-4219
                if (Auth::user()->phone != '' && Auth::user()->whatsapp_number != '') {
                    app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi(Auth::user()->phone, Auth::user()->whatsapp_number, "SCRAPER-REMARK#" . $name . "\n" . $remark);
                }
                //END - DEVTASK-4219

                if ($includeAssignTo == 1) {
                    $scraper = \App\Scraper::where("scraper_name", $name)->first();
                    if ($scraper) {
                        $sendPer = $scraper->scraperMadeBy;
                        if ($sendPer) {
                            app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($sendPer->phone, $sendPer->whatsapp_number, "SCRAPER-REMARK#" . $name . "\n" . $remark);
                        }
                    }
                }
            }

            $last_rec = ScrapRemark::latest()->first(); //Purpose : Last Record - DEVTASK-4219
        }

        return response()->json(['remark' => $remark, 'last_record' => $last_rec], 200); //Purpose : Send Last Record - DEVTASK-4219
    }

    public function updateField(Request $request)
    {

        $fieldName = request()->get("field");
        $fieldValue = request()->get("field_value");
        $search = request()->get("search");
        $remark = request()->get("remark");
        //dd($search);
        $suplier = \App\Scraper::where("supplier_id", $search)->first();

        if (!$suplier) {
            $suplier = \App\Scraper::find($search);
        }

        if ($suplier) {
            $oldValue = $suplier->{$fieldName};

            if ($fieldName == "scraper_made_by") {
                $oldValue = ($suplier->scraperMadeBy) ? $suplier->scraperMadeBy->name : "";
            }

            if ($fieldName == "parent_supplier_id") {
                $oldValue = ($suplier->scraperParent) ? $suplier->scraperParent->scraper_name : "";
            }

            $suplier->{$fieldName} = $fieldValue;
            $suplier->save();

            $suplier = \App\Scraper::where("supplier_id", $search)->first();

            if (!$suplier) {
                $suplier = \App\Scraper::find($search);
            }

            $newValue = $fieldValue;

            if ($fieldName == "scraper_made_by") {
                $newValue = ($suplier->scraperMadeBy) ? $suplier->scraperMadeBy->name : "";
            }

            if ($fieldName == "parent_supplier_id") {
                $newValue = ($suplier->scraperParent) ? $suplier->scraperParent->scraper_name : "";
            }

            $remark_entry = ScrapRemark::create([
                'scraper_name' => $suplier->scraper_name,
                'remark' => "{$fieldName} updated old value was $oldValue and new value is $newValue",
                'user_name' => Auth::user()->name,
                'scrap_field' => $fieldName,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'scrap_id' => $suplier->id,
            ]);

            if (!empty($remark)) {
                $remark_entry = ScrapRemark::create([
                    'scraper_name' => $suplier->scraper_name,
                    'remark' => $remark,
                    'user_name' => Auth::user()->name,
                    'scrap_id' => $suplier->id,
                ]);
            }
        }

        return response()->json(["code" => 200, "data" => $suplier]);

    }

    public function updateScrapperField(Request $request)
    {
        $fieldName = request()->get("field");
        $fieldValue = request()->get("field_value");
        $search = request()->get("search");

        $suplier = \App\Scraper::find($search);

        if (!$suplier) {
            return response()->json(["code" => 500]);
        }

        if ($suplier) {
            $oldValue = $suplier->{$fieldName};

            if ($fieldName == "scraper_made_by") {
                $oldValue = ($suplier->scraperMadeBy) ? $suplier->scraperMadeBy->name : "";
            }

            if ($fieldName == "parent_supplier_id") {
                $oldValue = ($suplier->scraperParent) ? $suplier->scraperParent->scraper_name : "";
            }

            $suplier->{$fieldName} = $fieldValue;
            $suplier->save();

            $suplier = \App\Scraper::where("supplier_id", $search)->first();

            if (!$suplier) {
                $suplier = \App\Scraper::find($search);
            }

            $newValue = $fieldValue;

            if ($fieldName == "scraper_made_by") {
                $newValue = ($suplier->scraperMadeBy) ? $suplier->scraperMadeBy->name : "";
            }

            if ($fieldName == "parent_supplier_id") {
                $newValue = ($suplier->scraperParent) ? $suplier->scraperParent->scraper_name : "";
            }

            $remark_entry = ScrapRemark::create([
                'scrap_id' => $suplier->id,
                'scraper_name' => $suplier->scraper_name,
                'remark' => "{$fieldName} updated old value was $oldValue and new value is $newValue",
                'user_name' => Auth::user()->name,
                'scrap_field' => $fieldName,
                'old_value' => $oldValue,
                'new_value' => $newValue,
            ]);

        }

        return response()->json(["code" => 200]);

    }

    public function updatePriority(Request $request)
    {
        $ids = $request->get("ids");
        $prio = count($ids);

        if (!empty($ids)) {
            foreach ($ids as $k => $id) {
                if (isset($id["id"])) {
                    $scrap = \App\Scraper::where("supplier_id", $id["id"])->first();
                    if ($scrap) {
                        $scrap->scraper_priority = $prio;
                        $scrap->save();
                    }
                }
                $prio--;
            }
        }

        return response()->json(["code" => 200]);
    }

    public function getHistory(Request $request)
    {
        $field = $request->get("field", "supplier");
        $value = $request->get("search", "0");

        $history = [];

        if ($value > 0) {
            if ($field == "supplier") {
                $history = ScrapHistory::where("model", \App\Supplier::class)->join("users as u", "u.id", "scrap_histories.created_by")->where("model_id", $value)
                    ->orderBy("created_at", "DESC")
                    ->select("scrap_histories.*", "u.name as created_by_name")
                    ->get()
                    ->toArray();
            }
        }

        return response()->json(["code" => 200, "data" => $history]);

    }

    private static function get_times($default = '19:00', $interval = '+60 minutes')
    {

        $output = [];

        $current = strtotime('00:00');
        $end = strtotime('23:59');

        while ($current <= $end) {
            $time = date('G', $current);
            $output[$time] = date('h.i A', $current);
            $current = strtotime($interval, $current);
        }

        return $output;
    }

    public function getLastRemark(Request $request)
    {
        //START - Purpose : Coment query and write new query for display only manualy added message - DEVTASK-4086
        //$lastRemark = \DB::select("select * from scrap_remarks as sr join ( select max(id) as id from scrap_remarks group by scraper_name) as max_s on sr.id =  max_s.id order by sr.scraper_name asc");

        // $lastRemark = \DB::select("select * from scrap_remarks as sr join ( SELECT MAX(id) AS id FROM scrap_remarks WHERE user_name != '' AND scrap_field IS NULL  GROUP BY scraper_name ) as max_s on sr.id =  max_s.id   join scrapers as scr on scr.scraper_name = sr.scraper_name  join scrap_logs as scr_logs on scr_logs.scraper_id = scr.id  WHERE sr.user_name IS NOT NULL order by sr.scraper_name asc");


        $lastRemark = \DB::select("select * from scrap_remarks as sr join ( SELECT MAX(id) AS id FROM scrap_remarks WHERE user_name != '' AND scrap_field IS NULL  GROUP BY scraper_name ) as max_s on sr.id =  max_s.id   join scrapers as scr on scr.scraper_name = sr.scraper_name  left join scrap_logs as scr_logs on scr_logs.scraper_id = scr.id  WHERE sr.user_name IS NOT NULL order by sr.scraper_name asc");





        $suppliers = DB::table('products')
        ->select(DB::raw('count(*) as inventory'), 'supplier_id as id', DB::raw('max(created_at) as last_date'))
        ->groupBy('supplier_id')->orderBy('created_at', 'desc')->get();

// dd($lastRemark);
//        Supplier::with('inventory', 'lastProduct')->whereIn('id', $ids)->get();
    $data = [];

    foreach ($suppliers as $supplier) {

        if ($supplier->id !== null) {

            $data[$supplier->id]['inventory'] = $supplier->inventory;
            $data[$supplier->id]['last_date'] = $supplier->last_date;

//            $data[$supplier->id]['last_date'] = $supplier->lastProduct !== null ? $supplier->lastProduct->created_at : null;
        }
    }

            foreach ($lastRemark as $lastRemar) {

                if (isset($data[$lastRemar->supplier_id])) {
                    $lastRemar->inventory = $data[$lastRemar->supplier_id]['inventory'];
                    $lastRemar->last_date = $data[$lastRemar->supplier_id]['last_date'];;
                } else {
                    $lastRemar->inventory = 0;
                    $lastRemar->last_date = null;
                }
            }

        //END - DEVTASK-4086

        $download = $request->input('download');
        if (!empty($download)) {
            return Excel::download(new ScrapRemarkExport($lastRemark), 'remarks.csv');
        }

        return response()->json(["code" => 200, "data" => $lastRemark]);
    }

    public function addNote(Request $request)
    {
        try {
            $this->validate($request, [
                'scraper_name' => 'required',
                'remark' => 'required',
            ]);
            $remark = $request->remark;

            if (!empty($remark)) {
                $note = ScrapRemark::create([
                    'scraper_name' => $request->scraper_name,
                    'remark' => $request->remark,
                    'user_name' => Auth::user()->name,
                ]);

                if ($request->hasfile('image')) {
                    $media = MediaUploader::fromSource($request->file('image'))
                        ->toDirectory('scrap-note')
                        ->upload();
                    $note->attachMedia($media, config('constants.media_tags'));
                }
            }
            session()->flash('success', 'Note added successfully.');
            return redirect()->back();
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function serverStatistics(Request $request)
    {

        try {
            $scrappers = Scraper::query();
            $scrap = $scrappers->where('inventory_lifetime', '!=', 0)->where('server_id', '!=', '');

            if ($request->type) {

                if ($request->type == 'server_id_filter') {
                    if (!empty($request->order)) {
                        $scrappers->where('server_id', $request->order);
                    }

                } elseif ($request->type == 'filter_by_text') {
                    if (!empty($request->order)) {
                        $scrappers->where('scraper_name', 'LIKE', '%' . $request->order . '%');
                    }
                } else {
                    $scrappers->orderBy($request->type, $request->order);
                }

            }

            $scrappers = $scrap->paginate(50);

            $servers = Scraper::select('server_id')->whereNotNull('server_id')->groupBy('server_id')->get();

            if ($request->ajax()) {
                return response()->json([
                    'tbody' => view('scrap.partials.scrap-server-status-data', compact('scrappers', 'servers'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                    'links' => (string)$scrappers->render(),
                    'count' => $scrappers->total(),
                ], 200);
            }

            return view('scrap.scrap-server-status', compact('scrappers', 'servers'));
        } catch (\Exception $e) {
            //session()->flash('error', $e->getMessage());
            //return redirect()->back();
        }
    }

    public function serverStatisticsHistory($scrap_name)
    {
        try {
            $scrap_history = Scraper::where(['scraper_name' => $scrap_name])
                ->where('created_at', '>=', Carbon::now()->subDays(25)->toDateTimeString())
                ->get();
            return new JsonResponse(['status' => 1, 'message' => 'Scrapping history', 'data' => $scrap_history, 'name' => $scrap_name]);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    public function getScreenShot(Request $request)
    {
        $screenshots = \App\ScraperScreenshotHistory::where("scraper_id", $request->id)->latest()->paginate(15);

        return view("scrap.partials.screenshot-history", compact('screenshots'));
    }

    public function positionHistory(Request $request)
    {
        $histories = \App\ScraperPositionHistory::where("scraper_id", $request->id)->latest()->paginate(15);

        return view("scrap.partials.position-history", compact('histories'));
    }

    //STRAT - Purpose : Download  Position History - DEVTASK-4086
    public function positionHistorydownload(Request $request)
    {
        $histories = \App\ScraperPositionHistory::where("scraper_id", $request->id)->latest()->get();

        $chatFileData = '';
        $chatFileData .= html_entity_decode("Scraper Position History", ENT_QUOTES, 'UTF-8');
        $chatFileData .= "\n" . "\n";

        if (!$histories->isEmpty()) {
            foreach ($histories as $k => $v) {
                $chatFileData .= html_entity_decode("Scraper Name : " . $v->scraper_name, ENT_QUOTES, 'UTF-8');
                $chatFileData .= "\n";
                $chatFileData .= html_entity_decode("Comment : " . $v->comment, ENT_QUOTES, 'UTF-8');
                $chatFileData .= "\n";
                $chatFileData .= html_entity_decode("Created at : " . $v->created_at, ENT_QUOTES, 'UTF-8');
                $chatFileData .= "\n" . "\n";
            }
        }

        $storagelocation = storage_path() . '/chatMessageFiles';
        if (!is_dir($storagelocation)) {
            mkdir($storagelocation, 0777, true);
        }
        $filename = "Scraper_Position_History.txt";
        $file = $storagelocation . '/' . $filename;
        $txt = fopen($file, "w") or die("Unable to open file!");
        fwrite($txt, $chatFileData);
        fclose($txt);
        if ($chatFileData == '') {
            return response()->json([
                'downloadUrl' => ''
            ]);
        }
        return response()->json([
            'downloadUrl' => $file
        ]);
    }

    //END - DEVTASK-4086

    public function taskList(Request $request)
    {
        $id = $request->id;

        if (isset($request->type) && $request->type == 'brand')
            $developerTasks = \App\DeveloperTask::where("brand_id", $request->id)->latest()->get();
        else
            $developerTasks = \App\DeveloperTask::where("scraper_id", $request->id)->latest()->get();

        $replies = \App\Reply::where("model", "scrap-statistics")->whereNull("deleted_at")->pluck("reply", "id")->toArray();
        return view("scrap.partials.task", compact('developerTasks', 'id', 'replies'));
    }

    public function addReply(Request $request)
    {
        $reply = $request->get("reply");
        $autoReply = [];
        // add reply from here
        if (!empty($reply)) {

            $autoReply = \App\Reply::updateOrCreate(
                ['reply' => $reply, 'model' => 'scrap-statistics', "category_id" => 1],
                ['reply' => $reply]
            );

        }

        return response()->json(["code" => 200, 'data' => $autoReply]);
    }

    public function deleteReply(Request $request)
    {
        $id = $request->get("id");

        if ($id > 0) {
            $autoReply = \App\Reply::where("id", $id)->first();
            if ($autoReply) {
                $autoReply->delete();
            }
        }

        return response()->json([
            "code" => 200, "data" => \App\Reply::where("model", "scrap-statistics")
                ->whereNull("deleted_at")
                ->pluck("reply", "id")
                ->toArray(),
        ]);
    }

    public function taskCreate(Request $request, $id)
    {
        $requestData = new Request();
        $requestData->setMethod('POST');

        $scraper = \App\Scraper::find($id);

        // if(isset($request->type)) $scraper = \App\Brand::find($id);
        if (isset($request->type) && $request->type == 'brand') $scraper = \App\Brand::find($id);

        if ($scraper) {

            $requestData->request->add([
                'priority' => 1,
                'issue' => $request->task_description,
                'status' => 'In Progress',
                'module' => 'Scraper',
                'subject' => $scraper->scraper_name . " - " . $request->task_subject,
                'assigned_to' => $request->get('assigned_to'),
                'scraper_id' => $id,
                'task_type_id' => 1,
            ]);

            if (isset($request->type) && $request->type == 'brand') {
                $requestData->request->add([
                    'brand_id' => $id,
                    'scraper_id' => '',
                    'subject' => 'EXTERNAL SCRAPPER ' . $scraper->name . " - " . $request->task_subject,
                ]);
            }

            app('App\Http\Controllers\DevelopmentController')->issueStore($requestData, 'issue');
        }

        if (isset($request->type) && $request->type == 'brand')
            $developerTasks = \App\DeveloperTask::where("brand_id", $request->id)->latest()->get();
        else
            $developerTasks = \App\DeveloperTask::where("scraper_id", $request->id)->latest()->get();

        $replies = \App\Reply::where("model", "scrap-statistics")->whereNull("deleted_at")->pluck("reply", "id")->toArray();

        return view("scrap.partials.task", compact('developerTasks', 'id', 'replies'));

    }

    public function autoRestart(Request $request)
    {
        if ($request->status == "on") {
            $affected = \DB::table('scrapers')->update(array('auto_restart' => 1));
        } else {
            $affected = \DB::table('scrapers')->update(array('auto_restart' => 0));
        }

        return redirect()->back();
    }

    public function positionAll()
    {
        $histories = \App\ScraperPositionHistory::whereDate('created_at', now()->subDays(7)->format('Y-m-d'))->latest()->get();
        $chatFileData = '';
        $chatFileData .= html_entity_decode("Scraper Position History", ENT_QUOTES, 'UTF-8');
        $chatFileData .= "\n" . "\n";

        if (!$histories->isEmpty()) {
            foreach ($histories as $k => $v) {
                $chatFileData .= html_entity_decode("Scraper Name : " . $v->scraper_name, ENT_QUOTES, 'UTF-8');
                $chatFileData .= "\n";
                $chatFileData .= html_entity_decode("Comment : " . $v->comment, ENT_QUOTES, 'UTF-8');
                $chatFileData .= "\n";
                $chatFileData .= html_entity_decode("Created at : " . $v->created_at, ENT_QUOTES, 'UTF-8');
                $chatFileData .= "\n" . "\n";
            }
        }

        $storagelocation = storage_path() . '/chatMessageFiles';
        if (!is_dir($storagelocation)) {
            mkdir($storagelocation, 0777, true);
        }
        $filename = "Scraper_Position_History.txt";
        $file = $storagelocation . '/' . $filename;
        $txt = fopen($file, "w") or die("Unable to open file!");
        fwrite($txt, $chatFileData);
        fclose($txt);
        if ($chatFileData == '') {
            return response()->json([
                'downloadUrl' => ''
            ]);
        }
        return response()->json([
            'downloadUrl' => $file
        ]);
    }

    public function serverStatusHistory(Request $request)
    {
        $statusHistory = \App\ScraperServerStatusHistory::whereDate("created_at", $request->date)->latest()->get();

        return view("scrap.partials.status-history", compact('statusHistory'));
    }

    public function serverStatusProcess(Request $request)
    {
        $statusHistory = \App\ScraperProcess::whereDate("created_at", $request->date)->orderBy("scraper_name","asc")->latest()->get();

        return view("scrap.partials.process-status-history", compact('statusHistory'));
    }

    public function getScraperServerTiming(Request $request)
    {
        \Artisan::call("check:scraper-running-status");

        $statusHistory = \App\ScraperServerStatusHistory::where("scraper_name", $request->scraper_name)->latest()->get();

        return view("scrap.partials.status-history", compact('statusHistory'));
    }

    public function getLastErrors(Request $request)
    {
        $remarks = \App\ScrapRemark::where("scrap_field", "last_line_error")->where("scrap_id", $request->id)->get();

        return view("scrap.partials.scrap-remarks", compact('remarks'));
    }

    public function logDetails(Request $request)
    {
        $logDetails = \App\ScrapLog::where("scraper_id", $request->scrapper_id)->latest()->get();

        return view("scrap.partials.log-details", compact('logDetails'));
    }

    public function serverHistory(Request $request)
    {
        $requestedDate = request("planned_at", date("Y-m-d"));

        $totalServers = \App\ScraperServerStatusHistory::groupBy('server_id')->pluck('server_id')->toArray();

        $timeSlots = [];
        $listOfServerUsed = [];
        for ($i = 0; $i < 24; $i++) {
            $tms = strlen($i) > 1 ? $i : "0" . $i;
            $timeSlots["$tms"] = $tms;
            // check the scrapper which run on current time
            $scrapers = \App\ScraperServerStatusHistory::runOnGiveTime($requestedDate, $tms);
            if (!$scrapers->isEmpty()) {
                foreach ($scrapers as $s) {
                    $listOfServerUsed["$tms"][$s->server_id][] = [
                        "scraper_name" => $s->scraper_name,
                        "memory_string" => "T: " . $s->total_memory . " U:" . $s->used_memory . " P:" . $s->in_percentage,
                        "pid" => $s->pid
                    ];
                }
            }
        }

        return view("scrap.server-history", compact('totalServers', 'timeSlots', 'requestedDate', 'listOfServerUsed'));
    }

    public function endJob(Request $request)
    {
        $pid = $request->get("pid");
        $server = $request->get("server_id");

        $cmd = 'bash ' . getenv('DEPLOYMENT_SCRIPTS_PATH') . '/scraper-kill.sh ' . $server . ' ' . $pid . ' 2>&1';

        $allOutput = array();
        $allOutput[] = $cmd;
        $result = exec($cmd, $allOutput);

        \Log::info(print_r($result, true));

        return response()->json(["code" => 200, "message" => "Your job has been stopped"]);
    }

}
