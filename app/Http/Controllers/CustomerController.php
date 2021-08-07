<?php

namespace App\Http\Controllers;

use App\Complaint;
use Dompdf\Dompdf;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Imports\CustomerImport;
use App\Exports\CustomersExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Mails\Manual\CustomerEmail;
use App\Mails\Manual\RefundProcessed;
use App\Mails\Manual\OrderConfirmation;
use App\Mails\Manual\AdvanceReceipt;
use App\Mails\Manual\IssueCredit;
use Illuminate\Support\Facades\Mail;
use App\Customer;
use App\Suggestion;
use App\SuggestedProduct;
use App\Setting;
use App\Leads;
use App\ErpLeads;
use App\Order;
use App\Status;
use App\Product;
use App\Brand;
use App\Supplier;
use App\ApiKey;
use App\Category;
use App\User;
use App\MessageQueue;
use App\Message;
use App\Helpers;
use App\Reply;
use App\Email;
use App\Instruction;
use App\ChatMessage;
use App\ReplyCategory;
use App\CallRecording;
use App\CommunicationHistory;
use App\InstructionCategory;
use App\OrderStatus as OrderStatuses;
use App\ReadOnly\PurchaseStatus;
use App\ReadOnly\SoloNumbers;
use App\EmailAddress;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Webklex\IMAP\Client;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;
use Auth;
use App\QuickSellGroup;
use GuzzleHttp\Client as GuzzleClient;
use Plank\Mediable\Media as PlunkMediable;
use App\CustomerAddressData;
use App\StoreWebsite;

class CustomerController extends Controller
{
	
	CONST DEFAULT_FOR = 1; //For Customer

    public function __construct()
    {
        // $this->middleware('permission:customer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

//    public function __construct() {
//      $this->middleware('permission:customer', ['only' => ['index','show']]);
//    }
    public function add_customer_address(Request $request){
        $apply_job =  CustomerAddressData::create([
            'customer_id' => $request->customer_id,
            'entity_id' => $request->entity_id,
            'parent_id' => $request->parent_id,
            'address_type' => $request->address_type,
            'region' => $request->region,
            'region_id' => $request->region_id,
            'postcode' => $request->postcode,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'company' => $request->company,
            'country_id' => $request->country_id,
            'telephone' => $request->telephone,
            'prefix' => $request->prefix,
            'street' => $request->street,
        ]);
//        dd($apply_job);
        $apply_job->save();
        return $apply_job;
    }
    public function index(Request $request)
    {
        $complaints = Complaint::whereNotNull('customer_id')->pluck('complaint', 'customer_id')->toArray();
        $instructions = Instruction::with('remarks')->orderBy('is_priority', 'DESC')->orderBy('created_at', 'DESC')->select(['id', 'instruction', 'customer_id', 'assigned_to', 'pending', 'completed_at', 'verified', 'is_priority', 'created_at'])->get()->groupBy('customer_id')->toArray();
        $orders = Order::latest()->select(['id', 'customer_id', 'order_status', 'order_status_id', 'created_at'])->get()->groupBy('customer_id')->toArray();
        $order_stats = DB::table('orders')->selectRaw('order_status, COUNT(*) as total')->whereNotNull('order_status')->groupBy('order_status')->get();

        $totalCount = 0;
        foreach ($order_stats as $order_stat) {
            $totalCount += $order_stat->total;
        }

        $orderStatus = [
            'order received',
            'follow up for advance',
            'prepaid',
            'proceed without advance',
            'pending purchase (advance received)',
            'purchase complete',
            'product shipped from italy',
            'product in stock',
            'product shipped to client',
            'delivered',
            'cancel',
            'refund to be processed',
            'refund credited'
        ];

        $finalOrderStats = [];
        foreach ($orderStatus as $status) {
            foreach ($order_stats as $order_stat) {
                if ($status == strtolower($order_stat->order_status)) {
                    $finalOrderStats[] = $order_stat;
                }
            }
        }

        foreach ($order_stats as $order_stat) {
            if (!in_array(strtolower($order_stat->order_status), $orderStatus)) {
                $finalOrderStats[] = $order_stat;
            }
        }

        $order_stats = $finalOrderStats;

        $finalOrderStats = [];
        foreach ($order_stats as $key => $order_stat) {
            $finalOrderStats[] = array(
                $order_stat->order_status,
                $order_stat->total,
                ($order_stat->total / $totalCount) * 100,
                [
                    '#CCCCCC',
                    '#95a5a6',
                    '#b2b2b2',
                    '#999999',
                    '#2c3e50',
                    '#7f7f7f',
                    '#666666',
                    '#4c4c4c',
                    '#323232',
                    '#191919',
                    '#000000',
                    '#414a4c',
                    '#353839',
                    '#232b2b',
                    '#34495e',
                    '#7f8c8d',
                ][ $key ]
            );
        }

        $order_stats = $finalOrderStats;

        // dd(';s');
        // $customers = Customer::with('whatsapps')->get();
        // $messages = DB::table('chat_messages')->selectRaw('id, message, customer_id GROUP BY customer_id')->get();
        // dd($messages);

        $results = $this->getCustomersIndex($request);
        $term = $request->input('term');
        $reply_categories = ReplyCategory::all();
        $api_keys = ApiKey::select('number')->get();

        $type = $request->type ?? '';

        $orderby = 'desc';
        if ($request->orderby == '') {
            $orderby = 'asc';
        }

        $customers_all = Customer::all();
        $customer_names = Customer::select(['name'])->get()->toArray();

        $category_suggestion = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple', 'multiple' => 'multiple'])
            ->renderAsDropdown();

        $brands = Brand::all()->toArray();

        foreach ($customer_names as $name) {
            $search_suggestions[] = $name[ 'name' ];
        }

        $users_array = Helpers::getUserArray(User::all());

        $last_set_id = MessageQueue::max('group_id');

        $queues_total_count = MessageQueue::where('status', '!=', 1)->where('group_id', $last_set_id)->count();
        $queues_sent_count = MessageQueue::where('sent', 1)->where('status', '!=', 1)->where('group_id', $last_set_id)->count();

        $start_time = $request->range_start ? "$request->range_start 00:00" : Carbon::now()->subDay();
        $end_time = $request->range_end ? "$request->range_end 23:59" : Carbon::now()->subDay();

        $allCustomers = $results[ 0 ]->pluck("id")->toArray();

        // Get all sent broadcasts from the past month
        $sbQuery = DB::select("select MIN(group_id) AS minGroup, MAX(group_id) AS maxGroup from message_queues where sent = 1 and created_at>'" . date('Y-m-d H:i:s', strtotime('1 month ago')) . "'");

        // Add broadcasts to array
        $broadcasts = [];
        if ($sbQuery !== null) {
            // Get min and max
            $minBroadcast = $sbQuery[ 0 ]->minGroup;
            $maxBroadcast = $sbQuery[ 0 ]->maxGroup;

            // Deduct 2 from min
            $minBroadcast = $minBroadcast - 2;

            for ($i = $minBroadcast; $i <= $maxBroadcast; $i++) {
                $broadcasts[] = $i;
            }
        }

        $shoe_size_group = Customer::selectRaw('shoe_size, count(id) as counts')
                                    ->whereNotNull('shoe_size')
                                    ->groupBy('shoe_size')
                                    ->pluck('counts', 'shoe_size');

        $clothing_size_group = Customer::selectRaw('clothing_size, count(id) as counts')
                                        ->whereNotNull('clothing_size')
                                        ->groupBy('clothing_size')
                                        ->pluck('counts', 'clothing_size');

        $groups = QuickSellGroup::select('id','name','group')->orderby('name','asc')->get();

        return view('customers.index', [
            'customers' => $results[ 0 ],
            'customers_all' => $customers_all,
            'customer_ids_list' => json_encode($results[ 1 ]),
            'users_array' => $users_array,
            'instructions' => $instructions,
            'term' => $term,
            'orderby' => $orderby,
            'type' => $type,
            'queues_total_count' => $queues_total_count,
            'queues_sent_count' => $queues_sent_count,
            'search_suggestions' => $search_suggestions,
            'reply_categories' => $reply_categories,
            'orders' => $orders,
            'api_keys' => $api_keys,
            'category_suggestion' => $category_suggestion,
            'brands' => $brands,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'leads_data' => $results[ 2 ],
            'order_stats' => $order_stats,
            'complaints' => $complaints,
            'shoe_size_group' => $shoe_size_group,
            'clothing_size_group' => $clothing_size_group,
            'broadcasts' => $broadcasts,
            'groups' => $groups,
        ]);
    }

    public function getCustomersIndex(Request $request)
    {
        // Set search term
        $term = $request->term;

        // Set delivery status
        $delivery_status = [
            'Follow up for advance',
            'Proceed without Advance',
            'Advance received',
            'Cancel',
            'Prepaid',
            'Product Shiped form Italy',
            'In Transist from Italy',
            'Product shiped to Client',
            'Delivered'
        ];

        // Set empty clauses for later usage
        $orderWhereClause = '';
        $searchWhereClause = '';
        $filterWhereClause = '';
        $leadsWhereClause = '';

        if (!empty($term)) {
            $searchWhereClause = " AND (customers.name LIKE '%$term%' OR customers.phone LIKE '%$term%' OR customers.instahandler LIKE '%$term%')";
            $orderWhereClause = "WHERE orders.order_id LIKE '%$term%'";
        }

        if ($request->get('shoe_size')) {
            $searchWhereClause .= " AND customers.shoe_size = '".$request->get('shoe_size')."'";
        }

        if ($request->get('clothing_size')) {
            $searchWhereClause .= " AND customers.clothing_size = '".$request->get('clothing_size')."'";
        }

        if ($request->get('shoe_size_group')) {
            $searchWhereClause .= " AND customers.shoe_size = '".$request->get('shoe_size_group')."'";
        }

        if ($request->get('clothing_size_group')) {
            $searchWhereClause .= " AND customers.clothing_size = '".$request->get('clothing_size_group')."'";
        }

        if ($request->get('customer_id')) {
            $searchWhereClause .= " AND customers.id LIKE '%".$request->get('customer_id')."%'";
        }

        if ($request->get('customer_name')) {
            $searchWhereClause .= " AND customers.name LIKE '%".$request->get('customer_name')."%'";
        }

        $orderby = 'DESC';

        if ($request->input('orderby')) {
            $orderby = 'ASC';
        }
        $sortby = 'communication';

        $sortBys = [
            'name' => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'instagram' => 'instahandler',
            'lead_created' => 'lead_created',
            'order_created' => 'order_created',
            'rating' => 'rating',
            'communication' => 'communication'
        ];

        if (isset($sortBys[ $request->input('sortby') ])) {
            $sortby = $sortBys[ $request->input('sortby') ];
        }

        $start_time = $request->range_start ? "$request->range_start 00:00" : '';
        $end_time = $request->range_end ? "$request->range_end 23:59" : '';

        if ($start_time != '' && $end_time != '') {
            $filterWhereClause = " AND last_communicated_at BETWEEN '" . $start_time . "' AND '" . $end_time . "'";
        }

        if ($request->type == 'unread' || $request->type == 'unapproved') {
            $join = "RIGHT";
            $type = $request->type == 'unread' ? 0 : ($request->type == 'unapproved' ? 1 : 0);
            $orderByClause = " ORDER BY is_flagged DESC, message_status ASC, last_communicated_at $orderby";
            $filterWhereClause = " AND chat_messages.status = $type";
            $messageWhereClause = " WHERE chat_messages.status != 7 AND chat_messages.status != 8 AND chat_messages.status != 9 AND chat_messages.status != 10";
            // $messageWhereClause = " WHERE chat_messages.status = $type";

            if ($start_time != '' && $end_time != '') {
                $filterWhereClause = " AND (last_communicated_at BETWEEN '" . $start_time . "' AND '" . $end_time . "') AND message_status = $type";
            }
        } else {
            if (
                strtolower($request->get('type')) === 'advance received' ||
                strtolower($request->get('type')) === 'cancel' ||
                strtolower($request->get('type')) === 'delivered' ||
                strtolower($request->get('type')) === 'follow up for advance' ||
                strtolower($request->get('type')) === 'high priority' ||
                strtolower($request->get('type')) === 'in transist from italy' ||
                strtolower($request->get('type')) === 'prepaid' ||
                strtolower($request->get('type')) === 'proceed without advance' ||
                strtolower($request->get('type')) === 'product shiped form italy' ||
                strtolower($request->get('type')) === 'product shiped to client' ||
                strtolower($request->get('type')) === 'refund credited' ||
                strtolower($request->get('type')) === 'refund dispatched' ||
                strtolower($request->get('type')) === 'refund to be processed'
            ) {
                $join = 'LEFT';
                $orderByClause = " ORDER BY is_flagged DESC, last_communicated_at $orderby";
                $messageWhereClause = ' WHERE chat_messages.status != 7 AND chat_messages.status != 8 AND chat_messages.status != 9';
                if ($orderWhereClause) {
                    $orderWhereClause .= ' AND ';
                } else {
                    $orderWhereClause = ' WHERE ';
                }
                $orderWhereClause .= 'orders.order_status = "' . $request->get('type') . '"';
                $filterWhereClause = ' AND order_status = "' . $request->get('type') . '"';

            } else {
                if (strtolower($request->type) != 'new' && strtolower($request->type) != 'delivery' && strtolower($request->type) != 'refund to be processed' && strtolower($request->type) != '') {
                    $join = 'LEFT';
                    $orderByClause = " ORDER BY is_flagged DESC, last_communicated_at $orderby";
                    $messageWhereClause = " WHERE chat_messages.status != 7 AND chat_messages.status != 8 AND chat_messages.status != 9";

                    if ($request->type == '0') {
                        $leadsWhereClause = ' AND lead_status IS NULL';
                    } else {
                        $leadsWhereClause = " AND lead_status = $request->type";
                    }
                } else {
                    if ($sortby === 'communication') {
                        $join = "LEFT";
                        $orderByClause = " ORDER BY is_flagged DESC, last_communicated_at $orderby";
                        $messageWhereClause = " WHERE chat_messages.status != 7 AND chat_messages.status != 8 AND chat_messages.status != 9";
                    }
                }
            }
        }

        $assignedWhereClause = '';
        if (Auth::user()->hasRole('Customer Care')) {
            $user_id = Auth::id();
            $assignedWhereClause = " AND id IN (SELECT customer_id FROM user_customers WHERE user_id = $user_id)";
        }

        if (!$orderByClause) {
            $orderByClause = ' ORDER BY instruction_completed_at DESC';
        } else {
            $orderByClause .= ', instruction_completed_at DESC';
        }

        $sql = '
            SELECT
                customers.id,
                customers.email,
                customers.frequency,
                customers.reminder_message,
                customers.name,
                customers.phone,
                customers.is_blocked,
                customers.is_flagged,
                customers.is_error_flagged,
                customers.is_priority,
                customers.instruction_completed_at,
                customers.whatsapp_number,
                customers.do_not_disturb,
                chat_messages.*,
                chat_messages.status AS message_status,
                chat_messages.number,
                orders.*,
                order_products.*,
                leads.*
            FROM
                customers
            LEFT JOIN
                (
                    SELECT
                        chat_messages.id AS message_id,
                        chat_messages.customer_id,
                        chat_messages.number,
                        chat_messages.message,
                        chat_messages.sent AS message_type,
                        chat_messages.status,
                        chat_messages.created_at,
                        chat_messages.created_at AS last_communicated_at
                    FROM
                        chat_messages
                    ' . $messageWhereClause . '
                ) AS chat_messages
            ON 
                customers.id=chat_messages.customer_id AND 
                chat_messages.message_id=(
                    SELECT
                        MAX(id)
                    FROM
                        chat_messages
                    ' . $messageWhereClause . (!empty($messageWhereClause) ? ' AND ' : '') . '
                        chat_messages.customer_id=customers.id
                    GROUP BY
                        chat_messages.customer_id
                )
            LEFT JOIN
                (
                    SELECT 
                        MAX(orders.id) as order_id, 
                        orders.customer_id, 
                        MAX(orders.created_at) as order_created, 
                        orders.order_status as order_status 
                    FROM 
                        orders
                    ' . $orderWhereClause . ' 
                    GROUP BY 
                        customer_id
                ) as orders
            ON
                customers.id=orders.customer_id
            LEFT JOIN
                (
                    SELECT 
                        order_products.order_id as purchase_order_id, 
                        order_products.purchase_status
                    FROM 
                        order_products 
                    GROUP BY 
                        purchase_order_id
                ) as order_products
            ON 
                orders.order_id=order_products.purchase_order_id
            LEFT JOIN
                (
                    SELECT 
                        MAX(id) as lead_id, 
                        leads.customer_id, 
                        leads.rating as lead_rating, 
                        MAX(leads.created_at) as lead_created, 
                        leads.status as lead_status
                    FROM 
                        leads
                    GROUP BY 
                        customer_id
                ) AS leads
            ON 
                customers.id = leads.customer_id
            WHERE
                customers.deleted_at IS NULL AND
                customers.id IS NOT NULL
            ' . $searchWhereClause . '
            ' . $filterWhereClause . '
            ' . $leadsWhereClause . '
            ' . $assignedWhereClause . '
            ' . $orderByClause . '
        ';
        $customers = DB::select($sql);

        echo "<!-- ";
        echo $sql;
        echo "-->";

        $oldSql = '
            SELECT
              *
            FROM
            (
                SELECT 
                    customers.id, 
                    customers.frequency, 
                    customers.reminder_message, 
                    customers.name, 
                    customers.phone, 
                    customers.is_blocked, 
                    customers.is_flagged, 
                    customers.is_error_flagged, 
                    customers.is_priority, 
                    customers.deleted_at, 
                    customers.instruction_completed_at,
                    order_status,
                    purchase_status,
                    (
                    SELECT 
                            mm5.status 
                        FROM 
                            leads mm5 
                        WHERE 
                            mm5.id=lead_id
                    ) AS lead_status,
                    lead_id,
                    (
                    SELECT
                            mm3.id 
                        FROM 
                            chat_messages mm3 
                        WHERE 
                            mm3.id=message_id
                    ) AS message_id,
                    (
                    SELECT 
                            mm1.message 
                        FROM 
                            chat_messages mm1 
                        WHERE mm1.id=message_id
                    ) as message,
                    (
                    SELECT
                            mm2.status 
                        FROM 
                            chat_messages mm2 
                        WHERE
                            mm2.id = message_id
                    ) AS message_status,
                    (
                    SELECT 
                            mm4.sent 
                        FROM 
                            chat_messages mm4 
                        WHERE 
                            mm4.id = message_id
                    ) AS message_type,
                    (
                    SELECT 
                            mm2.created_at 
                        FROM 
                            chat_messages mm2 
                        WHERE 
                            mm2.id = message_id
                    ) as last_communicated_at
                FROM
                    (
                        SELECT
                            *
                        FROM 
                            customers
                        LEFT JOIN
                            (
                                SELECT 
                                    MAX(id) as lead_id, 
                                    leads.customer_id as lcid, 
                                    leads.rating as lead_rating, 
                                    MAX(leads.created_at) as lead_created, 
                                    leads.status as lead_status
                                FROM 
                                    leads
                                GROUP BY 
                                    customer_id
                            ) AS leads
                        ON 
                            customers.id = leads.lcid
                        LEFT JOIN
                            (
                                SELECT 
                                    MAX(id) as order_id, 
                                    orders.customer_id as ocid, 
                                    MAX(orders.created_at) as order_created, 
                                    orders.order_status as order_status 
                                FROM 
                                    orders ' . $orderWhereClause . ' 
                                GROUP BY 
                                    customer_id
                            ) as orders
                        ON
                            customers.id = orders.ocid
                        LEFT JOIN
                            (
                                SELECT 
                                    order_products.order_id as purchase_order_id, 
                                    order_products.purchase_status 
                                FROM 
                                    order_products 
                                GROUP BY 
                                    purchase_order_id
                            ) as order_products
                        ON 
                            orders.order_id = order_products.purchase_order_id
                        ' . $join . ' JOIN
                            (
                                SELECT 
                                    MAX(id) as message_id, 
                                    customer_id, 
                                    message, 
                                    MAX(created_at) as message_created_At 
                                FROM 
                                    chat_messages ' . $messageWhereClause . ' 
                                GROUP BY 
                                    customer_id 
                                ORDER BY 
                                    chat_messages.created_at ' . $orderby . '
                            ) AS chat_messages
                        ON 
                            customers.id = chat_messages.customer_id
                    ) AS customers
                WHERE
                    deleted_at IS NULL
                ) AND (
                    id IS NOT NULL
                )
                ' . $searchWhereClause . '
          ) AS customers
          ' . $filterWhereClause . $leadsWhereClause .
            $assignedWhereClause .
            $orderByClause;

        // dd($customers);

        // $customers = DB::select('
        // 						SELECT * FROM
        //             (SELECT *
        //
        //             FROM (
        //               SELECT * FROM customers
        //
        //               LEFT JOIN (
        //                 SELECT MAX(id) as lead_id, leads.customer_id as lcid, leads.rating as lead_rating, MAX(leads.created_at) as lead_created, leads.status as lead_status
        //                 FROM leads
        //                 GROUP BY customer_id
        //               ) AS leads
        //               ON customers.id = leads.lcid
        //
        //               LEFT JOIN
        //                 (SELECT MAX(id) as order_id, orders.customer_id as ocid, MAX(orders.created_at) as order_created, orders.order_status as order_status FROM orders '. $orderWhereClause .' GROUP BY customer_id) as orders
        //                   LEFT JOIN (SELECT order_products.order_id as purchase_order_id, order_products.purchase_status FROM order_products GROUP BY purchase_order_id) as order_products
        //                   ON orders.order_id = order_products.purchase_order_id
        //               ON customers.id = orders.ocid
        //
        //               ' . $join . ' JOIN (SELECT MAX(id) as message_id, customer_id, message, MAX(created_at) as message_created_At FROM chat_messages ' . $messageWhereClause . ' ORDER BY chat_messages.created_at ' . $orderby . ') AS chat_messages
        //               ON customers.id = chat_messages.customer_id
        //
        //
        //             ) AS customers
        //             WHERE (deleted_at IS NULL) AND (id IS NOT NULL)
        //
        //             ' . $searchWhereClause . '
        //           ) AS customers
        //           ' . $filterWhereClause . $leadsWhereClause . ';
        // 				');


        // dd($customers);

        $leads_data = DB::select('
                      SELECT COUNT(*) AS total,
                      (SELECT mm1.status FROM leads mm1 WHERE mm1.id = lead_id) as lead_final_status
                       FROM customers

                      LEFT JOIN (
                        SELECT MAX(id) as lead_id, leads.customer_id as lcid, leads.rating as lead_rating, MAX(leads.created_at) as lead_created, leads.status as lead_status
                        FROM leads
                        GROUP BY customer_id
                      ) AS leads
                      ON customers.id = leads.lcid

                      WHERE (deleted_at IS NULL) AND (id IS NOT NULL)
                      GROUP BY lead_final_status;
  							');


        // dd($leads_data);
        $ids_list = [];

        // $leads_data = [0, 0, 0, 0, 0, 0, 0];
        foreach ($customers as $customer) {
            if ($customer->id != null) {
                $ids_list[] = $customer->id;

                // $lead_status = $customer->lead_status == null ? '0' : $customer->lead_status;
                //
                // $leads_data[$lead_status] += 1;
            }
        }


        // dd($leads_data);

        // if ($start_time != '' && $end_time != '') {
        //   $customers = $customers->whereBetween('chat_message_created_at', [$start_time, $end_time])->paginate(Setting::get('pagination'));
        // } else if ($request->type == 'unapproved') {
        //   // dd($customers->get());
        //   $customers = $customers->where('status', 1)->paginate(Setting::get('pagination'));
        // } else {
        //   $customers = $customers->paginate(Setting::get('pagination'));
        // }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = empty(Setting::get('pagination')) ? 25 : Setting::get('pagination');
        $currentItems = array_slice($customers, $perPage * ($currentPage - 1), $perPage);
        $customers = new LengthAwarePaginator($currentItems, count($customers), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        return [$customers, $ids_list, $leads_data];
    }

    public function customerstest(Request $request)
    {
        $instructions = Instruction::with('remarks')->orderBy('is_priority', 'DESC')->orderBy('created_at', 'DESC')->select(['id', 'instruction', 'customer_id', 'assigned_to', 'pending', 'completed_at', 'verified', 'is_priority', 'created_at'])->get()->groupBy('customer_id')->toArray();
        $orders = Order::latest()->select(['id', 'customer_id', 'order_status', 'created_at'])->get()->groupBy('customer_id')->toArray();

        $term = $request->input('term');
        $reply_categories = ReplyCategory::all();
        $api_keys = ApiKey::select('number')->get();

        // $type = $request->type ?? '';

        $orderby = 'desc';
        if ($request->orderby == '') {
            $orderby = 'asc';
        }

        $customers_all = Customer::all();
        $customer_names = Customer::select(['name'])->get()->toArray();

        $category_suggestion = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple', 'multiple' => 'multiple'])
            ->renderAsDropdown();

        $brands = Brand::all()->toArray();

        foreach ($customer_names as $name) {
            $search_suggestions[] = $name[ 'name' ];
        }

        $users_array = Helpers::getUserArray(User::all());

        $last_set_id = MessageQueue::max('group_id');

        $queues_total_count = MessageQueue::where('status', '!=', 1)->where('group_id', $last_set_id)->count();
        $queues_sent_count = MessageQueue::where('sent', 1)->where('status', '!=', 1)->where('group_id', $last_set_id)->count();


        $term = $request->input('term');
        // $customers = DB::table('customers');
        $delivery_status = [
            'Follow up for advance',
            'Proceed without Advance',
            'Advance received',
            'Cancel',
            'Prepaid',
            'Product Shiped form Italy',
            'In Transist from Italy',
            'Product shiped to Client',
            'Delivered'
        ];

        $orderWhereClause = '';
        $searchWhereClause = '';
        $filterWhereClause = '';

        if (!empty($term)) {
            $searchWhereClause = " AND (customers.name LIKE '%$term%' OR customers.phone LIKE '%$term%' OR customers.instahandler LIKE '%$term%')";
            $orderWhereClause = "WHERE orders.order_id LIKE '%$term%'";

            // if ($request->type == 'delivery' || $request->type == 'new' || $request->type == 'Refund to be processed') {
            //   $status_array = [];
            //
            //   if ($request->type == 'delivery') {
            //     array_push($delivery_status, 'VIP', 'HIGH PRIORITY');
            //
            //     $status_array = $delivery_status;
            //   } else if ($request->type == 'Refund to be processed') {
            //     $status_array = [$request->type];
            //   } else if ($request->type == 'new') {
            //     $status_array = [
            //       'Delivered',
            //       'Refund Dispatched',
            //       'Refund Credited'
            //     ];
            //   }
            //
            //   $imploded = implode("','", $status_array);
            //
            //   $orderWhereClause = "WHERE orders.order_id LIKE '%$term%' AND orders.order_status IN ('" . $imploded . "')";
            // } else {
        }

        $orderby = 'DESC';

        if ($request->input('orderby')) {
            $orderby = 'ASC';
        }

        $sortby = 'communication';

        $sortBys = [
            'name' => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'instagram' => 'instahandler',
            'lead_created' => 'lead_created',
            'order_created' => 'order_created',
            'rating' => 'rating',
            'communication' => 'communication'
        ];

        if (isset($sortBys[ $request->input('sortby') ])) {
            $sortby = $sortBys[ $request->input('sortby') ];
        }

        $start_time = $request->input('range_start') ?? '';
        $end_time = $request->input('range_end') ?? '';

        if ($start_time != '' && $end_time != '') {
            $filterWhereClause = " WHERE last_communicated_at BETWEEN '" . $start_time . "' AND '" . $end_time . "'";
        }

        if ($request->type == 'unread' || $request->type == 'unapproved') {
            $join = "RIGHT";
            $type = $request->type == 'unread' ? 0 : ($request->type == 'unapproved' ? 1 : 0);
            $orderByClause = " ORDER BY is_flagged DESC, message_status ASC, `last_communicated_at` $orderby";
            $filterWhereClause = " WHERE message_status = $type";

            if ($start_time != '' && $end_time != '') {
                $filterWhereClause = " WHERE (last_communicated_at BETWEEN '" . $start_time . "' AND '" . $end_time . "') AND message_status = $type";
            }
        } else {
            if ($sortby === 'communication') {
                $join = "LEFT";
                $orderByClause = " ORDER BY is_flagged DESC, last_communicated_at $orderby";
            }
        }

        $new_customers = DB::select('
  									SELECT * FROM
                    (SELECT customers.id, customers.name, customers.phone, customers.is_blocked, customers.is_flagged, customers.is_error_flagged, customers.is_priority, customers.deleted_at,
                    lead_id, lead_status, lead_created, lead_rating,
                    order_id, order_status, order_created, purchase_status,
                    (SELECT mm3.id FROM chat_messages mm3 WHERE mm3.id = message_id) AS message_id,
                    (SELECT mm1.message FROM chat_messages mm1 WHERE mm1.id = message_id) as message,
                    (SELECT mm2.status FROM chat_messages mm2 WHERE mm2.id = message_id) AS message_status,
                    (SELECT mm4.sent FROM chat_messages mm4 WHERE mm4.id = message_id) AS message_type,
                    (SELECT mm2.created_at FROM chat_messages mm2 WHERE mm2.id = message_id) as last_communicated_at

                    FROM (
                      SELECT * FROM customers

                      LEFT JOIN (
                        SELECT MAX(id) as lead_id, leads.customer_id as lcid, leads.rating as lead_rating, MAX(leads.created_at) as lead_created, leads.status as lead_status
                        FROM leads
                        GROUP BY customer_id
                      ) AS leads
                      ON customers.id = leads.lcid

                      LEFT JOIN
                        (SELECT MAX(id) as order_id, orders.customer_id as ocid, MAX(orders.created_at) as order_created, orders.order_status as order_status FROM orders ' . $orderWhereClause . ' GROUP BY customer_id) as orders
                          LEFT JOIN (SELECT order_products.order_id as purchase_order_id, order_products.purchase_status FROM order_products) as order_products
                          ON orders.order_id = order_products.purchase_order_id

                      ' . $join . ' JOIN (SELECT MAX(id) as message_id, customer_id, message, MAX(created_at) as message_created_At FROM chat_messages GROUP BY customer_id ORDER BY created_at DESC) AS chat_messages
                      ON customers.id = chat_messages.customer_id


                    ) AS customers
                    WHERE (deleted_at IS NULL)
                    ' . $searchWhereClause . '
                    ' . $orderByClause . '
                  ) AS customers
                  ' . $filterWhereClause . ';
  							');


        // dd($new_customers);

        $ids_list = [];
        foreach ($new_customers as $customer) {
            $ids_list[] = $customer->id;
        }


        // if ($start_time != '' && $end_time != '') {
        //   $customers = $customers->whereBetween('chat_message_created_at', [$start_time, $end_time])->paginate(Setting::get('pagination'));
        // } else if ($request->type == 'unapproved') {
        //   // dd($customers->get());
        //   $customers = $customers->where('status', 1)->paginate(Setting::get('pagination'));
        // } else {
        //   $customers = $customers->paginate(Setting::get('pagination'));
        // }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = Setting::get('pagination');
        $currentItems = array_slice($new_customers, $perPage * ($currentPage - 1), $perPage);

        $new_customers = new LengthAwarePaginator($currentItems, count($new_customers), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);


        dd([
            'customers' => $new_customers,
            'customers_all' => $customers_all,
            'customer_ids_list' => json_encode($ids_list),
            'users_array' => $users_array,
            'instructions' => $instructions,
            'term' => $term,
            'orderby' => $orderby,
            'type' => $type,
            'queues_total_count' => $queues_total_count,
            'queues_sent_count' => $queues_sent_count,
            'search_suggestions' => $search_suggestions,
            'reply_categories' => $reply_categories,
            'orders' => $orders,
            'api_keys' => $api_keys,
            'category_suggestion' => $category_suggestion,
            'brands' => $brands,
        ]);

        return view('customers.index', [
            'customers' => $new_customers,
            'customers_all' => $customers_all,
            'customer_ids_list' => json_encode($ids_list),
            'users_array' => $users_array,
            'instructions' => $instructions,
            'term' => $term,
            'orderby' => $orderby,
            'type' => $type,
            'queues_total_count' => $queues_total_count,
            'queues_sent_count' => $queues_sent_count,
            'search_suggestions' => $search_suggestions,
            'reply_categories' => $reply_categories,
            'orders' => $orders,
            'api_keys' => $api_keys,
            'category_suggestion' => $category_suggestion,
            'brands' => $brands,
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $messages = ChatMessage::where('message', 'LIKE', "%$keyword%")->where('customer_id', '>', 0)->groupBy('customer_id')->with('customer')->select(DB::raw('MAX(id) as message_id, customer_id, message'))->get()->map(function ($item) {
            return [
                'customer_id' => $item->customer_id,
                'customer_name' => $item->customer->name,
                'message_id' => $item->message_id,
                'message' => $item->message,
            ];
        });

        return response()->json($messages);
    }

    public function loadMoreMessages(Request $request)
    {
        $limit = request()->get("limit", 3);

        $customer = Customer::find($request->customer_id);

        $chat_messages = $customer->whatsapps_all()->where("message", "!=", "")->skip(1)->take($limit)->get();

        $messages = [];

        foreach ($chat_messages as $chat_message) {
            $messages[] = $chat_message->message;
        }

        return response()->json([
            'messages' => $messages
        ]);
    }

    public function sendAdvanceLink(Request $request, $id)
    {
        $customer = Customer::find($id);

        $options = array(
            'trace' => true,
            'connection_timeout' => 120,
            'wsdl_cache' => WSDL_CACHE_NONE,
        );

        $proxy = new \SoapClient(config('magentoapi.url'), $options);
        $sessionId = $proxy->login(config('magentoapi.user'), config('magentoapi.password'));

        $errors = 0;

        $productData = array(
            'price' => $request->price_inr,
            'special_price' => $request->price_special,
        );

        try {
            $result = $proxy->catalogProductUpdate($sessionId, "QUICKADVANCEPAYMENT", $productData);

            $params = [
                'customer_id' => $customer->id,
                'number' => null,
                'message' => "https://www.sololuxury.co.in/advance-payment-product.html",
                'user_id' => Auth::id(),
                'approve' => 0,
                'status' => 1
            ];

            ChatMessage::create($params);

            return response('success');
            // return redirect()->back()->withSuccess('You have successfully sent a link');
        } catch (\Exception $e) {
            $errors++;

            return response($e->getMessage());
            // dd($e);
            // return redirect()->back()->withError('You have failed sending a link');
        }
    }

    public function initiateFollowup(Request $request, $id)
    {
        CommunicationHistory::create([
            'model_id' => $id,
            'model_type' => Customer::class,
            'type' => 'initiate-followup',
            'method' => 'whatsapp'
        ]);

        return redirect()->route('customer.show', $id)->with('success', 'You have successfully initiated follow up sequence!');
    }

    public function stopFollowup(Request $request, $id)
    {
        $histories = CommunicationHistory::where('model_id', $id)->where('model_type', Customer::class)->where('type', 'initiate-followup')->where('is_stopped', 0)->get();

        foreach ($histories as $history) {
            $history->is_stopped = 1;
            $history->save();
        }

        return redirect()->route('customer.show', $id)->with('success', 'You have successfully stopped follow up sequence!');
    }

    public function export()
    {
        $customers = Customer::select(['name', 'phone'])->get()->toArray();

        return Excel::download(new CustomersExport($customers), 'customers.xlsx');
    }

    public function block(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        if ($customer->is_blocked == 0) {
            $customer->is_blocked = 1;
        } else {
            $customer->is_blocked = 0;
        }

        $customer->save();

        return response()->json(['is_blocked' => $customer->is_blocked]);
    }

    public function flag(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        if ($customer->is_flagged == 0) {
            $customer->is_flagged = 1;
        } else {
            $customer->is_flagged = 0;
        }

        $customer->save();

        return response()->json(['is_flagged' => $customer->is_flagged]);
    }

    public function addInWhatsappList(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        if ($customer->in_w_list == 0) {
            $customer->in_w_list = 1;
        } else {
            $customer->in_w_list = 0;
        }

        $customer->save();

        return response()->json(['in_w_list' => $customer->in_w_list]);
    }
    

    public function prioritize(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        if ($customer->is_priority == 0) {
            $customer->is_priority = 1;
        } else {
            $customer->is_priority = 0;
        }

        $customer->save();

        return response()->json(['is_priority' => $customer->is_priority]);
    }

    public function sendInstock(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        $products = Product::where('supplier', 'In-stock')->latest()->get();

        $params = [
            'customer_id' => $customer->id,
            'number' => null,
            'user_id' => Auth::id(),
            'message' => 'In Stock Products',
            'status' => 1
        ];

        $chat_message = ChatMessage::create($params);

        foreach ($products as $product) {
            $chat_message->attachMedia($product->getMedia(config('constants.media_tags'))->first(), config('constants.media_tags'));
        }

        return response('success');
    }

    public function load(Request $request)
    {
        $first_customer = Customer::find($request->first_customer);
        $second_customer = Customer::find($request->second_customer);

        return response()->json([
            'first_customer' => $first_customer,
            'second_customer' => $second_customer
        ]);
    }

    public function merge(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'email' => 'required_without_all:phone,instahandler|nullable|email',
            'phone' => 'required_without_all:email,instahandler|nullable|numeric|regex:/^[91]{2}/|digits:12|unique:customers,phone,' . $request->first_customer_id,
            'instahandler' => 'required_without_all:email,phone|nullable|min:3|max:255',
            'rating' => 'required|numeric',
            'address' => 'sometimes|nullable|min:3|max:255',
            'city' => 'sometimes|nullable|min:3|max:255',
            'country' => 'sometimes|nullable|min:3|max:255',
            'pincode' => 'sometimes|nullable|max:6'
        ]);

        $first_customer = Customer::find($request->first_customer_id);

        $first_customer->name = $request->name;
        $first_customer->email = $request->email;
        $first_customer->phone = $request->phone;
        $first_customer->whatsapp_number = $request->whatsapp_number;
        $first_customer->instahandler = $request->instahandler;
        $first_customer->rating = $request->rating;
        $first_customer->address = $request->address;
        $first_customer->city = $request->city;
        $first_customer->country = $request->country;
        $first_customer->pincode = $request->pincode;

        $first_customer->save();

        $chat_messages = ChatMessage::where('customer_id', $request->second_customer_id)->get();

        foreach ($chat_messages as $chat) {
            $chat->customer_id = $first_customer->id;
            $chat->save();
        }

        $messages = Message::where('customer_id', $request->second_customer_id)->get();

        foreach ($messages as $message) {
            $message->customer_id = $first_customer->id;
            $message->save();
        }

        $leads = ErpLeads::where('customer_id', $request->second_customer_id)->get();

        foreach ($leads as $lead) {
            $lead->customer_id = $first_customer->id;
            $lead->save();
        }

        $orders = Order::where('customer_id', $request->second_customer_id)->get();

        foreach ($orders as $order) {
            $order->customer_id = $first_customer->id;
            $order->save();
        }

        $instructions = Instruction::where('customer_id', $request->second_customer_id)->get();

        foreach ($instructions as $instruction) {
            $instruction->customer_id = $first_customer->id;
            $instruction->save();
        }

        $second_customer = Customer::find($request->second_customer_id);
        $second_customer->delete();

        return redirect()->route('customer.index');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        (new CustomerImport)->queue($request->file('file'));

        return redirect()->back()->with('success', 'Customers are being imported in the background');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $solo_numbers = (new SoloNumbers)->all();

        return view('customers.create', [
            'solo_numbers' => $solo_numbers
        ]);
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
            'name' => 'required|min:3|max:255',
            'email' => 'required_without_all:phone,instahandler|nullable|email',
            'phone' => 'required_without_all:email,instahandler|nullable|numeric|digits:12|unique:customers',
            'instahandler' => 'required_without_all:email,phone|nullable|min:3|max:255',
            'rating' => 'required|numeric',
            'address' => 'sometimes|nullable|min:3|max:255',
            'city' => 'sometimes|nullable|min:3|max:255',
            'country' => 'sometimes|nullable|min:2|max:255',
            'pincode' => 'sometimes|nullable|max:6',
        ]);

        $customer = new Customer;

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
		if(empty($request->whatsapp_number))
		{
			//get default whatsapp number for vendor from whatsapp config
			$task_info = DB::table('whatsapp_configs')
						->select('*')
						->whereRaw("find_in_set(".self::DEFAULT_FOR.",default_for)")
						->first();
		
			$data["whatsapp_number"] = $task_info->number;
        }
	
	
		$customer->whatsapp_number = $request->whatsapp_number;
        $customer->instahandler = $request->instahandler;
        $customer->rating = $request->rating;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->country = $request->country;
        $customer->pincode = $request->pincode;

        $customer->save();

        return redirect()->route('customer.index')->with('success', 'You have successfully added new customer!');
    }

    public function addNote($id, Request $request)
    {
        $customer = Customer::findOrFail($id);
        $notes = $customer->notes;
        if (!is_array($notes)) {
            $notes = [];
        }

        $notes[] = $request->get('note');
        $customer->notes = $notes;
        $customer->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::with(['call_recordings', 'orders', 'leads', 'facebookMessages'])->where('id', $id)->first();
        $customers = Customer::select(['id', 'name', 'email', 'phone', 'instahandler'])->get();
        $emails = [];
        $lead_status = (New status)->all();
        $users_array = Helpers::getUserArray(User::all());
        $brands = Brand::all()->toArray();
        $reply_categories = ReplyCategory::all();
        $instruction_categories = InstructionCategory::all();
        $instruction_replies = Reply::where('model', 'Instruction')->get();
        $order_status_report = OrderStatuses::all();
        $purchase_status = (new PurchaseStatus)->all();
        $solo_numbers = (new SoloNumbers)->all();
        $api_keys = ApiKey::select(['number'])->get();
        $broadcastsNumbers = collect(\DB::select("select number from whatsapp_configs where is_customer_support = 0"))->pluck("number","number")->toArray();
        $suppliers = Supplier::select(['id', 'supplier'])
            ->whereRaw("suppliers.id IN (SELECT product_suppliers.supplier_id FROM product_suppliers)")->get();
        $category_suggestion = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple', 'multiple' => 'multiple'])
            ->renderAsDropdown();

        $facebookMessages = null;
        if (@$customer->facebook_id) {
            $facebookMessages = $customer->facebookMessages()->get();
        }

        return view('customers.show', [
            'customer' => $customer,
            'customers' => $customers,
            'lead_status' => $lead_status,
            'brands' => $brands,
            'users_array' => $users_array,
            'reply_categories' => $reply_categories,
            'instruction_categories' => $instruction_categories,
            'instruction_replies' => $instruction_replies,
            'order_status_report' => $order_status_report,
            'purchase_status' => $purchase_status,
            'solo_numbers' => $solo_numbers,
            'api_keys' => $api_keys,
            'emails' => $emails,
            'category_suggestion' => $category_suggestion,
            'suppliers' => $suppliers,
            'facebookMessages' => $facebookMessages,
            'broadcastsNumbers' => $broadcastsNumbers
        ]);
    }

    public function exportCommunication($id)
    {
        $messages = ChatMessage::where('customer_id', $id)->orderBy('created_at', 'DESC')->get();

        $html = view('customers.chat_export', compact('messages'));

        $pdf = new Dompdf();
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream('orders.pdf');
    }

    public function postShow(Request $request, $id)
    {
        $customer = Customer::with(['call_recordings', 'orders', 'leads', 'facebookMessages'])->where('id', $id)->first();
        $customers = Customer::select(['id', 'name', 'email', 'phone', 'instahandler'])->get();
		
		
		//$emails = Email::select()->where('to', $customer->email)->paginate(15);
		
		
        $searchedMessages = null;
        if ($request->get('sm')) {
            $searchedMessages = ChatMessage::where('customer_id', $id)->where('message', 'LIKE', '%' . $request->get('sm') . '%')->get();
        }


        $customer_ids = json_decode($request->customer_ids ?? "[0]");
        $key = array_search($id, $customer_ids);

        if ($key != 0) {
            $previous_customer_id = $customer_ids[ $key - 1 ];
        } else {
            $previous_customer_id = 0;
        }

        if ($key == (count($customer_ids) - 1)) {
            $next_customer_id = 0;
        } else {
            $next_customer_id = $customer_ids[ $key + 1 ];
        }

        $emails = [];
        $lead_status = (New status)->all();
        $users_array = Helpers::getUserArray(User::all());
        $brands = Brand::all()->toArray();
        $reply_categories = ReplyCategory::orderby('id', 'DESC')->get();
        $instruction_categories = InstructionCategory::all();
        $instruction_replies = Reply::where('model', 'Instruction')->get();
        $order_status_report = OrderStatuses::all();
        $purchase_status = (new PurchaseStatus)->all();
        $solo_numbers = (new SoloNumbers)->all();
        $api_keys = ApiKey::select(['number'])->get();
        $suppliers = Supplier::select(['id', 'supplier'])->get();
        $broadcastsNumbers = collect(\DB::select("select number from whatsapp_configs where is_customer_support = 0"))->pluck("number","number")->toArray();
        $category_suggestion = Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple', 'multiple' => 'multiple'])
            ->renderAsDropdown();

        $facebookMessages = null;
        if ($customer->facebook_id) {
            $facebookMessages = $customer->facebookMessages()->get();
        }
        
        return view('customers.show', [
            'customer_ids' => json_encode($customer_ids),
            'previous_customer_id' => $previous_customer_id,
            'next_customer_id' => $next_customer_id,
            'customer' => $customer,
            'customers' => $customers,
            'lead_status' => $lead_status,
            'brands' => $brands,
            'users_array' => $users_array,
            'reply_categories' => $reply_categories,
            'instruction_categories' => $instruction_categories,
            'instruction_replies' => $instruction_replies,
            'order_status_report' => $order_status_report,
            'purchase_status' => $purchase_status,
            'solo_numbers' => $solo_numbers,
            'api_keys' => $api_keys,
            'emails' => $emails,
            'category_suggestion' => $category_suggestion,
            'suppliers' => $suppliers,
            'facebookMessages' => $facebookMessages,
            'searchedMessages' => $searchedMessages,
            'broadcastsNumbers' => $broadcastsNumbers
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function emailInbox(Request $request)
    {
        /*$imap = new Client([
            'host' => env('IMAP_HOST'),
            'port' => env('IMAP_PORT'),
            'encryption' => env('IMAP_ENCRYPTION'),
            'validate_cert' => env('IMAP_VALIDATE_CERT'),
            'username' => env('IMAP_USERNAME'),
            'password' => env('IMAP_PASSWORD'),
            'protocol' => env('IMAP_PROTOCOL')
        ]);

        $imap->connect();

        $customer = Customer::find($request->customer_id);

        if ($request->type == 'inbox') {
            $inbox_name = 'INBOX';
            $direction = 'from';
        } else {
            $inbox_name = 'INBOX.Sent';
            $direction = 'to';
        }

        $inbox = $imap->getFolder($inbox_name);

        $emails = $inbox->messages()->where($direction, $customer->email);
        $emails = $emails->setFetchFlags(false)
            ->setFetchBody(false)
            ->setFetchAttachment(false)->leaveUnread()->get();


        $emails_array = [];
        $count = 0;

        foreach ($emails as $key => $email) {
            $emails_array[ $key ][ 'uid' ] = $email->getUid();
            $emails_array[ $key ][ 'subject' ] = $email->getSubject();
            $emails_array[ $key ][ 'date' ] = $email->getDate();

            $count++;
        }

        if ($request->type != 'inbox') {
            $db_emails = $customer->emails;

            foreach ($db_emails as $key2 => $email) {
                $emails_array[ $count + $key2 ][ 'id' ] = $email->id;
                $emails_array[ $count + $key2 ][ 'subject' ] = $email->subject;
                $emails_array[ $count + $key2 ][ 'date' ] = $email->created_at;
            }
        }

        $emails_array = array_values(array_sort($emails_array, function ($value) {
            return $value[ 'date' ];
        }));*/
		
		$inbox = "to";
		if ($request->type != 'inbox') {
			$inbox = 'from';
		}
		
		$customer = Customer::find($request->customer_id);
		
		$emails = Email::select()->where($inbox,$customer->email)->get();
		
		
		$count = count($emails);
		foreach ($emails as $key => $email) {
			$emails_array[ $count + $key ][ 'id' ] = $email->id;
			$emails_array[ $count + $key ][ 'subject' ] = $email->subject;
			$emails_array[ $count + $key ][ 'type' ] = $email->type;
			$emails_array[ $count + $key ][ 'message' ] = $email->message;
			$emails_array[ $count + $key ][ 'date' ] = $email->created_at;
		}
		$emails_array = array_values(array_sort($emails_array, function ($value) {
            return $value[ 'date' ];
        }));
        $emails_array = array_reverse($emails_array);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 5;
        $currentItems = array_slice($emails_array, $perPage * ($currentPage - 1), $perPage);
        $emails = new LengthAwarePaginator($currentItems, count($emails_array), $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
		//echo url($request->path());exit;
		//$emails->setPath($request->path());
//print_r($emails);
        $view = view('customers.email', [
            'emails' => $emails,
            'type' => $request->type
        ])->render();

        return response()->json(['emails' => $view]);
    }

    public function emailFetch(Request $request)
    {
        /*$imap = new Client([
            'host' => env('IMAP_HOST'),
            'port' => env('IMAP_PORT'),
            'encryption' => env('IMAP_ENCRYPTION'),
            'validate_cert' => env('IMAP_VALIDATE_CERT'),
            'username' => env('IMAP_USERNAME'),
            'password' => env('IMAP_PASSWORD'),
            'protocol' => env('IMAP_PROTOCOL')
        ]);

        $imap->connect();

        if ($request->type == 'inbox') {
            $inbox = $imap->getFolder('INBOX');
        } else {
            $inbox = $imap->getFolder('INBOX.Sent');
            $inbox->query();
        }

        if ($request->email_type == 'server') {
            $email = $inbox->getMessage($uid = $request->uid, null, null, true, true, true);
            // dd($email);
            if ($email->hasHTMLBody()) {
                $content = $email->getHTMLBody();
            } else {
                $content = $email->getTextBody();
            }
        } else {*/
            //$email = Email::find($request->uid);
            $email = Email::find($request->id);
			$content = $email->message;

            if ($email->template == 'customer-simple') {
                $content = (new CustomerEmail($email->subject, $email->message, $email->from))->render();
            } else {
                if ($email->template == 'refund-processed') {
                    $details = json_decode($email->additional_data, true);

                    $content = (new RefundProcessed($details[ 'order_id' ], $details[ 'product_names' ]))->render();
                } else {
                    if ($email->template == 'order-confirmation') {
                        $order = Order::find($email->additional_data);

                        $content = (new OrderConfirmation($order))->render();
                    } else {
                        if ($email->template == 'advance-receipt') {
                            $order = Order::find($email->additional_data);

                            $content = (new AdvanceReceipt($order))->render();
                        } else {
                            if ($email->template == 'issue-credit') {
                                $customer = Customer::find($email->model_id);

                                $content = (new IssueCredit($customer))->render();
                            } else {
                                $content = 'No Template';
                            }
                        }
                    }
                }
            }
        //}

        return response()->json(['email' => $content]);
    }

    public function emailSend(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:3|max:255',
            'message' => 'required'
        ]);
		
		$customer = Customer::find($request->customer_id);

		//Store ID Email
		$emailAddressDetails = EmailAddress::select()->where(['store_website_id'=>$customer->store_website_id])->first();
		
        if ($request->order_id != '') {
            $order_data = json_encode(['order_id' => $request->order_id]);
        }

        $emailClass = (new CustomerEmail($request->subject, $request->message, $emailAddressDetails->from_address))->build();

        $email             = Email::create([
            'model_id'         => $customer->id,
            'model_type'       => Customer::class,
            'from'             => $emailAddressDetails->from_address,
            'to'               => $customer->email,
            'subject'          => $request->subject,
            'message'          => $emailClass->render(),
            'template'         => 'customer-simple',
            'additional_data'  => isset($order_data) ? $order_data : '',
            'status'           => 'pre-send',
            'store_website_id' => null,
        ]);

        \App\Jobs\SendEmail::dispatch($email);

        return redirect()->route('customer.show', $customer->id)->withSuccess('You have successfully sent an email!');
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        $solo_numbers = (new SoloNumbers)->all();

        return view('customers.edit', [
            'customer' => $customer,
            'solo_numbers' => $solo_numbers
        ]);
    }

    public function updateReminder(Request $request)
    {
        $customer = Customer::find($request->get('customer_id'));
        $customer->frequency            = $request->get('frequency');
        $customer->reminder_message     = $request->get('message');
        $customer->reminder_from        = $request->get('reminder_from',"0000-00-00 00:00");
        $customer->reminder_last_reply  = $request->get('reminder_last_reply',0);
        $customer->save();

        return response()->json([
            'success'
        ]);
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
        $customer = Customer::find($id);

        $this->validate($request, [
            'name' => 'required|min:3|max:255',
            'email' => 'required_without_all:phone,instahandler|nullable|email',
            'phone' => 'required_without_all:email,instahandler|nullable|unique:customers,phone,' . $id,
            'instahandler' => 'required_without_all:email,phone|nullable|min:3|max:255',
            'rating' => 'required|numeric',
            'address' => 'sometimes|nullable|min:3|max:255',
            'city' => 'sometimes|nullable|min:3|max:255',
            'country' => 'sometimes|nullable|min:2|max:255',
            'pincode' => 'sometimes|nullable|max:6',
            'shoe_size' => 'sometimes|nullable',
            'clothing_size' => 'sometimes|nullable',
            'gender' => 'sometimes|nullable|string',
            'credit' => 'sometimes|nullable|numeric',
        ]);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        if ($request->get('whatsapp_number', false)) {
            $customer->whatsapp_number = $request->whatsapp_number;
        }
        $customer->instahandler = $request->instahandler;
        $customer->rating = $request->rating;
        $customer->do_not_disturb = $request->do_not_disturb == 'on' ? 1 : 0;
        $customer->is_blocked = $request->is_blocked == 'on' ? 1 : 0;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->country = $request->country;
        $customer->pincode = $request->pincode;
        $customer->credit = $request->credit;
        $customer->shoe_size = $request->shoe_size;
        $customer->clothing_size = $request->clothing_size;
        $customer->gender = $request->gender;

        $customer->save();

        if ($request->do_not_disturb == 'on') {
             \Log::channel('customerDnd')->debug("(Customer ID " . $customer->id . " line " . $customer->name. " " . $customer->number . ": Added To DND");
             MessageQueue::where('customer_id', $customer->id)->delete();

            // foreach ($message_queues as $message_queue) {
            //   $message_queue->status = 1; // message stopped
            //   $message_queue->save();
            // }
        }

        return redirect()->route('customer.show', $id)->with('success', 'You have successfully updated the customer!');
    }

    public function updateNumber(Request $request, $id)
    {
        $customer = Customer::find($id);

        $customer->whatsapp_number = $request->whatsapp_number;
        $customer->save();

        return response('success');
    }

    public function updateDnd(Request $request, $id)
    {
        $customer = Customer::find($id);

        // $customer->do_not_disturb = $request->do_not_disturb;

        if ($customer->do_not_disturb == 1) {
            $customer->do_not_disturb = 0;
        } else {
            $customer->do_not_disturb = 1;
        }

        $customer->save();

        if ($request->do_not_disturb == 1) {
             \Log::channel('customerDnd')->debug("(Customer ID " . $customer->id . " line " . $customer->name. " " . $customer->number . ": Added To DND");
            MessageQueue::where('customer_id', $customer->id)->delete();

            // foreach ($message_queues as $message_queue) {
            //   $message_queue->status = 1; // message stopped
            //   $message_queue->save();
            // }
        }

        return response()->json([
            'do_not_disturb' => $customer->do_not_disturb
        ]);
    }

    public function updatePhone(Request $request, $id)
    {
        $this->validate($request, [
            'phone' => 'required|numeric|unique:customers,phone'
        ]);

        $customer = Customer::find($id);

        $customer->phone = $request->phone;
        $customer->save();

        return response('success');
    }

    public function issueCredit(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        $emailClass = (new \App\Mails\Manual\SendIssueCredit($customer))->build();

        $email             = Email::create([
            'model_id'         => $customer->id,
            'model_type'       => Customer::class,
            'from'             => $emailClass->fromMailer,
            'to'               => $customer->email,
            'subject'          => $emailClass->subject,
            'message'          => $emailClass->render(),
            'template'         => 'issue-credit',
            'additional_data'  => '',
            'status'           => 'pre-send'
        ]);

        \App\Jobs\SendEmail::dispatch($email);

        $message = "Dear $customer->name, this is to confirm that an amount of Rs. $customer->credit - is credited with us against your previous order. You can use this credit note for reference on your next purchase. Thanks & Regards, Solo Luxury Team";
        $requestData = new Request();
        $requestData->setMethod('POST');
        $requestData->request->add(['customer_id' => $customer->id, 'message' => $message]);

        app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'customer');

        CommunicationHistory::create([
            'model_id' => $customer->id,
            'model_type' => Customer::class,
            'type' => 'issue-credit',
            'method' => 'whatsapp'
        ]);

       
    }

    public function sendSuggestion(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        $params = [
            'customer_id' => $customer->id,
            'number' => $request->number,
            'brands' => '',
            'categories' => '',
            'size' => '',
            'supplier' => ''
        ];

        if ($request->brand[ 0 ] != null) {
            $products = Product::whereIn('brand', $request->brand);

            $params[ 'brands' ] = json_encode($request->brand);
        }

        if ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) {
            $categorySel = $request->category;
            $category = \App\Category::whereIn("parent_id",$categorySel)->get()->pluck("id")->toArray();
            $categorySelected = array_merge($categorySel,$category);
            if ($request->brand[ 0 ] != null) {
                $products = $products->whereIn('category', $categorySelected);
            } else {
                $products = Product::whereIn('category', $categorySelected);
            }

            $params[ 'categories' ] = json_encode($request->category);
        }

        if ($request->size[ 0 ] != null) {
            if ($request->brand[ 0 ] != null || ($request->category[ 0 ] != 1 && $request->category[ 0 ] != null)) {
                $products = $products->where(function ($query) use ($request) {
                    foreach ($request->size as $size) {
                        $query->orWhere('size', 'LIKE', "%$size%");
                    }

                    return $query;
                });
            } else {
                $products = Product::where(function ($query) use ($request) {
                    foreach ($request->size as $size) {
                        $query->orWhere('size', 'LIKE', "%$size%");
                    }

                    return $query;
                });
            }

            $params[ 'size' ] = json_encode($request->size);
        }

        if ($request->supplier[ 0 ] != null) {
            if ($request->brand[ 0 ] != null || ($request->category[ 0 ] != 1 && $request->category[ 0 ] != null) || $request->size[ 0 ] != null) {
                $products = $products->join("product_suppliers as ps","ps.sku","products.sku");
                $products = $products->whereIn("ps.supplier_id",$request->supplier);
                $products = $products->groupBy("products.id");
                /*$products = $products->whereHas('suppliers', function ($query) use ($request) {
                    return $query->where(function ($q) use ($request) {
                        foreach ($request->supplier as $supplier) {
                            $q->orWhere('suppliers.id', $supplier);
                        }
                    });
                });*/
            } else {
                $products = $products->join("product_suppliers as ps","ps.sku","products.sku");
                $products = $products->whereIn("ps.supplier_id",$request->supplier);
                $products = $products->groupBy("products.id");
                /*$products = Product::whereHas('suppliers', function ($query) use ($request) {
                    return $query->where(function ($q) use ($request) {
                        foreach ($request->supplier as $supplier) {
                            $q->orWhere('suppliers.id', $supplier);
                        }
                    });
                });*/
            }

            $params[ 'supplier' ] = json_encode($request->supplier);
        }

        if ($request->brand[ 0 ] == null && ($request->category[ 0 ] == 1 || $request->category[ 0 ] == null) && $request->size[ 0 ] == null && $request->supplier[ 0 ] == null) {
            $products = (new Product)->newQuery();
        }

        $price = explode(',', $request->get('price'));

        $products = $products->whereBetween('price_inr_special', [$price[ 0 ], $price[ 1 ]]);

        $products = $products->where('category', '!=', 1)->select(["products.*"])->latest()->take($request->number)->get();

        if ($customer->suggestion) {
            $suggestion = SuggestedProduct::find($customer->suggestion->id);
            $suggestion->update($params);
        } else {
            $suggestion = SuggestedProduct::create($params);
        }

        if (count($products) > 0) {
            $params = [
                'number' => null,
                'user_id' => Auth::id(),
                'approved' => 0,
                'status' => 1,
                'message' => 'Suggested images',
                'customer_id' => $customer->id
            ];

            $count = 0;

            foreach ($products as $product) {
                if (!$product->suggestions->contains($suggestion->id)) {
                    if ($image = $product->getMedia(config('constants.attach_image_tag'))->first()) {
                        if ($count == 0) {
                            $params["status"] = ChatMessage::CHAT_SUGGESTED_IMAGES; 
                            $chat_message = ChatMessage::create($params);
                            $suggestion->chat_message_id = $chat_message->id;
                            $suggestion->save();
                        }

                        $chat_message->attachMedia($image->getKey(), config('constants.media_tags'));
                        $count++;
                    }

                    $product->suggestions()->attach($suggestion->id);
                }
            }
        }

        if($request->ajax()) {
            return response()->json(["code" => 200, "data" => [], "message" => "Your records has been update successfully"]);
        }

        return redirect()->route('customer.show', $customer->id)->withSuccess('You have successfully created suggested message');
    }

    public function sendScraped(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        $products = new Product;
        if ($request->brand[ 0 ] != null) {
            $products = $products->whereIn('brand', $request->brand);
        }


        if ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) {
            // if ($request->brand[ 0 ] != null) {
            //     $products = $products->whereIn('category', $request->category);
            // } else {
            //     $products = Product::whereIn('category', $request->category);
            // }
            $products = $products->whereIn('category', $request->category);
        }

        // if ($request->brand[ 0 ] == null && ($request->category[ 0 ] == 1 || $request->category[ 0 ] == null)) {
        //     $products = (new Product)->newQuery();
        // }
        $total_images = $request->total_images;
        if(!$total_images) {
            $total_images = 20;
        }
        $products = $products->where('is_scraped', 1)->where('is_without_image', 0)->where('category', '!=', 1)->orderBy(DB::raw('products.created_at'), 'DESC')->take($total_images)->get();
        if (count($products) > 0) {
            $params = [
                'number' => null,
                'user_id' => Auth::id(),
                'approved' => 0,
                'status' => 1,
                'message' => 'Suggested images',
                'customer_id' => $customer->id
            ];

            $count = 0;

            foreach ($products as $product) {
                if ($image = $product->getMedia(config('constants.media_tags'))->first()) {
                    if ($count == 0) {
                        $chat_message = ChatMessage::create($params);
                    }

                    $chat_message->attachMedia($image->getKey(), config('constants.media_tags'));
                    $count++;
                }
            }
        }

        if ($request->ajax()) {
            return response('success');
        }

        return redirect()->route('customer.show', $customer->id)->withSuccess('You have successfully created suggested message');
    }

    public function attachAll(Request $request)
    {
        $data = [];
        $term = $request->input('term');
        $roletype = $request->input('roletype');
        $model_type = $request->input('model_type');

        $data[ 'term' ] = $term;
        $data[ 'roletype' ] = $roletype;

        $doSelection = $request->input('doSelection');

        if (!empty($doSelection)) {

            $data[ 'doSelection' ] = true;
            $data[ 'model_id' ] = $request->input('model_id');
            $data[ 'model_type' ] = $request->input('model_type');

            $data[ 'selected_products' ] = ProductController::getSelectedProducts($data[ 'model_type' ], $data[ 'model_id' ]);
        }

        if ($request->brand[ 0 ] != null) {
            $productQuery = (new Product())->newQuery()
                ->latest()->whereIn('brand', $request->brand);

        }

        if ($request->color[ 0 ] != null) {
            if ($request->brand[ 0 ] != null) {
                $productQuery = $productQuery->whereIn('color', $request->color);
            } else {
                $productQuery = (new Product())->newQuery()
                    ->latest()->whereIn('color', $request->color);
            }
        }

        if ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) {
            $category_children = [];

            foreach ($request->category as $category) {
                $is_parent = Category::isParent($category);

                if ($is_parent) {
                    $childs = Category::find($category)->childs()->get();

                    foreach ($childs as $child) {
                        $is_parent = Category::isParent($child->id);

                        if ($is_parent) {
                            $children = Category::find($child->id)->childs()->get();

                            foreach ($children as $chili) {
                                array_push($category_children, $chili->id);
                            }
                        } else {
                            array_push($category_children, $child->id);
                        }
                    }
                } else {
                    array_push($category_children, $category);
                }
            }

            if ($request->brand[ 0 ] != null || $request->color[ 0 ] != null) {
                $productQuery = $productQuery->whereIn('category', $category_children);
            } else {
                $productQuery = (new Product())->newQuery()
                    ->latest()->whereIn('category', $category_children);
            }
        }

        if ($request->price != null) {
            $exploded = explode(',', $request->price);
            $min = $exploded[ 0 ];
            $max = $exploded[ 1 ];

            if ($min != '0' || $max != '400000') {
                if ($request->brand[ 0 ] != null || $request->color[ 0 ] != null || ($request->category[ 0 ] != null && $request->category[ 0 ] != 1)) {
                    $productQuery = $productQuery->whereBetween('price_inr_special', [$min, $max]);
                } else {
                    $productQuery = (new Product())->newQuery()
                        ->latest()->whereBetween('price_inr_special', [$min, $max]);
                }
            }
        }

        if ($request->supplier[ 0 ] != null) {
            $suppliers_list = implode(',', $request->supplier);

            if ($request->brand[ 0 ] != null || $request->color[ 0 ] != null || ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) || $request->price != "0,400000") {
                $productQuery = $productQuery->with('Suppliers')->whereRaw("products.id in (SELECT product_id FROM product_suppliers WHERE supplier_id IN ($suppliers_list))");
            } else {
                $productQuery = (new Product())->newQuery()->with('Suppliers')
                    ->latest()->whereRaw("products.id IN (SELECT product_id FROM product_suppliers WHERE supplier_id IN ($suppliers_list))");
            }
        }

        if (trim($request->size) != '') {
            if ($request->brand[ 0 ] != null || $request->color[ 0 ] != null || ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) || $request->price != "0,400000" || $request->supplier[ 0 ] != null) {
                $productQuery = $productQuery->whereNotNull('size')->where('size', 'LIKE', "%$request->size%");
            } else {
                $productQuery = (new Product())->newQuery()
                    ->latest()->whereNotNull('size')->where('size', 'LIKE', "%$request->size%");
            }
        }

        if ($request->location[ 0 ] != null) {
            if ($request->brand[ 0 ] != null || $request->color[ 0 ] != null || ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) || $request->price != "0,400000" || $request->supplier[ 0 ] != null || trim($request->size) != '') {
                $productQuery = $productQuery->whereIn('location', $request->location);
            } else {
                $productQuery = (new Product())->newQuery()
                    ->latest()->whereIn('location', $request->location);
            }

            $data[ 'location' ] = $request->location[ 0 ];
        }

        if ($request->type[ 0 ] != null) {
            if ($request->brand[ 0 ] != null || $request->color[ 0 ] != null || ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) || $request->price != "0,400000" || $request->supplier[ 0 ] != null || trim($request->size) != '' || $request->location[ 0 ] != null) {
                if (count($request->type) > 1) {
                    $productQuery = $productQuery->where('is_scraped', 1)->orWhere('status', 2);
                } else {
                    if ($request->type[ 0 ] == 'scraped') {
                        $productQuery = $productQuery->where('is_scraped', 1);
                    } elseif ($request->type[ 0 ] == 'imported') {
                        $productQuery = $productQuery->where('status', 2);
                    } else {
                        $productQuery = $productQuery->where('isUploaded', 1);
                    }
                }
            } else {
                if (count($request->type) > 1) {
                    $productQuery = (new Product())->newQuery()
                        ->latest()->where('is_scraped', 1)->orWhere('status', 2);
                } else {
                    if ($request->type[ 0 ] == 'scraped') {
                        $productQuery = (new Product())->newQuery()
                            ->latest()->where('is_scraped', 1);
                    } elseif ($request->type[ 0 ] == 'imported') {
                        $productQuery = (new Product())->newQuery()
                            ->latest()->where('status', 2);
                    } else {
                        $productQuery = (new Product())->newQuery()
                            ->latest()->where('isUploaded', 1);
                    }
                }
            }

            $data[ 'type' ] = $request->type[ 0 ];
        }

        if ($request->date != '') {
            if ($request->brand[ 0 ] != null || $request->color[ 0 ] != null || ($request->category[ 0 ] != null && $request->category[ 0 ] != 1) || $request->price != "0,400000" || $request->supplier[ 0 ] != null || trim($request->size) != '' || $request->location[ 0 ] != null || $request->type[ 0 ] != null) {
                if ($request->type[ 0 ] != null && $request->type[ 0 ] == 'uploaded') {
                    $productQuery = $productQuery->where('is_uploaded_date', 'LIKE', "%$request->date%");
                } else {
                    $productQuery = $productQuery->where('created_at', 'LIKE', "%$request->date%");
                }
            } else {
                $productQuery = (new Product())->newQuery()
                    ->latest()->where('created_at', 'LIKE', "%$request->date%");
            }
        }

        if ($request->quick_product === 'true') {
            $productQuery = (new Product())->newQuery()
                ->latest()->where('quick_product', 1);
        }

        if (trim($term) != '') {
            $productQuery = (new Product())->newQuery()
                ->latest()
                ->orWhere('sku', 'LIKE', "%$term%")
                ->orWhere('id', 'LIKE', "%$term%")//		                                 ->orWhere( 'category', $term )
            ;

            if ($term == -1) {
                $productQuery = $productQuery->orWhere('isApproved', -1);
            }

            if (Brand::where('name', 'LIKE', "%$term%")->first()) {
                $brand_id = Brand::where('name', 'LIKE', "%$term%")->first()->id;
                $productQuery = $productQuery->orWhere('brand', 'LIKE', "%$brand_id%");
            }

            if ($category = Category::where('title', 'LIKE', "%$term%")->first()) {
                $category_id = $category = Category::where('title', 'LIKE', "%$term%")->first()->id;
                $productQuery = $productQuery->orWhere('category', CategoryController::getCategoryIdByName($term));
            }

            if (!empty($stage->getIDCaseInsensitive($term))) {

                $productQuery = $productQuery->orWhere('stage', $stage->getIDCaseInsensitive($term));
            }

            if (!(\Auth::user()->hasRole(['Admin', 'Supervisors']))) {

                $productQuery = $productQuery->where('stage', '>=', $stage->get($roletype));
            }

            if ($roletype != 'Selection' && $roletype != 'Searcher') {

                $productQuery = $productQuery->whereNull('dnf');
            }
        } else {
            if ($request->brand[ 0 ] == null && $request->color[ 0 ] == null && ($request->category[ 0 ] == null || $request->category[ 0 ] == 1) && $request->price == "0,400000" && $request->supplier[ 0 ] == null && trim($request->size) == '' && $request->date == '' && $request->type == null && $request->location[ 0 ] == null) {
                $productQuery = (new Product())->newQuery()->latest();
            }
        }

        if ($request->ids[ 0 ] != null) {
            $productQuery = (new Product())->newQuery()
                ->latest()->whereIn('id', $request->ids);
        }

        $data[ 'products' ] = $productQuery->select(['id', 'sku', 'size', 'price_inr_special', 'brand', 'supplier', 'isApproved', 'stage', 'status', 'is_scraped', 'created_at'])->get();

        $params = [
            'user_id' => Auth::id(),
            'number' => null,
            'status' => 1,
            'customer_id' => $request->customer_id
        ];

        $chat_message = ChatMessage::create($params);

        $mediaList = [];

        foreach ($data[ 'products' ] as $product) {
            if ($product->hasMedia(config('constants.media_tags'))) {
                $mediaList[] = $product->getMedia(config('constants.media_tags'));
            }
        }

        foreach (array_unique($mediaList) as $list) {
            try {
                $chat_message->attachMedia($list, config('constants.media_tags'));
            } catch (\Exception $e) {

            }
        }

        return redirect()->route('customer.show', $request->customer_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (count($customer->leads) > 0 || count($customer->orders) > 0) {
            return redirect()->route('customer.index')->with('warning', 'You have related leads or orders to this customer');
        }

        $customer->delete();

        return redirect()->route('customer.index')->with('success', 'You have successfully deleted a customer');
    }

    /**
     * using for creating file and save into the on given folder path
     *
     */

    public function testImage()
    {
        $path = request()->get("path");
        $text = request()->get("text");
        $color = request()->get("color", "FFF");
        $fontSize = request()->get("size", 42);

        $img = \IImage::make(public_path($path));
        // use callback to define details
        $img->text($text, 5, 50, function ($font) use ($fontSize, $color) {
            $font->file(public_path('fonts/Arial.ttf'));
            $font->size($fontSize);
            $font->color("#" . $color);
            $font->align('top');
        });

        return $img->response();
        //$img->save(public_path('uploads/withtext.jpg'));
    }

    public function broadcast()
    {
        $customerId = request()->get("customer_id", 0);

        $pendingBroadcast = \App\MessageQueue::where("customer_id", $customerId)
            ->where("sent", 0)->orderBy("group_id", "asc")->groupBy("group_id")->select("group_id as id")->get()->toArray();
        // last two
        $lastBroadcast = \App\MessageQueue::where("customer_id", $customerId)
            ->where("sent", 1)->orderBy("group_id", "desc")->groupBy("group_id")->limit(2)->select("group_id as id")->get()->toArray();

        $allRequest = array_merge($pendingBroadcast, $lastBroadcast);

        if (!empty($allRequest)) {
            usort($allRequest, function ($a, $b) {
                $a = $a[ 'id' ];
                $b = $b[ 'id' ];

                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            });
        }

        return response()->json(["code" => 1, "data" => $allRequest]);

    }

    public function broadcastSendPrice()
    {
        $broadcastId = request()->get("broadcast_id", 0);
        $customerId = request()->get("customer_id", 0);
        $productsToBeRun = explode(",", request()->get("product_to_be_run", ""));

        $products = [];
        if (!empty(array_filter($productsToBeRun))) {
            foreach ($productsToBeRun as $prd) {
                if (is_numeric($prd)) {
                    $products[] = $prd;
                }
            }
        }

        $customer = Customer::where("id", $customerId)->first();

        if ($customer && $customer->do_not_disturb == 0) {
            $this->dispatchBroadSendPrice($customer, array_unique($products));
        }

        return response()->json(["code" => 1, "message" => "Broadcast run successfully"]);
    }

    public function dispatchBroadSendPrice($customer, $product_ids, $dimention = false)
    {
        if (!empty($customer) && is_numeric($customer->phone)) {
            \Log::info("Customer with phone found for customer id : ". $customer->id." and product ids ".json_encode($product_ids));
            if (!empty(array_filter($product_ids))) {

                foreach($product_ids as $pid) {
                    $product = \App\Product::where("id",$pid)->first();

                    $quick_lead = ErpLeads::create([
                        'customer_id' => $customer->id,
                        //'rating' => 1,
                        'lead_status_id' => 3,
                        //'assigned_user' => 6,
                        'product_id' => $pid,
                        'brand_id' => $product ? $product->brand : null,
                        'category_id' => $product ? $product->category : null,
                        'brand_segment' => $product && $product->brands ? $product->brands->brand_segment : null,
                        'color' => $customer->color,
                        'size' => $customer->size,
                        'created_at' => Carbon::now()
                    ]);
                }

                $requestData = new Request();
                $requestData->setMethod('POST');
                if($dimention){
                    $requestData->request->add(['customer_id' => $customer->id, 'dimension' => true, 'lead_id' => $quick_lead->id, 'selected_product' => $product_ids]);
                }else{
                    $requestData->request->add(['customer_id' => $customer->id, 'lead_id' => $quick_lead->id, 'selected_product' => $product_ids]);
                }
                

                $res = app('App\Http\Controllers\LeadsController')->sendPrices($requestData, new GuzzleClient);

                //$message->sent = 1;
                //$message->save();

                return true;
            }

            return false;
        }
    }

    public function broadcastDetails()
    {
        $broadcastId = request()->get("broadcast_id", 0);
        $customerId = request()->get("customer_id", 0);

        $messages = \App\MessageQueue::where("group_id", $broadcastId)->where("customer_id", $customerId)->get();

        $response = [];

        if (!$messages->isEmpty()) {
            foreach ($messages as $message) {
                $response[] = $message->getImagesWithProducts();
            }
        }

        return response()->json(["code" => 1, "data" => $response]);

    }

    /**
     * Change in whatsapp no
     *
     */

    public function changeWhatsappNo()
    {
        $customerId = request()->get("customer_id", 0);
        $whatsappNo = request()->get("number", null);
        $type       = request()->get("type","whatsapp_number");

        if ($customerId > 0) {
            // find the record from customer table
            $customer = \App\Customer::where("id", $customerId)->first();

            if ($customer) {
                // assing nummbers
                $oldNumber = $customer->whatsapp_number;
                if($type == "broadcast_number") {
                    $customer->broadcast_number = $whatsappNo;
                }else{
                    $customer->whatsapp_number = $whatsappNo;
                }

                if ($customer->save()) {
                    if($type == "whatsapp_number") {
                        // update into whatsapp history table
                        $wHistory = new \App\HistoryWhatsappNumber;
                        $wHistory->date_time = date("Y-m-d H:i:s");
                        $wHistory->object = "App\Customer";
                        $wHistory->object_id = $customerId;
                        $wHistory->old_number = $oldNumber;
                        $wHistory->new_number = $whatsappNo;
                        $wHistory->save();
                    }
                }
            }
        }

        return response()->json(["code" => 1, "message" => "Number updated successfully"]);
    }

    public function sendContactDetails()
    {
        $userID = request()->get("user_id",0);
        $customerID = request()->get("customer_id",0);

        $user = \App\User::where("id", $userID)->first();
        $customer = \App\Customer::where("id", $customerID)->first();

        // if found customer and  user
        if($user && $customer) {

            $data = [
                "Customer details:",
                "$customer->name",
                "$customer->phone",
                "$customer->email",
                "$customer->address",
                "$customer->city",
                "$customer->country",
                "$customer->pincode"
            ];

            $messageData = implode("\n",$data);

            $params[ 'erp_user' ] = $user->id;
            $params[ 'approved' ] = 1;
            $params[ 'message' ]  = $messageData;
            $params[ 'status' ]   = 2;

            app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone,$user->whatsapp_number,$messageData);

            $chat_message = \App\ChatMessage::create($params);

        }

        return response()->json(["code" => 1 , "message" => "done"]);


    }

    public function addReplyCategory(Request $request)
    {

        $this->validate($request, [
            'name'  => 'required|string'
        ]);

        $category = new ReplyCategory;
        $category->name = $request->name;
        $category->save();

        return response()->json(["code" => 1 , "data" => $category]);

    }

    public function destroyReplyCategory(Request $request)
    {

        $this->validate($request, [
            'id'  => 'required'
        ]);

        Reply::where('category_id', $request->get('id'))->delete();
        ReplyCategory::where('id', $request->get('id'))->delete();

        return response()->json(["code" => 1 , "message" => "Deleted successfully"]);

    }

    public function downloadContactDetails()
    {
        $userID = request()->get("user_id",0);
        $customerID = request()->get("customer_id",0);

        $user = \App\User::where("id", $userID)->first();
        $customer = \App\Customer::where("id", $customerID)->first();

        // if found customer and  user
        if($user && $customer) {
            // load the view for pdf and after that load that into dompdf instance, and then stream (download) the pdf
            $html = view( 'customers.customer_pdf', compact('customer') );

            $pdf = new Dompdf();
            $pdf->loadHtml($html);
            $pdf->render();
            $pdf->stream('orders.pdf');
        }
    }

    public function downloadContactDetailsPdf($id)
    {
        //$userID = request()->get("user_id",0);
        $customerID = request()->get("id",0);

        //$user = \App\User::where("id", $userID)->first();
        $customer = \App\Customer::where("id", $id)->first();

        // if found customer and  user
        if($customer) {
            // load the view for pdf and after that load that into dompdf instance, and then stream (download) the pdf
            $html = view( 'customers.customer_pdf', compact('customer') );

            $pdf = new Dompdf();
            $pdf->loadHtml($html);
            $pdf->render();
            $pdf->stream($id.'-label.pdf');
        }
    }

    public function languageTranslate(Request $request) 
    {
        if($request->language == '')
            $language = 'en';
        else
            $language = $request->language;

        $customer = Customer::find($request->id);
        // $customer->language = $request->language;
        $customer->language = $language;
        $customer->save();
        return response()->json(['success' => 'Customer language updated'], 200);
    }

    public function getLanguage(Request $request)
    {
        $customerDetails = Customer::find($request->id);
        return  response()->json(["data" => $customerDetails]);
    }

    public function updateField(Request $request)
    {
        $field = $request->get("field");
        $value = $request->get("value");

        $customerId = $request->get("customer_id");

        if(!empty($customerId)) {
            $customer = \App\Customer::find($customerId);
            if(!empty($customer)) {
                $customer->{$field} = $value;
                $customer->save();
            }

            return response()->json(["code" => 200 , "data" => [], "message" => $field . " updated successfully"]);
        }
        
        return response()->json(["code" => 200 , "data" => [] , "message" => "Sorry , no customer found"]);
    }

    public function  createKyc(Request $request )
    {
        $customer_id = $request->get("customer_id");
        $media_id    = $request->get("media_id");

        if(empty($customer_id)) {
            return response()->json(["code" => 500 ,"message" => "Customer id is required"]);
        }

        if(empty($media_id)) {
            return response()->json(["code" => 500 ,"message" => "Media id is required"]);
        }

        $media = PlunkMediable::find($media_id);
        if(!empty($media)) {

            $kycDoc = new \App\CustomerKycDocument;
            $kycDoc->customer_id = $customer_id;
            $kycDoc->url = $media->getUrl();
            $kycDoc->path = $media->getAbsolutePath();
            $kycDoc->type = 1;
            $kycDoc->save();

            return response()->json(["code" => 200 , "data" => [], "message" => "Kyc document added successfully"]);
        }

        return response()->json(["code" => 500 ,"message" => "Ooops, something went wrong"]);
    }
    public function quickcustomer(Request $request)
    {
        $results = $this->getCustomersIndex($request);
        $nextActionArr = DB::table('customer_next_actions')->get();
        $type = @$request->type;
        return view('customers.quickcustomer', ['customers'=>$results[0],'nextActionArr'=>$nextActionArr,'type'=>$type]);
    }


    //START - Purpose : Add Customer Data - DEVTASK-19932
    public function add_customer_data(Request $request)
    {
        if($request->email)
        {
            $email = $request->email;
            $website = $request->website;

            $website_data = StoreWebsite::where('website',$website)->first();

            if($website_data)
                $website_id = $website_data->id;
            else
                $website_id = '';

            if($email != '' && $website_id != ''){
                $find_customer = Customer::where('email',$email)->where('store_website_id',$website_id)->first();

                if($find_customer)
                {
                    foreach($request->post() as $key => $value)
                    {

                        if($value['entity_id'] != "")
                            $check_record = CustomerAddressData::where('customer_id',$find_customer->id)->where('entity_id',$value['entity_id'])->first();


                        if($check_record)

                        {

                            if(isset($value['is_deleted']) && $value['is_deleted'] == 1)
                            {
                                CustomerAddressData::where('customer_id',$find_customer->id)
                                ->where('entity_id',$value['entity_id'])
                                ->delete();
                            }else{
                                CustomerAddressData::where('customer_id',$find_customer->id)
                                ->where('entity_id',$value['entity_id'])
                                ->update(
                                    [
                                        'parent_id' => ($value['parent_id'] ?? ''),
                                        'address_type' => ($value['address_type'] ?? ''),
                                        'region' => ($value['region'] ?? ''),
                                        'region_id' => ($value['region_id'] ?? ''),
                                        'postcode' => ($value['postcode'] ?? ''),
                                        'firstname' => ($value['firstname'] ?? ''),
                                        'middlename' => ($value['middlename'] ?? ''),
                                        'company' => ($value['company'] ?? ''),
                                        'country_id' => ($value['country_id'] ?? ''),
                                        'telephone' => ($value['telephone'] ?? ''),
                                        'prefix' => ($value['prefix'] ?? ''),
                                        'street' => ($value['street'] ?? ''),
                                        'updated_at' => \Carbon\Carbon::now(),
                                    ]
                                );
                            }
                        }else{

                            $params[] = [
                                'customer_id' => $find_customer->id,
                                'entity_id' => ($value['entity_id'] ?? ''),
                                'parent_id' => ($value['parent_id'] ?? ''),
                                'address_type' => ($value['address_type'] ?? ''),
                                'region' => ($value['region'] ?? ''),
                                'region_id' => ($value['region_id'] ?? ''),
                                'postcode' => ($value['postcode'] ?? ''),
                                'firstname' => ($value['firstname'] ?? ''),
                                'middlename' => ($value['middlename'] ?? ''),
                                'company' => ($value['company'] ?? ''),
                                'country_id' => ($value['country_id'] ?? ''),
                                'telephone' => ($value['telephone'] ?? ''),
                                'prefix' => ($value['prefix'] ?? ''),
                                'street' => ($value['street'] ?? ''),
                                'created_at' => \Carbon\Carbon::now(),
                                'updated_at' => \Carbon\Carbon::now(),

                            ];

                        }
                    }

                    if(!empty($params))
                         CustomerAddressData::insert($params);

                    return response()->json(["code" => 200]);
                }else{
                    return response()->json(["code" => 404 ,"message" => "Not Exist!"]);
                }
            }else{
                return response()->json(["code" => 404 ,"message" => "Website Not Found!"]);
            }
        }
        // if(!empty($request->customer_data))
        // {
        //     $email = $request->customer_data['email'];
        //     $website_id =  $request->customer_data['website_id'];
            
        //     if($email != '')
        //     {

        //         $find_customer = Customer::where('email',$email)->where('store_website_id',$website_id)->first();

        //         if($find_customer)
        //         {
        //             foreach($request->customer_data['address'] as $key => $value)
        //             {
        //                 if($value['entity_id'] != '')
        //                     $check_record = CustomerAddressData::where('customer_id',$find_customer->id)->where('entity_id',$value['entity_id'])->first();
                        
        //                 if($check_record)
        //                 {
        //                     if(isset($value['is_deleted']) && $value['is_deleted'] == 1)
        //                     {
        //                         CustomerAddressData::where('customer_id',$find_customer->id)
        //                         ->where('entity_id',$value['entity_id'])
        //                         ->delete();
        //                     }else{
        //                         CustomerAddressData::where('customer_id',$find_customer->id)
        //                         ->where('entity_id',$value['entity_id'])
        //                         ->update(
        //                             [
        //                                 'address_1' => $value['address_1'],
        //                                 'address_2' => $value['address_2'],
        //                                 'address_3' => $value['address_3'],
        //                                 'country' => $value['country'],
        //                                 'city' => $value['city'],
        //                                 'state' => $value['state'],
        //                                 'postcode' => $value['postcode'],
        //                                 'updated_at' => \Carbon\Carbon::now(),
        //                             ]
        //                         );
        //                     }
        //                 }else{    
        //                     $params[] = [
        //                         'customer_id' => $find_customer->id,
        //                         'entity_id' => $value['entity_id'],
        //                         'address_1' => $value['address_1'],
        //                         'address_2' => $value['address_2'],
        //                         'address_3' => $value['address_3'],
        //                         'country' => $value['country'],
        //                         'city' => $value['city'],
        //                         'state' => $value['state'],
        //                         'postcode' => $value['postcode'],
        //                         'created_at' => \Carbon\Carbon::now(),
        //                         'updated_at' => \Carbon\Carbon::now(),

        //                     ];
        //                 }
        //             }

        //             if(!empty($params))
        //                 CustomerAddressData::insert($params);

        //             return response()->json(["code" => 2000 ,"message" => "Data Added successfully"]); 

        //         }else{
        //             return response()->json(["code" => 404 ,"message" => "Not Exist!"]);
        //         }
        //     }
        // }
    }
    //END - DEVTASK-19932
}
