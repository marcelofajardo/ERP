<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Old;
use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use View;
use App\Issue;
use App\OldCategory;
use App\User;
use App\ReplyCategory;
use App\Helpers;
use App\OldRemark;
use Auth;
use App\OldPayment;
use Webklex\IMAP\Client;
use App\Email;
use Carbon\Carbon;
use Mail;
use Response;
use App\Mails\Manual\PurchaseEmail;
use Illuminate\Pagination\LengthAwarePaginator;


class OldController extends Controller
{
    /**
     * Defining scope of variable
     *
     * @access protected
     *
     * @var    array $old
     */
    protected $old;

    /**
     * Create a new controller instance.
     *
     * @param mixed $old get old model
     *
     * @return void
     */
    public function __construct(Old $old)
    {
        $this->old = $old;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if($request->type == 2){

            if($request->term != null || $request->status != null){
                if($request->status && $request->term){
                $olds  =   Old::query()
                        ->where('status', '=', $request->status)
                        ->orWhere('name', 'LIKE', "%{$request->term}%")
                        ->orWhere('description', 'LIKE', "%{$request->term}%")
                        ->orWhere('email', 'LIKE', "%{$request->term}%")
                        ->orWhereHas('category', function ($q) use ($request) {
                        $q->where('category', 'like', "%{$request->term}%");
                        })
                        ->paginate(10);  
                }

                if($request->status){
                    $olds  = Old::query()->where('status', '=', $request->status)->paginate(10);
                }

                if($request->term){
                
                $olds  = Old::query()
                            ->where('name', 'LIKE', "%{$request->term}%")
                            ->orWhere('description', 'LIKE', "%{$request->term}%")
                            ->orWhere('email', 'LIKE', "%{$request->term}%")
                            ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('category', 'like', "%{$request->term}%");
                            })
                            ->paginate(10);         
                }
                $title = 'Old Info';
                $type = '2';    
            }else{
                $olds = Old::paginate(10);
                $title = 'Old Info';
                $type = '2';
            }

       }elseif ($request->type == 0 && $request->type != null) {

          if($request->term != null || $request->status != null){
                if($request->status && $request->term){
                $olds  =   Old::query()
                        ->where('status', '=', $request->status)
                        ->orWhere('name', 'LIKE', "%{$request->term}%")
                        ->orWhere('description', 'LIKE', "%{$request->term}%")
                        ->orWhere('email', 'LIKE', "%{$request->term}%")
                        ->orWhereHas('category', function ($q) use ($request) {
                        $q->where('category', 'like', "%{$request->term}%");
                        })
                        ->where('is_payable',0)
                        ->paginate(10);  
                }

                if($request->status){

                    $olds  = Old::query()->where('status', '=', $request->status)->where('is_payable',0)->paginate(10);
                }

                if($request->term){
                
                $olds  = Old::query()
                            ->where('name', 'LIKE', "%{$request->term}%")
                            ->orWhere('description', 'LIKE', "%{$request->term}%")
                            ->orWhere('email', 'LIKE', "%{$request->term}%")
                            ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('category', 'like', "%{$request->term}%");
                            })
                            ->where('is_payable',0)
                            ->paginate(10);         
                }
                   
            }else{
                $olds = Old::where('is_payable',0)->paginate(10);
            }
            $title = 'Old Incoming Info';
            $type = 0;
       }elseif ($request->type == 1 && $request->type != null) {
          if($request->term != null || $request->status != null){
                if($request->status && $request->term){
                $olds  =   Old::query()
                        ->where('status', '=', $request->status)
                        ->orWhere('name', 'LIKE', "%{$request->term}%")
                        ->orWhere('description', 'LIKE', "%{$request->term}%")
                        ->orWhere('email', 'LIKE', "%{$request->term}%")
                        ->orWhereHas('category', function ($q) use ($request) {
                        $q->where('category', 'like', "%{$request->term}%");
                        })
                        ->where('is_payable',1)
                        ->paginate(10);  
                }

                if($request->status){
                    $olds  = Old::query()->where('status', '=', $request->status)->where('is_payable',1)->paginate(10);
                }

                if($request->term){
                
                $olds  = Old::query()
                            ->where('name', 'LIKE', "%{$request->term}%")
                            ->orWhere('description', 'LIKE', "%{$request->term}%")
                            ->orWhere('email', 'LIKE', "%{$request->term}%")
                            ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('category', 'like', "%{$request->term}%");
                            })
                            ->where('is_payable',1)
                            ->paginate(10);         
                }
                   
            }else{
                $olds = Old::where('is_payable',1)->paginate(10);
            }
            $title = 'Old Outgoing Info';
            $type = 1;
       }else{
            $olds = Old::paginate(10);
            $title = 'Old Info';
            $type = '2';
       }

      $old_categories = OldCategory::all();
      $users = User::all();
      $status = $this->old->getStatus();
       
      return view('old.index', [
        'olds' => $olds,
        'old_categories' => $old_categories,
        'users' => $users,
        'title' => $title,
        'type' => $type,
        'status' => $status,
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $this->validate($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'pending_payment' => 'required',
            'status' => 'required',
        ]);

        $new = new Old();
        $new->name = $request->name;
        $new->description = $request->description;
        if($request->amount == null){
          $new->amount = 0;
        }else{
          $new->amount = $request->amount;
        }
        
        $new->email = $request->email;
        $new->number = $request->number;
        $new->address = $request->address;
        $new->phone = $request->phone;
        $new->gst = $request->gst;
        $new->amount = $request->amount;
        $new->account_name = $request->account_name;
        $new->account_number = $request->account_number;
        $new->account_iban = $request->account_iban;
        $new->account_swift = $request->account_swift;
        $new->category_id = $request->category_id;
        $new->pending_payment = $request->pending_payment;
        $new->currency = $request->currency;
        $new->is_payable = $request->is_payable;
        $new->status = $request->status;
        $new->save();

        Session::flash('success', 'Record Created');
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $old = Old::find($id);
      $old_categories = OldCategory::all();
      $old_show = true;
      $emails = [];
      $reply_categories = ReplyCategory::all();
      $users_array = Helpers::getUserArray(User::all());

      return view('old.show', [
        'old'  => $old,
        'old_categories'  => $old_categories,
        'old_show'  => $old_show,
        'reply_categories'  => $reply_categories,
        'users_array'  => $users_array,
        'emails' => $emails,
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($serial_no)
    {        
        $old = $this->old::where('serial_no', $serial_no)->first();
        $status = $this->old->getStatus();
        return view('old.edit', compact('status', 'old'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $serial_no)
    {
      
        $new = Old::findorfail($serial_no);
        $new->name = $request->name;
        $new->description = $request->description;
        if($request->amount == null){
          $new->amount = 0;
        }else{
          $new->amount = $request->amount;
        }
        
        $new->email = $request->email;
        $new->number = $request->number;
        $new->address = $request->address;
        $new->phone = $request->phone;
        $new->gst = $request->gst;
        $new->account_name = $request->account_name;
        $new->account_number = $request->account_number;
        $new->account_iban = $request->account_iban;
        $new->account_swift = $request->account_swift;
        $new->category_id = $request->category_id;
        $new->pending_payment = $request->pending_payment;
        $new->currency = $request->currency;
        $new->update();

        Session::flash('success', 'Record Updated');
        return redirect('old');
    }

    /**
     * Remove the specified resource from storage.
     * Destroy Old Issues
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $old = Old::find($id);
        $old->delete();
        // Delete Relation    
        OldRemark::where('old_id',$id)->delete();
        OldPayment::where('old_id',$id)->delete();

      return redirect()->route('old.index')->withSuccess('You have successfully deleted a old vendor');
    }

    //Twilio Block
    public function block(Request $request){

        $old = Old::find($request->old_id);
        
        if ($old->is_blocked == 0) {
            $old->is_blocked = 1;
        } else {
            $old->is_blocked = 0;
        }

        $old->save();

        return response()->json(['is_blocked' => $old->is_blocked]);
    }
    
    //Create Category
    public function createCategory(Request $request)
    {
        $this->validate($request, [
        'category' => 'required|string'
      ]);

      $data = $request->except('_token');

      OldCategory::create($data);

      return redirect()->route('old.index')->withSuccess('You have successfully created a old category!');
    }

    //Get Remark
     public function getTaskRemark(Request $request)
    {
        $id = $request->input('id');

        $remark = OldRemark::where('old_id', $id)->get();

        return response()->json($remark, 200);
    }

    // Add Remark
    public function addRemark(Request $request)
    {
        $remark = OldRemark::create([
                'old_id' => $request->id,
                'remark' => $request->remark,
                'user_name' => $request->user_name ? $request->user_name : Auth::user()->name
            ]);
        

        return response()->json(['remark' => $remark], 200);

    }

    //Send Email 
    public function sendEmail(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:3|max:255',
            'message' => 'required',
            'email.*' => 'required|email',
            'cc.*' => 'nullable|email',
            'bcc.*' => 'nullable|email'
        ]);

        $old = Old::find($request->old_id);

        if ($old->email != '') {
            $file_paths = [];

            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $filename = $file->getClientOriginalName();

                    $file->storeAs("documents", $filename, 'files');

                    $file_paths[] = "documents/$filename";
                }
            }

            $cc = $bcc = [];
            $emails = $request->email;

            if ($request->has('cc')) {
                $cc = array_values(array_filter($request->cc));
            }
            if ($request->has('bcc')) {
                $bcc = array_values(array_filter($request->bcc));
            }

            if (is_array($emails) && !empty($emails)) {
                $to = array_shift($emails);
                $cc = array_merge($emails, $cc);

                $mail = Mail::to($to);

                if ($cc) {
                    $mail->cc($cc);
                }
                if ($bcc) {
                    $mail->bcc($bcc);
                }

                $mail->send(new PurchaseEmail($request->subject, $request->message, $file_paths));
            } else {
                return redirect()->back()->withErrors('Please select an email');
            }

            $params = [
                'model_id' => $old->serial_no,
                'model_type' => Old::class,
                'from' => 'buying@amourint.com',
                'to' => $request->email[0],
                'seen' => 1,
                'subject' => $request->subject,
                'message' => $request->message,
                'template' => 'customer-simple',
                'additional_data' => json_encode(['attachment' => $file_paths]),
                'cc' => $cc ?: null,
                'bcc' => $bcc ?: null
            ];

            Email::create($params);

            return redirect()->route('old.show', $old->serial_no)->withSuccess('You have successfully sent an email!');

        }
    }
    // Send Bulk Email 
    public function sendEmailBulk(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|min:3|max:255',
            'message' => 'required',
            'cc.*' => 'nullable|email',
            'bcc.*' => 'nullable|email'
        ]);

        if ($request->olds) {
            $olds = Old::whereIn('serial_no', $request->olds)->get();
        } else {
            if ($request->not_received != 'on' && $request->received != 'on') {
                return redirect()->route('vendors.index')->withErrors(['Please select vendors']);
            }
        }

        if ($request->not_received == 'on') {
            $olds = Old::doesnthave('emails')->where(function ($query) {
                $query->whereNotNull('email');
            })->get();
        }

        if ($request->received == 'on') {
            $olds = Old::whereDoesntHave('emails', function ($query) {
                $query->where('type', 'incoming');
            })->where(function ($query) {
                $query->orWhereNotNull('email');
            })->where('has_error', 0)->get();
        }

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

        foreach ($olds as $old) {
            $mail = Mail::to($old->email);

            if ($cc) {
                $mail->cc($cc);
            }
            if ($bcc) {
                $mail->bcc($bcc);
            }

            $mail->send(new PurchaseEmail($request->subject, $request->message, $file_paths));

            $params = [
                'model_id'        => $old->serial_no,
                'model_type'      => Old::class,
                'from'            => 'buying@amourint.com',
                'seen'            => 1,
                'to'              => $old->email,
                'subject'         => $request->subject,
                'message'         => $request->message,
                'template'      => 'customer-simple',
                'additional_data'   => json_encode(['attachment' => $file_paths]),
                'cc'              => $cc ?: null,
                'bcc'             => $bcc ?: null,
            ];

            Email::create($params);
        }

        return redirect()->route('old.index')->withSuccess('You have successfully sent emails in bulk!');
    }

    //Recieve Email
    public function emailInbox(Request $request){
        
        $imap = new Client([
            'host'          => 'mail.myinteriormart.com',
            'port'          => 143,
            'encryption'    => 'tls',
            'validate_cert' => true,
            'username'      => 'suggestion@myinteriormart.com',
            'password'      => 'FIVEthousand',
            'protocol'      => 'imap'
        ]);

        $imap->connect();

        $old = Old::find($request->old_id);

        if ($request->type == 'inbox') {
            $inbox_name = 'INBOX';
            $direction = 'from';
            $type = 'incoming';
        } else {
            $inbox_name = 'INBOX.Sent';
            $direction = 'to';
            $type = 'outgoing';
        }

        $inbox = $imap->getFolder($inbox_name);

        $latest_email = Email::where('type', $type)->where('model_id', $old->serial_no)->where('model_type', 'App\Old')->latest()->first();

        $latest_email_date = $latest_email
            ? Carbon::parse($latest_email->created_at)
            : Carbon::parse('1990-01-01');

        $oldAgentsCount = $old->agents()->count();
        //dd($old->email);
        if ($oldAgentsCount == 0) {
            $emails = $inbox->messages()->where($direction, $old->email)->since(Carbon::parse($latest_email_date)->format('Y-m-d H:i:s'));
            $emails = $emails->leaveUnread()->get();
            $this->createEmailsForEmailInbox($old, $type, $latest_email_date, $emails);
            
        }
        else if($oldAgentsCount == 1) {
            $emails = $inbox->messages()->where($direction, $old->agents[0]->email)->since(Carbon::parse($latest_email_date)->format('Y-m-d H:i:s'));
            $emails = $emails->leaveUnread()->get();
            $this->createEmailsForEmailInbox($old, $type, $latest_email_date, $emails);
        }
        else {
            foreach ($old->agents as $key => $agent) {
                if ($key == 0) {
                    $emails = $inbox->messages()->where($direction, $agent->email)->where([
                        ['SINCE', $latest_email_date->format('d M y H:i')]
                    ]);
                    $emails = $emails->leaveUnread()->get();
                    $this->createEmailsForEmailInbox($old, $type, $latest_email_date, $emails);
                } else {
                    $additional = $inbox->messages()->where($direction, $agent->email)->since(Carbon::parse($latest_email_date)->format('Y-m-d H:i:s'));
                    $additional = $additional->leaveUnread()->get();
                    $this->createEmailsForEmailInbox($old, $type, $latest_email_date, $additional);
                    // $emails = $emails->merge($additional);
                }
            }
        }

        $db_emails = $old->emails()->with('model')->where('type', $type)->get();
            
        $emails_array = []; $count = 0;
        foreach ($db_emails as $key2 => $email) {
            $dateCreated = $email->created_at->format('D, d M Y');
            $timeCreated = $email->created_at->format('H:i');
            $userName = null;
            if ($email->model instanceof Supplier) {
                $userName = $email->model->supplier;
            } elseif ($email->model instanceof Customer) {
                $userName = $email->model->name;
            }

            $emails_array[$count + $key2]['id'] = $email->id;
            $emails_array[$count + $key2]['subject'] = $email->subject;
            $emails_array[$count + $key2]['seen'] = $email->seen;
            $emails_array[$count + $key2]['type'] = $email->type;
            $emails_array[$count + $key2]['date'] = $email->created_at;
            $emails_array[$count + $key2]['from'] = $email->from;
            $emails_array[$count + $key2]['to'] = $email->to;
            $emails_array[$count + $key2]['message'] = $email->message;
            $emails_array[$count + $key2]['cc'] = $email->cc;
            $emails_array[$count + $key2]['bcc'] = $email->bcc;
            $emails_array[$count + $key2]['replyInfo'] = "On {$dateCreated} at {$timeCreated}, $userName <{$email->from}> wrote:";
            $emails_array[$count + $key2]['dateCreated'] = $dateCreated;
            $emails_array[$count + $key2]['timeCreated'] = $timeCreated;
        }

        $emails_array = array_values(array_sort($emails_array, function ($value) {
            return $value['date'];
        }));

        $emails_array = array_reverse($emails_array);

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($emails_array, $perPage * ($currentPage - 1), $perPage);
        $emails = new LengthAwarePaginator($currentItems, count($emails_array), $perPage, $currentPage);
            
        $view = view('old.partials.email', ['emails' => $emails, 'type' => $request->type])->render();

        return response()->json(['emails' => $view]);
    }

    //Save Recieved Email 
     private function createEmailsForEmailInbox($old, $type, $latest_email_date, $emails)
    {
        foreach ($emails as $email) {
            $content = $email->hasHTMLBody() ? $email->getHTMLBody() : $email->getTextBody();

            if ($email->getDate()->format('Y-m-d H:i:s') > $latest_email_date->format('Y-m-d H:i:s')) {
                $attachments_array = [];
                $attachments = $email->getAttachments();

                $attachments->each(function ($attachment) use (&$attachments_array) {
                    file_put_contents(storage_path('app/files/email-attachments/' . $attachment->name), $attachment->content);
                    $path = "email-attachments/" . $attachment->name;
                    $attachments_array[] = $path;
                });

                $params = [
                    'model_id'        => $old->serial_no,
                    'model_type'      => Old::class,
                    'type'            => $type,
                    'seen'            => $email->getFlags()['seen'],
                    'from'            => $email->getFrom()[0]->mail,
                    'to'              => array_key_exists(0, $email->getTo()) ? $email->getTo()[0]->mail : $email->getReplyTo()[0]->mail,
                    'subject'         => $email->getSubject(),
                    'message'         => $content,
                    'template'        => 'customer-simple',
                    'additional_data' => json_encode(['attachment' => $attachments_array]),
                    'created_at'      => $email->getDate()
                ];

                Email::create($params);
            }
        }
    }

    // Payment Index
     public function paymentindex($id)
    {
        $old = Old::findorfail($id);
        $payments = $old->payments()->orderBy('payment_date')->paginate(50);

        return view('old.payments', [
            'payments' => $payments,
            'old' => $old,
            'currencies' => Helpers::currencies(),
        ]);
    }


    // Payment Store
    public function paymentStore(Old $old, Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'currency' => 'required|numeric',
            'payment_date' => 'required|date',
            'paid_date' => 'sometimes|nullable|date',
            'paid_amount' => 'sometimes|nullable|numeric',
        ]);
        try {
            $status = 0;
            if ($request->get('paid_date') && $request->get('paid_amount')) {
                $status = 1;
            }

            //Check if amount is equal to total paid amount 
            //if yes make it paid
            //If no update the payment in the Old module
            if($request->payable_amount < $old->paid_amount){
                    return redirect()->back()->withErrors('Payable amount is greater then Paid amount');
                }

            $vendor_payment = $old->payments()->create([
                'service_provided' => $request->get('service_provided'),
                'payment_date' => $request->get('payment_date'),
                'payable_amount' => $old->amount,
                'paid_date' => $request->get('paid_date'),
                'paid_amount' => $request->get('paid_amount'),
                'description' => $request->get('description'),
                'module' => $request->get('module'),
                'work_hour' => $request->get('work_hour'),
                'currency' => $request->get('currency'),
                'status' => $status,
            ]);

            if($vendor_payment != null){
                if($old->pending_payment == $request->paid_amount){
                  $old->status = 'paid';
                  $old->pending_payment = ($old->pending_payment - $request->paid_amount);
                  $old->update();  
                  return redirect()->back()->withSuccess('Payment completed!');
                }
                
                if($request->paid_amount != null){
                  $old->pending_payment =($old->pending_payment - $request->paid_amount);
                  $old->update();
                  return redirect()->back()->withSuccess('You have successfully added a old vendor payment!');
                }
            }
           
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t store old vendor payment');
        }
        return redirect()->back()->withSuccess('You have successfully added a old vendor payment!');
    }

    //Destroy Payment
    public function paymentDestroy(Old $old, OldPayment $old_payment)
    {
      $payment = $old->payments()->where('id', $old_payment->id)->firstOrFail();
        try {
            $payment->delete();
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t delete vendor payment');
        }
        return redirect()->back()->withSuccess('You have successfully deleted vendor payment!');
    }

    public function updateOld(Request $request)
    {
        $old = Old::findorfail($request->id);
        $old->status = $request->value;
        $old->save();
        return Response::json(array(
          'success' => true,
          'data'   => $old
        )); 
    }

   



   
}
