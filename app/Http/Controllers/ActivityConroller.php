<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Benchmark;
use App\ListingHistory;
use App\LogScraperVsAi;
use App\User;
use App\Leads;
use App\Order;
use App\Product;
use App\ScrapedProducts;
use App\Helpers\StatusHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ActivityConroller extends Controller
{


    private $dataLabelDay = [
        "12:00 am",
        "1:00 am",
        "2:00 am",
        "3:00 am",
        "4:00 am",
        "5:00 am",
        "6:00 am",
        "7:00 am",
        "8:00 am",
        "9:00 am",
        "10:00 am",
        "11:00 am",
        "12:00 pm",
        "1:00 pm",
        "2:00 pm",
        "3:00 pm",
        "4:00 pm",
        "5:00 pm",
        "6:00 pm",
        "7:00 pm",
        "8:00 pm",
        "9:00 pm",
        "10:00 pm",
        "11:00 pm"
    ];

    private $dataLabelMonth = [
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        30,
        31
    ];

    public function __construct()
    {

        $this->middleware('permission:view-activity', ['only' => ['index', 'store']]);
    }

    public function showActivity(Request $request)
    {
        // Set range start and range end
        $range_start = $request->input('range_start');
        $range_end = $request->input('range_end');

        // Set empty AI activity
        $aiActivity = [];

        // Get total row count for products
//        $products = Product::all();
        $aiActivity[ 'total' ] = 0;
//        $products = Product::whereBetween( 'created_at', [ $range_start . ' 00:00', $range_end . ' 23:59' ] )->get();
        $aiActivity[ 'total_range' ] = 0;

        // Get ai activity
        $logScraperVsAi = LogScraperVsAi::selectRaw('DISTINCT(product_id) AS product_id')->get();
        $aiActivity[ 'ai' ] = $logScraperVsAi->count();
        $logScraperVsAi = LogScraperVsAi::selectRaw('DISTINCT(product_id) AS product_id')->whereBetween('created_at', [$range_start . ' 00:00', $range_end . ' 23:59'])->get();
        $aiActivity[ 'ai_range' ] = $logScraperVsAi->count();

        // Free up memory by unsetting unused variables
        unset($products);
        unset($logScraperVsAi);

        $allActivity = DB::table('listing_histories')->selectRaw('
	        SUM(case when action = "CROP_APPROVAL" then 1 Else 0 End) as crop_approved,
	        SUM(case when action = "CROP_APPROVAL_DENIED" then 1 Else 0 End) as crop_approval_denied,
	        SUM(case when action = "CROP_APPROVAL_CONFIRMATION" then 1 Else 0 End) as crop_approval_confirmation,
            SUM(case when action = "CROP_REJECTED"  then 1 Else 0 End) as crop_rejected,
            SUM(case when action = "CROP_SEQUENCED" then 1 Else 0 End) as crop_ordered,
            SUM(case when action = "LISTING_APPROVAL" then 1 Else 0 End) as attribute_approved,
            SUM(case when action = "LISTING_REJECTED" then 1 Else 0 End) as attribute_rejected,
            SUM(case when action = "MAGENTO_LISTED" then 1 Else 0 End) as magento_listed
	    ');

        $activity = DB::table('listing_histories')->selectRaw('
            user_id,
            SUM(case when action = "CROP_APPROVAL" then 1 Else 0 End) as crop_approved,
            SUM(case when action = "CROP_APPROVAL_DENIED" then 1 Else 0 End) as crop_approval_denied,
            SUM(case when action = "CROP_APPROVAL_CONFIRMATION" then 1 Else 0 End) as crop_approval_confirmation,
            SUM(case when action = "CROP_REJECTED"  then 1 Else 0 End) as crop_rejected,
            SUM(case when action = "CROP_SEQUENCED" then 1 Else 0 End) as crop_ordered,
            SUM(case when action = "LISTING_APPROVAL" then 1 Else 0 End) as attribute_approved,
            SUM(case when action = "LISTING_REJECTED" then 1 Else 0 End) as attribute_rejected,
            SUM(case when action = "MAGENTO_LISTED" then 1 Else 0 End) as magento_listed
        ')->whereNotNull('user_id');

        $ca = Product::where('is_image_processed', 1)
            ->where('is_crop_rejected', 0)
            ->where('is_crop_approved', 0)
            ->where('is_crop_being_verified', 0)
            ->whereDoesntHave('amends')->count();

//        $productStats = DB::table( 'products' )->selectRaw( '(SELECT COUNT(*) FROM products p WHERE is_image_processed = 0 AND is_scraped = 1) as uncropped,
//        (SELECT COUNT(*) FROM products WHERE is_crop_approved = 1 AND is_crop_ordered = 0) as left_for_sequencing ,
//        SUM(is_approved) as total_approvals,
//        SUM(is_listing_rejected) as total_rejections,
//        SUM(is_crop_rejected) as crop_rejected,
//        SUM(isListed) as total_listed,
//        ' . $ca . ' AS crop_approval' )->first();

//        $productStatsDateRange = DB::table( 'products' )->whereBetween( 'created_at', [ $range_start . ' 00:00', $range_end . ' 23:59' ] )->selectRaw( '(SELECT COUNT(*) FROM products p WHERE is_image_processed = 0 AND is_scraped = 1) as uncropped,
//        (SELECT COUNT(*) FROM products WHERE is_crop_approved = 1 AND is_crop_ordered = 0) as left_for_sequencing ,
//        SUM(is_approved) as total_approvals,
//        SUM(is_listing_rejected) as total_rejections,
//        SUM(is_crop_rejected) as crop_rejected,
//        SUM(isListed) as total_listed,
//        ' . $ca . ' AS crop_approval' )->first();

        $productStats = StatusHelper::getStatusCount();
        $productStatsDateRange = StatusHelper::getStatusCountByDateRange($range_start, $range_end);

        if (is_array($request->get('selected_user'))) {
            $activity = $activity->whereIn('user_id', $request->get('selected_user'));
        }

        $users = $this->getUserArray();
        $selected_user = $request->input('selected_user');

        $scrapCount = new ScrapedProducts();
        $inventoryCount = new ScrapedProducts();
        $rejectedListingsCount = Product::where('is_listing_rejected', 1);

        // Get total number of scraped products
        $sqlScrapedProductsInStock = "
                SELECT
                    COUNT(DISTINCT(ls.sku)) as ttl
                FROM
                    suppliers s
                JOIN 
                    scrapers sc 
                ON 
                    s.id=sc.supplier_id    
                JOIN 
                    scraped_products ls 
                ON 
                    ls.website=sc.scraper_name
                WHERE
                    s.supplier_status_id=1 AND 
                    ls.validated=1 AND
                    ls.website!='internal_scraper' AND
                    ls.last_inventory_at > DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY) 
            ";
        $resultScrapedProductsInStock = DB::select($sqlScrapedProductsInStock);

        if ($range_start != '' && $range_end != '') {
            $activity = $activity->where(function ($query) use ($range_end, $range_start) {
                $query->whereBetween('created_at', [$range_start . ' 00:00', $range_end . ' 23:59']);
            });


            $allActivity = $allActivity->whereBetween('created_at', [$range_start . ' 00:00', $range_end . ' 23:59']);
            $scrapCount = $scrapCount->whereBetween('created_at', [$range_start . ' 00:00', $range_end . ' 23:59']);
            $inventoryCount = $inventoryCount->whereBetween('last_inventory_at', [$range_start . ' 00:00', $range_end . ' 23:59']);
            $rejectedListingsCount = $rejectedListingsCount->whereBetween('listing_rejected_on', [$range_start . ' 00:00', $range_end . ' 23:59']);
        }

        if (!$range_start || !$range_end) {
            $inventoryCount = $inventoryCount->whereRaw('TIMESTAMPDIFF(HOUR, last_inventory_at, NOW())<= 48');
            $scrapCount = $scrapCount->where('created_at', 'LIKE', "%" . date('Y-m-d') . "%");
//            $rejectedListingsCount = $rejectedListingsCount->where('listing_rejected_on', 'LIKE', "%".date('Y-m-d')."%");
        }

        $scrapCount = $scrapCount->count();
        $inventoryCount = $inventoryCount->count();
        $rejectedListingsCount = $rejectedListingsCount->count();

        $allActivity = $allActivity->first();
        $userActions = $activity->groupBy('user_id')->get();

        $cropCountPerMinute = Product::whereRaw('TIMESTAMPDIFF(DAY, cropped_at, NOW()) IN (0,1)')->count();
        $cropCountPerMinute = round($cropCountPerMinute / 1440, 4);

        return view('activity.index', compact('resultScrapedProductsInStock', 'aiActivity', 'userActions', 'users', 'selected_user', 'range_end', 'range_start', 'allActivity', 'scrapCount', 'inventoryCount', 'rejectedListingsCount', 'productStats', 'productStatsDateRange', 'cropCountPerMinute'));
    }

    public function showGraph(Request $request)
    {

//		return $request->all();

        $data[ 'date_type' ] = $request->input('date_type') ?? 'week';

        $data[ 'week_range' ] = $request->input('week_range') ?? date('Y-\WW');
        $data[ 'month_range' ] = $request->input('month_range') ?? date('Y-m');

        if ($data[ 'date_type' ] == 'week') {

            $weekRange = $this->getStartAndEndDateByWeek($data[ 'week_range' ]);
            $start = $weekRange[ 'start_date' ];
            $end = $weekRange[ 'end_date' ];

            $workDoneResult = DB::select('
									SELECT WEEKDAY(created_at) as xaxis ,COUNT(*) AS total FROM
								 		(SELECT DISTINCT activities.subject_id,activities.subject_type,activities.created_at
								  		 FROM activities
								  		 WHERE activities.description = "create"
								  		 AND activities.created_at BETWEEN ? AND ?)
								    AS SUBQUERY
								   	GROUP BY WEEKDAY(created_at);
							', [$start, $end]);

            $benchmarkResult = DB::select('
							SELECT WEEKDAY(for_date) as day,
								sum(selections + searches + attributes + supervisor + imagecropper + lister + approver + inventory) as total
							FROM benchmarks
							WHERE
							created_at BETWEEN ? AND ?
							GROUP BY WEEKDAY(for_date);
						', [$start, $end]);

            $workDone = [];
            $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

            foreach ($workDoneResult as $item) {
                $workDone[ $dowMap[ $item->xaxis ] ] = $item->total;
            }

            $benchmark = [];

            foreach ($benchmarkResult as $item) {
                $benchmark[ $dowMap[ $item->day ] ] = $item->total;
            }

        } else {

            $monthRange = $this->getStartAndEndDateByMonth($data[ 'month_range' ]);
            $start = $monthRange[ 'start_date' ];
            $end = $monthRange[ 'end_date' ];

            $workDoneResult = DB::select('
									SELECT DAYOFMONTH(created_at) as xaxis ,COUNT(*) AS total FROM
								 		(SELECT DISTINCT activities.subject_id,activities.subject_type,activities.created_at
								  		 FROM activities
								  		 WHERE activities.description = "create"
								  		 AND activities.created_at BETWEEN ? AND ?)
								    AS SUBQUERY
								   	GROUP BY DAYOFMONTH(created_at);
							', [$start, $end]);

            $benchmarkResult = DB::select('
							SELECT DAYOFMONTH(for_date) as day,
								sum(selections + searches + attributes + supervisor + imagecropper + lister + approver + inventory) as total
							FROM benchmarks
							WHERE
							created_at BETWEEN ? AND ?
							GROUP BY DAYOFMONTH(for_date);
						', [$start, $end]);

            foreach ($workDoneResult as $item) {
                $workDone[ $item->xaxis ] = $item->total;
            }

            foreach ($benchmarkResult as $item) {
                $benchmark[ $item->day ] = $item->total;
            }
        }


//		return $benchmark;

        $data[ 'benchmark' ] = $benchmark ?? [];
        $data[ 'workDone' ] = $workDone ?? [];

        return view('activity.graph', $data);

    }

    public function showUserGraph(Request $request)
    {

//		return $request->all();

        $data[ 'users' ] = $this->getUserArray();
        $data[ 'selected_user' ] = $request->input('selected_user') ?? 3;

        $data[ 'date_type' ] = $request->input('date_type') ?? 'day';

        $data[ 'day_range' ] = $request->input('day_range') ?? date('Y-m-d');
        $data[ 'month_range' ] = $request->input('month_range') ?? date('Y-m');

        if ($data[ 'date_type' ] == 'day') {


            $start = $data[ 'day_range' ] . " 00:00:00.000000";
            $end = $data[ 'day_range' ] . " 23:59:59.000000";

            $workDoneResult = DB::select('
									SELECT HOUR(created_at) as xaxis,subject_type ,COUNT(*) AS total FROM
								 		(SELECT DISTINCT activities.subject_id,activities.subject_type,activities.created_at
								  		 FROM activities
								  		 WHERE activities.description = "create"
								  		 AND activities.causer_id = ?
								  		 AND activities.created_at BETWEEN ? AND ?)
								    AS SUBQUERY
								   	GROUP BY HOUR(created_at),subject_type ORDER By xaxis;
							', [$data[ 'selected_user' ], $start, $end]);

            $workDone = [];

            foreach ($workDoneResult as $item) {
                $workDone[ $item->subject_type ][ $item->xaxis ] = $item->total;
            }


            foreach ($workDone as $subject_type => $subject_type_array) {

                for ($i = 0; $i <= 23; $i++) {
                    $workDone[ $subject_type ][ $i ] = $subject_type_array[ $i ] ?? 0;
//					$workDone[ $subject_type ][gmdate("g:i a",$i*3600 )] = $subject_type_array[$i] ?? 0;
                }
            }

        } else {

            $monthRange = $this->getStartAndEndDateByMonth($data[ 'month_range' ]);
            $start = $monthRange[ 'start_date' ];
            $end = $monthRange[ 'end_date' ];

            $workDoneResult = DB::select('
									SELECT DAYOFMONTH(created_at) as xaxis,subject_type ,COUNT(*) AS total FROM
								 		(SELECT DISTINCT activities.subject_id,activities.subject_type,activities.created_at
								  		 FROM activities
								  		 WHERE activities.description = "create"
								  		 AND activities.causer_id = ?
								  		 AND activities.created_at BETWEEN ? AND ?)
								    AS SUBQUERY
								   	GROUP BY DAYOFMONTH(created_at),subject_type ORDER By xaxis;
							', [$data[ 'selected_user' ], $start, $end]);

            $workDone = [];

            foreach ($workDoneResult as $item) {
                $workDone[ $item->subject_type ][ $item->xaxis ] = $item->total;
            }


            foreach ($workDone as $subject_type => $subject_type_array) {

                for ($i = 1; $i <= 31; $i++) {

                    $workDone[ $subject_type ][ $i ] = $subject_type_array[ $i ] ?? 0;
                }
            }
        }

        $data[ 'workDone' ] = $workDone ?? [];
        $data[ 'dataLabel' ] = $data[ 'date_type' ] == 'day' ? $this->dataLabelDay : $this->dataLabelMonth;
//		return $data;

        return view('activity.graph-user', $data);

    }

    public function getUserArray()
    {

        $users = User::all();

        $userArray = [];

        foreach ($users as $user) {

            $userArray[ ((string)$user->id) ] = $user->name;
        }

        return $userArray;
    }


    public static function create($subject_id, $subject_type, $description)
    {

        $activity = new Activity();

        $activity->create([
            'subject_id' => $subject_id,
            'subject_type' => $subject_type,
            'causer_id' => \Auth::id() ?? 0,
            'description' => $description
        ]);

    }


    function getStartAndEndDateByWeek($week_range)
    {

        $arr = explode('-', $week_range);

        $week = str_replace('W', '', $arr[ 1 ]);
        $year = $arr[ 0 ];

        $dateTime = new \DateTime();
        $dateTime->setISODate($year, $week);
        $result[ 'start_date' ] = $dateTime->format('Y-m-d') . " 00:00:00.000000";
        $dateTime->modify('+6 days');
        $result[ 'end_date' ] = $dateTime->format('Y-m-d') . " 23:59:59.000000";

        return $result;
    }

    function getStartAndEndDateByMonth($month_range)
    {

        $arr = explode('-', $month_range);

        $year = $arr[ 0 ];
        $month = $arr[ 1 ];

        $dateTime = new \DateTime();
        $dateTime->setDate($year, $month, 1);
        $result[ 'start_date' ] = $dateTime->format('Y-m-d') . " 00:00:00.000000";
        $dateTime->modify('+1 month');
        $dateTime->modify('-1 days');
        $result[ 'end_date' ] = $dateTime->format('Y-m-d') . " 23:59:59.000000";

        return $result;
    }

    public function recentActivities(Request $request){
        $productStats = DB::table('productactivities')
        ->where('status_id',$request->type)
        ->whereDate('created_at', '>', Carbon::now()->subDays(10))
        ->orderBy('created_at','DESC')
        ->get();
        return $productStats;
    }

}

/*$total_data['selection']    += isset( $results['selection'] ) ? $results['selection'] : 0;
		$total_data['searcher']     += isset( $results['searcher'] ) ? $results['searcher'] : 0;
		$total_data['attribute']    += isset( $results['attribute'] ) ? $results['attribute'] : 0;
		$total_data['supervisor']   += isset( $results['supervisor'] ) ? $results['supervisor'] : 0;
		$total_data['imagecropper'] += isset( $results['imagecropper'] ) ? $results['imagecropper'] : 0;
		$total_data['lister']       += isset( $results['lister'] ) ? $results['lister'] : 0;
		$total_data['approver']     += isset( $results['approver'] ) ? $results['approver'] : 0;
		$total_data['inventory']    += isset( $results['inventory'] ) ? $results['inventory'] : 0;
		$total_data['sales']        += isset( $results['sales'] ) ? $results['sales'] : 0;*/
