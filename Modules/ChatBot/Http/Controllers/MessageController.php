<?php

namespace Modules\ChatBot\Http\Controllers;

use App\ChatbotCategory;
use App\ChatMessage;
use App\Suggestion;
use App\SuggestedProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $search = request("search");
        $status = request("status");
        $unreplied_msg = request("unreplied_msg");//Purpose : get unreplied message value - DEVATSK=4350


        $pendingApprovalMsg = ChatMessage::with('taskUser', 'chatBotReplychat', 'chatBotReplychatlatest')
            ->leftjoin("customers as c", "c.id", "chat_messages.customer_id")
            ->leftJoin("vendors as v", "v.id", "chat_messages.vendor_id")
            ->leftJoin("suppliers as s", "s.id", "chat_messages.supplier_id")
            ->leftJoin("store_websites as sw", "sw.id", "c.store_website_id")
            ->Join("chatbot_replies as cr", "cr.replied_chat_id", "chat_messages.id")
            ->leftJoin("chat_messages as cm1", "cm1.id", "cr.chat_id")
            ->groupBy(['chat_messages.customer_id','chat_messages.vendor_id','chat_messages.user_id', 'chat_messages.task_id','chat_messages.developer_task_id']);//Purpose : Add task_id - DEVTASK-4203
            
        if (!empty($search)) {
            $pendingApprovalMsg = $pendingApprovalMsg->where(function ($q) use ($search) {
                $q->where("cr.question", "like", "%" . $search . "%")->orWhere("cr.answer", "Like", "%" . $search . "%");
            });
        }

        //START - Purpose : get unreplied messages - DEVATSK=4350 
        if (!empty($unreplied_msg)) {
            $pendingApprovalMsg = $pendingApprovalMsg->where('cm1.message',null);
        }
        //END - DEVATSK=4350 

        if (isset($status) && $status !== null) {
            $pendingApprovalMsg = $pendingApprovalMsg->where(function ($q) use ($status) {
                $q->where("chat_messages.approved", $status);
            });
        }

        if(request("unread_message") == "true") {
            $pendingApprovalMsg = $pendingApprovalMsg->where(function ($q) use ($status) {
                $q->where("cr.is_read", 0);
            });
        }

        $pendingApprovalMsg = $pendingApprovalMsg->whereRaw("chat_messages.id in (select max(chat_messages.id) as latest_message from chat_messages JOIN chatbot_replies as cr on cr.replied_chat_id = `chat_messages`.`id` where (customer_id > 0 or vendor_id > 0 or task_id > 0 or developer_task_id > 0 or user_id > 0 or supplier_id > 0)  GROUP BY customer_id,user_id,vendor_id,supplier_id,task_id,developer_task_id)");

        $pendingApprovalMsg = $pendingApprovalMsg->where(function ($q) {
            $q->where("chat_messages.message", "!=", "");
        })->select(['cr.id as chat_bot_id','cr.is_read as chat_read_id', "chat_messages.*", "cm1.id as chat_id", "cr.question",
            "cm1.message as answer",
            "c.name as customer_name", "v.name as vendors_name","s.supplier as supplier_name", "cr.reply_from", "cm1.approved", "sw.title as website_title","c.do_not_disturb as customer_do_not_disturb"])
            ->orderBy('cr.id', 'DESC')
            ->paginate(20);

            // dd($pendingApprovalMsg);
            
        $allCategory = ChatbotCategory::all();
        $allCategoryList = [];
        if (!$allCategory->isEmpty()) {
            foreach ($allCategory as $all) {
                $allCategoryList[] = ["id" => $all->id, "text" => $all->name];
            }
        }
        $page = $pendingApprovalMsg->currentPage();
        $reply_categories = \App\ReplyCategory::with('approval_leads')->orderby('name')->get();


        if ($request->ajax()) {
            $tml = (string)view("chatbot::message.partial.list", compact('pendingApprovalMsg', 'page', 'allCategoryList','reply_categories'));
            return response()->json(["code" => 200, "tpl" => $tml, "page" => $page]);
        }

        
//dd($pendingApprovalMsg);
        return view("chatbot::message.index", compact('pendingApprovalMsg', 'page', 'allCategoryList','reply_categories'));
    }

    public function approve()
    {
        $id = request("id");


        if ($id > 0) {

            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add(['messageId' => $id]);

            $chatMEssage = \app\ChatMessage::find($id);

            $type = "customer";
            if($chatMEssage->task_id > 0) {
                $type = "task";
            }elseif($chatMEssage->developer_tasK_id > 0) {
                $type = "issue";
            }elseif($chatMEssage->vendor_id > 0) {
                $type = "vendor";
            }elseif($chatMEssage->user_id > 0) {
                $type = "user";
            }elseif($chatMEssage->supplier_id > 0) {
                $type = "supplier";
            }


            app('App\Http\Controllers\WhatsAppController')->approveMessage($type, $myRequest);
        }

        return response()->json(["code" => 200, "message" => "Messsage Send Successfully"]);

    }

    /**
     * [removeImages description]
     * @return [type] [description]
     *
     */
    public function removeImages(Request $request)
    {
        $deleteImages = $request->get("delete_images", []);

        if (!empty($deleteImages)) {
            foreach ($deleteImages as $image) {
                list($mediableId, $mediaId) = explode("_", $image);
                if (!empty($mediaId) && !empty($mediableId)) {
                    \Db::statement("delete from mediables where mediable_id = ? and media_id = ? limit 1", [$mediableId, $mediaId]);
                }
            }
        }

        return response()->json(["code" => 200, "data" => [], "message" => "Image has been removed now"]);

    }

    public function attachImages(Request $request)
    {
        $id = $request->get("chat_id", 0);

        $data = [];
        $ids = [];
        $images = [];

        if ($id > 0) {
            // find the chat message
            $chatMessages = ChatMessage::where("id", $id)->first();

            if ($chatMessages) {
                $chatsuggestion = $chatMessages->suggestion;
                if ($chatsuggestion) {
                    $data = SuggestedProduct::attachMoreProducts($chatsuggestion);
                    $code = 500;
                    $message = "Sorry no images found!";
                    if (count($data) > 0) {
                        $code = 200;
                        $message = "More images attached Successfully";
                    }
                    return response()->json(["code" => $code, "data" => $data, "message" => $message]);
                }
            }

            return response()->json(["code" => 200, "data" => [], "message" => "Sorry , There is not avaialble images"]);
        }

        return response()->json(["code" => 500, "data" => [], "message" => "It looks like there is not validate id"]);

    }

    public function forwardToCustomer(Request $request)
    {
        $customer = $request->get("customer");
        $images = $request->get("images");

        if ($customer > 0 && !empty($images)) {

            $params = request()->all();
            $params["user_id"] = \Auth::id();
            $params["is_queue"] = 0;
            $params["status"] = \App\ChatMessage::CHAT_MESSAGE_APPROVED;
            $params["customer_ids"] = is_array($customer) ? $customer : [$customer];
            $groupId = \DB::table('chat_messages')->max('group_id');
            $params["group_id"] = ($groupId > 0) ? $groupId + 1 : 1;
            $params["images"] = $images;

            \App\Jobs\SendMessageToCustomer::dispatch($params);

        }

        return response()->json(["code" => 200, "data" => [], "message" => "Message forward to customer(s)"]);

    }

    public function resendToBot(Request $request)
    {
        $chatId = $request->get("chat_id");

        if (!empty($chatId)) {
            $chatMessage = \App\ChatMessage::find($chatId);
            if ($chatMessage) {
                $customer = $chatMessage->customer;
                if ($customer) {
                    $params = $chatMessage->getAttributes();
                    \App\Helpers\MessageHelper::whatsAppSend($customer, $chatMessage->message, null, $chatMessage);
                    \App\Helpers\MessageHelper::sendwatson($customer, $chatMessage->message, null, $chatMessage, $params, false, 'customer');
                    return response()->json(["code" => 200, "data" => [], "message" => "Message sent Successfully"]);
                }
            }
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Message not exist in record"]);
    }

    public function updateReadStatus(Request $request)
    {
        $chatId = $request->get("chat_id");
        $value  = $request->get("value");

        $reply = \App\ChatbotReply::find($chatId);

        if($reply) {
            
            $reply->is_read = $value;
            $reply->save();
            
            $status = ($value == 1) ? "read" : "unread";

            return response()->json(["code" => 200, "data" => [], "messages" => "Marked as ".$status]);
        }

        return response()->json(["code" => 500, "data" => [], "messages" => "Message not exist in record"]);
    }

    public function stopReminder(Request $request)
    {
        $id = $request->get("id");
        $type = $request->get("type");

        if($type == "developer_task") {
           $task = \App\DeveloperTask::find($id);
        }else{
           $task = \App\Task::find($id);
        }

        if($task) {
            $task->frequency = 0;
            $task->save();
            return response()->json(["code" => 200, "data" => [], "messages" => "Reminder turned off"]);
        }

        return response()->json(["code" => 500, "data" => [], "messages" => "No task found"]);
    }

}
