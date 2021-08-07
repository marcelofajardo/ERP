<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Setting;
use Illuminate\Http\Request;

class QuickCustomerController extends Controller
{
    public function index(Request $request)
    {
        $title         = "Quick Customer";
        $nextActionArr = \DB::table('customer_next_actions')->get();
        $nextActionList = \DB::table('customer_next_actions')->pluck('name','id')->toArray();

        $reply_categories = \App\ReplyCategory::orderby('id', 'DESC')->get();
        $groups           = \App\QuickSellGroup::select('id', 'name', 'group')->orderby('id', 'DESC')->get();
        $category_suggestion = \App\Category::attr(['name' => 'category[]', 'class' => 'form-control select-multiple', 'multiple' => 'multiple'])->renderAsDropdown();
        $brands = \App\Brand::all()->toArray();

        $request->merge(["do_not_disturb" => "0"]); 

        return view("quick-customer.index", compact('title', 'category_suggestion', 'brands', 'nextActionArr','reply_categories', 'groups','nextActionList'));
    }

    public function records(Request $request)
    {

        $type = $request->get("type", "last_received");
        $chatMessagesWhere = "WHERE status not in (7,8,9,10)";

        $customer = \App\Customer::query();


        if ($type == "unread") {

            $customer = $customer->join("chat_messages_quick_datas as cmqs", function ($q) {
                $q->on("cmqs.model_id", "customers.id")->where("cmqs.model", Customer::class);
            });
            $customer = $customer->join("chat_messages as cm", "cm.id", "cmqs.last_unread_message_id");
        } else if ($type == "last_communicated") {
            $customer = $customer->join("chat_messages_quick_datas as cmqs", function ($q) {
                $q->on("cmqs.model_id", "customers.id")->where("cmqs.model", Customer::class);
            });
            $customer = $customer->join("chat_messages as cm", "cm.id", "cmqs.last_communicated_message_id");
        } else if ($type == "last_received") {
            $chatMessagesWhere .= " and message != '' and message is not null and number = c.phone";
            $customer = $customer->leftJoin(\DB::raw('(SELECT MAX(chat_messages.id) as  max_id, customer_id ,message as matched_message  FROM `chat_messages` join customers as c on c.id = chat_messages.customer_id '.$chatMessagesWhere.' GROUP BY customer_id ) m_max'), 'm_max.customer_id', '=', 'customers.id');
            $customer = $customer->leftJoin('chat_messages as cm', 'cm.id', '=', 'm_max.max_id');
            $customer = $customer->whereNotNull('cm.id');
            $customer = $customer->orderBy('cm.created_at','desc');
        } else if($type == null) {
            $customer = $customer->leftJoin(\DB::raw('(SELECT MAX(chat_messages.id) as  max_id, customer_id ,message as matched_message  FROM `chat_messages` join customers as c on c.id = chat_messages.customer_id '.$chatMessagesWhere.' GROUP BY customer_id ) m_max'), 'm_max.customer_id', '=', 'customers.id');
            $customer = $customer->leftJoin('chat_messages as cm', 'cm.id', '=', 'm_max.max_id');
            //$customer = $customer->whereNotNull('cm.id');
        }
        $customer = $customer->orderBy('cm.created_at','desc');
        if($request->customer_id != null) {
            $customer = $customer->where("customers.id",$request->customer_id);
        }

        if($request->customer_name != null) {
            $customer = $customer->where("customers.name","like","%".$request->customer_name."%");
        }

        if($request->next_action != null) {
            $customer = $customer->where("customers.customer_next_action_id",$request->next_action);
        }

        if ($request->get('do_not_disturb') || 
            (($request->get('do_not_disturb') === "0" || $request->get('do_not_disturb') === 0) && $request->get('do_not_disturb') != "")) {
            $customer = $customer->where('customers.do_not_disturb', $request->get('do_not_disturb'));
        }

        //Setting::get('pagination')

        $customer = $customer->select(["customers.*", "cm.id as message_id", "cm.status as message_status", "cm.message"])->paginate(10);

        // $customer = $customer->select("customers.*")->paginate(10);
        $items = [];
        foreach($customer->items() as $item) {
            $item->message = utf8_encode($item->message);
            $item->name = utf8_encode($item->name);
            $item->address = utf8_encode($item->address);
            $item->city = utf8_encode($item->city);
            $item->country = utf8_encode($item->country);
            $item->reminder_message = utf8_encode($item->reminder_message);
            $item->message = utf8_encode($item->message);
            $item["short_message"] = strlen($item->message) > 20 ? substr($item->message, 0, 20) : $item->message;
            $item["short_name"] = strlen($item->name) > 10 ? substr($item->name, 0, 10) : $item->name;
            $items[] = $item;
        }
        
        
        $title         = "Quick Customer";
        $nextActionArr = \DB::table('customer_next_actions')->get();
        $reply_categories = \App\ReplyCategory::orderby('name')->get();
        if(isset($_GET['page'])){
            unset($_GET['page']);
        }
        return response()->json([
            "code"       => 200,
            "data"       => view("quick-customer.quicklist-html", compact('items','title', 'nextActionArr','reply_categories'))->render(),
            "total"      => $customer->total(),
            "pagination" => (string) $customer->appends($_GET)->links(),
            "page"       => $customer->currentPage()
        ]);

    }

    public function addInWhatsappList(Request $request)
    {
        $ids = $request->customer_ids;
        if(!empty($ids)) {
            \DB::table('customers')->whereIn('id', $ids)->update(array('in_w_list' => 1));
        }

        return response()->json(["code" => 200 , "data" => [], "message" => "Whatsapp list added successfully"]);
    }
}