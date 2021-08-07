<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StoreWebsite;
use Illuminate\Support\Facades\Validator;
use App\Tickets;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @SWG\Post(
     *   path="/ticket/create",
     *   tags={"Ticket"},
     *   summary="create ticket",
     *   operationId="create-ticket",
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:80',
            'last_name' => 'required|max:80',
            'email' => 'required|email',
            //'order_no' => ['required', 'exists:orders,order_id'],
            'type_of_inquiry' => 'required',
            //'country' => 'required',
            'subject' => 'required|max:80',
            'message' => 'required',
            //'source_of_ticket' => 'in:live_chat,customer',
        ]);

        if ($validator->fails()) {
            $message = $this->generate_erp_response("ticket.failed.validation", 0, $default = "Please check the errors in validation!", request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message, 'errors' => $validator->errors()], 400);
        }
        if(isset($request->notify_on) && $request->notify_on!='email' && $request->notify_on!='phone'){
            $message = $this->generate_erp_response("ticket.failed.email_or_phone", 0, $default = "notify_on field must be either email or phone!", request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message, 'errors' => $validator->errors()], 400);            
        }

        $data = $request->all();
        $data['ticket_id'] = "T" . date("YmdHis");
        $data['status_id'] = 1;
        $success = Tickets::create($data);
        if (!is_null($success)) {
            $message = $this->generate_erp_response("ticket.success", 0, $default = 'Ticket #' . $data['ticket_id'] . ' created successfully', request('lang_code'));
            return response()->json(['status' => 'success', 'data' => ["id" => $data['ticket_id']], 'message' => $message], 200);
        }
        $message = $this->generate_erp_response("ticket.failed", 0, $default = 'Unable to create ticket', request('lang_code'));
        return response()->json(['status' => 'error', 'message' => $message], 500);
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

    /**
     * @SWG\Post(
     *   path="/ticket/send",
     *   tags={"Ticket"},
     *   summary="Send ticket to customers",
     *   operationId="send-ticket-to-customer",
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
    public function sendTicketsToCustomers(request $request)
    {
        $Validator = Validator::make($request->all(), [
            'website' => 'required'
        ]);
        if ($Validator->fails()) {
            $message = $this->generate_erp_response("ticket.send.failed.validation", 0, $default = 'Please check validation errors !', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message, 'errors' => $Validator->errors()], 400);
        }
        if(empty($request->email) && empty($request->ticket_id)){
            $message = $this->generate_erp_response("ticket.send.failed.ticket_or_email", 0, $default = 'Please input either email or ticket_id !', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message, ], 400);            
        }
        $tickets = Tickets::select('tickets.*','ts.name as status')->where('source_of_ticket',$request->website);
        if($request->email!=null){
            $tickets->where('email',$request->email);
        }
        if($request->ticket_id!=null){
            $tickets->where('ticket_id',$request->ticket_id);
        }
        $per_page='';
        if(!empty($request->per_page)){
            $per_page=$request->per_page;
        }
        $tickets = $tickets->join('ticket_statuses as ts','ts.id', 'tickets.status_id')->paginate($per_page);
        if(empty($tickets)){
            $message = $this->generate_erp_response("ticket.send.failed", 0, $default = 'Tickets not found for customer !', request('lang_code'));
            return response()->json(['status' => 'failed', 'message' => $message, ], 404);    
        }
        return response()->json(['status' => 'success', 'tickets' => $tickets ], 200);
    }
}
