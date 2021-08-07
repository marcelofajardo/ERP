<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Email;
use App\EmailRemark;
use App\CronJob;
use App\CronJobReport;
use Webklex\IMAP\Client;
use App\Mails\Manual\PurchaseEmail;
use Mail;
use Auth;
use DB;
use App\Mails\Manual\ReplyToEmail;
use App\Mails\Manual\ForwardEmail;
use Illuminate\Support\Facades\Validator;
use App\Wetransfer;

use App\EmailAddress;
use App\EmailRunHistories;
use EmailReplyParser\Parser\EmailParser;

use Carbon\Carbon;
use seo2websites\ErpExcelImporter\ErpExcelImporter;
use App\DigitalMarketingPlatform;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // Set default type as incoming
        $type = "incoming";
		$seen = '1';
		
        $term = $request->term ?? '';
        $sender = $request->sender ?? '';
        $receiver = $request->receiver ?? '';
        $status = $request->status ?? '';
        $category = $request->category ?? '';
        $date = $request->date ?? '';
        $type = $request->type ?? $type;
        $seen = $request->seen ?? $seen;
        $query = (new Email())->newQuery();
        $trash_query = false;

        // If type is bin, check for status only
        if($type == "bin"){
            $trash_query = true;
            $query = $query->where('status',"bin");
        }elseif($type == "draft"){
            $query = $query->where('is_draft',1);
        }elseif($type == "pre-send"){
            $query = $query->where('status',"pre-send");
        }else{
            $query = $query->where('type',$type);
        }
        
        if($date) {
            $query = $query->whereDate('created_at',$date);
        }
        if($term) {
            $query = $query->where(function ($query) use ($term) {
                $query->where('from','like','%'.$term.'%')
                ->orWhere('to','like','%'.$term.'%')
                ->orWhere('subject','like','%'.$term.'%')
                ->orWhere('message','like','%'.$term.'%');
            });
        }
		
		if(!$term)
		{
			if($sender)
			{
				$query = $query->where(function ($query) use ($sender) {
					$query->orWhere('from','like','%'.$sender.'%');
				});
			}
			if($receiver)
			{
				$query = $query->where(function ($query) use ($receiver) {
					$query->orWhere('to','like','%'.$receiver.'%');
				});
			}
			if($status)
			{
				$query = $query->where(function ($query) use ($status) {
					$query->orWhere('status',$status);
				});
			}
			if($category)
			{
				$query = $query->where(function ($query) use ($category) {
					$query->orWhere('email_category_id',$category);
				});
			}
			
		}

        if(isset($seen)){
            if($seen != 'both'){
                $query = $query->where('seen',$seen);
            }
        }

        // If it isn't trash query remove email with status trashed
        if(!$trash_query){
            $query = $query->where(function($query){ return $query->where('status','<>',"bin")->orWhereNull('status');});
        }
		
		$query = $query->orderByDesc('created_at');
		
		
		
		//Get All Category
        $email_status = DB::table('email_status')->get();
		
		//Get All Status
        $email_categories = DB::table('email_category')->get();
        
        //Get Cron Email Histroy
		$reports = CronJobReport::where('cron_job_reports.signature','fetch:all_emails')
        ->join('cron_jobs', 'cron_job_reports.signature', 'cron_jobs.signature')
        ->whereDate('cron_job_reports.created_at','>=',Carbon::now()->subDays(10))
        ->select(['cron_job_reports.*','cron_jobs.last_error'])->paginate(15);

        $emails = $query->paginate(30)->appends(request()->except(['page']));
        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('emails.search', compact('emails','date','term','type','email_categories','email_status'))->with('i', ($request->input('page', 1) - 1) * 5)->render(),
                'links' => (string)$emails->links(),
                'count' => $emails->total(),
                'emails' => $emails
            ], 200);
        }

        // suggested search for email forwarding
        $search_suggestions = $this->getAllEmails();

        // dd(array_values($search_suggestions));

        // if($request->AJAX()) {
        //     return view('emails.search',compact('emails'));
        // }

        // dont load any data, data will be loaded by tabs based on ajax
        // return view('emails.index',compact('emails','date','term','type'))->with('i', ($request->input('page', 1) - 1) * 5);
        $digita_platfirms = DigitalMarketingPlatform::all();
        $sender_drpdwn = Email::select('from')->distinct()->get()->toArray();
        $receiver_drpdwn = Email::select('to')->distinct()->get()->toArray();
        return view('emails.index',['emails'=>$emails,'type'=>'email' ,'search_suggestions'=>$search_suggestions,'email_categories'=>$email_categories,'email_status'=>$email_status, 'reports' => $reports,'sender_drpdwn' => $sender_drpdwn,'digita_platfirms' => $digita_platfirms, 'receiver_drpdwn' => $receiver_drpdwn])->with('i', ($request->input('page', 1) - 1) * 5);

    }


    public function platformUpdate(Request $request){
        if($request->id){
            if(Email::where('id',$request->id)->update(['digital_platfirm' => $request->platform])){
                return redirect()->back()->with('success','Updated successfully.');
            }
            return redirect()->back()->with('error','Records not found!');
        }
        return redirect()->back()->with('error','Error Occured! Please try again later.');
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
        $email = Email::find($id);
        $status = "bin";
        $message = "Email has been trashed";

        // If status is already trashed, move to inbox
        if($email->status == 'bin'){
            $status = "";
            $message = "Email has been sent to inbox";
        }

        $email->status= $status;
        $email->update();

        return response()->json(['message' => $message]);
    }

    public function resendMail($id, Request $request) {
        $email = Email::find($id);
        $attachment = [];
        $imap = new Client([
            'host' => env('IMAP_HOST_PURCHASE'),
            'port' => env('IMAP_PORT_PURCHASE'),
            'encryption' => env('IMAP_ENCRYPTION_PURCHASE'),
            'validate_cert' => env('IMAP_VALIDATE_CERT_PURCHASE'),
            'username' => env('IMAP_USERNAME_PURCHASE'),
            'password' => env('IMAP_PASSWORD_PURCHASE'),
            'protocol' => env('IMAP_PROTOCOL_PURCHASE')
        ]);

        $imap->connect();

        $array = is_array(json_decode($email->additional_data, true)) ? json_decode($email->additional_data, true) : [];

        if (array_key_exists('attachment', $array)) {
            $temp = json_decode($email->additional_data, true)[ 'attachment' ];
        }
        if (!is_array($temp)) {
            $attachment[] = $temp;
        } else {
            $attachment = $temp;
        }
        $customConfig = [
            'from' =>  $email->from,
        ];
        Mail::to($email->to)->send(new PurchaseEmail($email->subject, $email->message, $attachment));
        if($type == 'approve') {
            $email->update(['approve_mail' => 0]);
        }
        return response()->json(['message' => 'Mail resent successfully']);
   }

   /**
    * Provide view for email reply modal
    *
    * @param [type] $id
    * @return view
    */
    public function replyMail($id) {
        $email = Email::find($id);
        return view('emails.reply-modal',compact('email'));
    }

    /**
     * Provide view for email forward modal
     *
     * @param [type] $id
     * @return void
     */
    public function forwardMail($id) {
        $email = Email::find($id);
        return view('emails.forward-modal',compact('email'));
        }

    /**
     * Handle the email reply
     *
     * @param Request $request
     * @return json
     */
    public function submitReply(Request $request)
    {
       $validator = Validator::make($request->all(), [
           'message' => 'required'
       ]);

       if ($validator->fails()) {
           return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
       }

       $email = Email::find($request->reply_email_id);
       Mail::send(new ReplyToEmail($email, $request->message));

       return response()->json(['success' => true, 'message' => 'Email has been successfully sent.']);
   }

   /**
    * Handle the email forward
    *
    * @param Request $request
    * @return json
    */
   public function submitForward(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'email' => 'required'
       ]);

       if ($validator->fails()) {
           return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
       }

       $email = Email::find($request->forward_email_id);

       $emailClass = (new ForwardEmail($email, $email->message))->build();

        $email             = \App\Email::create([
            'model_id'         => $email->id,
            'model_type'       => \App\Email::class,
            'from'             => @$emailClass->from[0]['address'],
            'to'               => $request->email,
            'subject'          => $emailClass->subject,
            'message'          => $emailClass->render(),
            'template'         => 'forward-email',
            'additional_data'  => "",
            'status'           => 'pre-send',
            'store_website_id' => null,
            'is_draft'         => 1
        ]);

        \App\Jobs\SendEmail::dispatch($email);
       
       //Mail::to($request->email)->send(new ForwardEmail($email, $email->message));

       return response()->json(['success' => true, 'message' => 'Email has been successfully sent.']);
   }


   public function getRemark(Request $request)
    {
        $email_id = $request->input('email_id');

        $remark = EmailRemark::where('email_id', $email_id)->get();

        return response()->json($remark, 200);
    }

    public function addRemark(Request $request)
    {
        $remark = $request->input('remark');
        $email_id = $request->input('id');
        $created_at = date('Y-m-d H:i:s');
        $update_at = date('Y-m-d H:i:s');

        if (!empty($remark)) {
            $remark_entry = EmailRemark::create([
                'email_id' => $email_id,
                'remarks' => $remark,
                'user_name' => Auth::user()->name
            ]);
        }

        return response()->json(['remark' => $remark], 200);
    }

    public function markAsRead($id){
        $email = Email::find($id);
        $email->seen = 1;
        $email->update();
        return response()->json(['success' => true, 'message' => 'Email has been read.']);
    }

    public function getAllEmails(){
            $available_models = ["supplier" =>\App\Supplier::class,"vendor"=>\App\Vendor::class,
                             "customer"=>\App\Customer::class,"users"=>\App\User::class];
            $email_list = [];
            foreach ($available_models as $key => $value) {
                $email_list = array_merge($email_list, $value::whereNotNull('email')->pluck('email')->unique()->all());
            }
        // dd($email_list);
        return array_values(array_unique($email_list));
    }
	
	
	public function category(Request $request){
		$values = array('category_name' => $request->input('category_name'));
		DB::table('email_category')->insert($values);
		
		session()->flash('success', 'Category added successfully');
		return redirect('email');

	}
	
	public function status(Request $request){
		$email_id = $request->input('status');
		$values = array('email_status' => $request->input('email_status'));
		DB::table('email_status')->insert($values);
		
		session()->flash('success', 'Status added successfully');
		return redirect('email');
		
	}
	
	
	public function updateEmail(Request $request){
		$email_id = $request->input('email_id');
		$category = $request->input('category');
		$status = $request->input('status');
		
		$email = Email::find($email_id);
        $email->status = $status;
        $email->email_category_id = $category;
		
        $email->update();
		
		session()->flash('success', 'Data updated successfully');
		return redirect('email');
	}

    public function getFileStatus(Request $request)
    {
        $id = $request->id;
        $email = Email::find($id);
        
        if ( isset( $email->email_excel_importer ) ) {
            $status = 'No any update';

            if ($email->email_excel_importer === 3) {
                $status = 'File move on wetransfer';
            }else if ($email->email_excel_importer === 2) {
                $status = 'Executed but we transfer file not exist';
            }else if ($email->email_excel_importer === 1) {
                $status = 'Transfer exist';
            }

            return response()->json([
                'status'      => true,
                'mail_status' => $status,
                'message'     => 'Data found'
            ], 200);
        }
        return response()->json([
            'status'  => false,
            'message' => 'Data not found'
        ], 200);

    }

    public function excelImporter(Request $request)
    {
        $id = $request->id;

        $email = Email::find($id);

        $body = $email->message;

        //check for wetransfer link

        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $body, $match);

        if(isset($match[0])){
            $matches = $match[0];
            foreach ($matches as $matchLink) {
                if(strpos($matchLink, 'wetransfer.com') !== false || strpos($matchLink, 'we.tl') !== false){

                    if(strpos($matchLink, 'google.com') === false){
                        //check if wetransfer already exist
                        $checkIfExist = Wetransfer::where('url',$matchLink)->where('supplier',$request->supplier)->first();
                        if(!$checkIfExist){
                            $wetransfer = new Wetransfer();
                            $wetransfer->type = 'excel';
                            $wetransfer->url = $matchLink;
                            $wetransfer->supplier = $request->supplier;
                            $wetransfer->save();

                            Email::where( 'id', $id )->update(['email_excel_importer' => 3 ]);

                            try {
                               self::downloadFromURL($matchLink,$request->supplier); 
                            } catch (Exception $e) {
                                return response()->json(['message' => 'Something went wrong!'], 422);
                            }
                            //downloading wetransfer and generating data

                        }
                        
                    }

                }
            }
        }

        //getting from attachments

        $attachments = $email->additional_data;
        if($attachments){
            $attachJson = json_decode($attachments);
            $attachs = $attachJson->attachment;
            
            //getting all attachments
            //check if extension is .xls or xlsx
            foreach ($attachs as $attach) {
                $attach = str_replace('email-attachments/', '', $attach);
                $extension = last(explode('.', $attach));
                if ($extension == 'xlsx' || $extension == 'xls') {
                    if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                        $excel = $request->supplier;
                        ErpExcelImporter::excelFileProcess($attach, $excel,'');
                    }
                } elseif ($extension == 'zip') {
                    if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                        $excel = $request->supplier;
                        $attachments_array = [];
                        $attachments       = ErpExcelImporter::excelZipProcess('', $attach, $excel, '', $attachments_array);
                        
                    }
                }
            }


        }

        
        return response()->json(['message' => 'Successfully Imported'], 200);

    }

    public static function downloadFromURL($url, $supplier)
    {
        $WETRANSFER_API_URL = 'https://wetransfer.com/api/v4/transfers/';



        if (strpos($url, 'https://we.tl/') !== false) {
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Feirefox/21.0"); // Necessary. The server checks for a valid User-Agent.
            curl_exec($ch);

            $response = curl_exec($ch);
            preg_match_all('/^Location:(.*)$/mi', $response, $matches);
            curl_close($ch);

            if(isset($matches[1])){
                if(isset($matches[1][0])){
                    $url = trim($matches[1][0]);
                }
            }

        }

        //replace https://wetransfer.com/downloads/ from url

        $url = str_replace('https://wetransfer.com/downloads/', '', $url);

        //making array from url

        $dataArray = explode('/', $url);

        if(count($dataArray) == 2){
            $securityhash = $dataArray[1];
            $transferId = $dataArray[0];
        }elseif(count($dataArray) == 3){
            $securityhash = $dataArray[2];
            $recieptId = $dataArray[1];
            $transferId = $dataArray[0];
        }else{
            die('Something is wrong with url');
        }




        //making post request to get the url
        $data = array();
        $data['intent'] = 'entire_transfer';
        $data['security_hash'] = $securityhash;

        $curlURL = $WETRANSFER_API_URL.$transferId.'/download'; 

          $cookie= "cookie.txt";
          $url='https://wetransfer.com/';
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_COOKIESESSION, true);
          curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/'.$cookie);
          curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/'.$cookie);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($ch);
          if (curl_errno($ch)) die(curl_error($ch));

          $re = '/name="csrf-token" content="([^"]+)"/m';

            preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);

            if(count($matches) != 0){
                if(isset($matches[0])){
                    if(isset($matches[0][1])){
                        $token = $matches[0][1];
                    }
                }
            }

          $headers[] = 'Content-Type: application/json';
          $headers[] = 'X-CSRF-Token:' .  $token;

          curl_setopt($ch, CURLOPT_URL, $curlURL);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);   
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

          $real = curl_exec($ch);

          $urlResponse = json_decode($real);

          //dd($urlResponse);

          if(isset($urlResponse->direct_link)){
               //echo $real;
              $downloadURL = $urlResponse->direct_link;
              
              $d = explode('?',$downloadURL);

              $fileArray = explode('/',$d[0]);

              $filename = end($fileArray);

              $file = file_get_contents($downloadURL);

              \Storage::put($filename,$file);

              $storagePath  = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
              
              $path = $storagePath."/".$filename;
            
              $get = \Storage::get($filename);  
                
                if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                    
                    if(strpos($filename, '.zip') !== false){
                        $attachments = ErpExcelImporter::excelZipProcess($path, $filename , $supplier, '', '');
                    }


                    if(strpos($filename, '.xls') !== false || strpos($filename, '.xlsx') !== false){
                        if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                            $excel = $supplier;
                            ErpExcelImporter::excelFileProcess($path, $filename,'');
                        }
                    }



                }           
          }

          

    }

    public function bluckAction(Request $request){
        $ids = $request->ids;
        $status = $request->status;
        $action_type = $request->action_type;

        if($action_type == "delete"){
            session()->flash('success', 'Email has been moved to trash successfully');
            Email::whereIn('id',$ids)->update(['status' => 'bin']);
        }else{
            session()->flash('success', 'Status has been updated successfully');
            Email::whereIn('id',$ids)->update(['status' => $status]);
        }

        return response()->json(['type' => 'success'],200);
    }

    public function changeStatus(Request $request){
        Email::where('id',$request->email_id)->update(['status' => $request->status_id]);
        session()->flash('success', 'Status has been updated successfully');
        return response()->json(['type' => 'success'],200);
    }

    public function syncroniseEmail()
    {

        $report = CronJobReport::create([
            'signature'  => "fetch:all_emails",
            'start_time' => \Carbon\Carbon::now(),
        ]);

        $emailAddresses = EmailAddress::orderBy('id', 'asc')->get();

        foreach ($emailAddresses as $emailAddress) {
            try {
                $imap = new Client([
                    'host'          => $emailAddress->host,
                    'port'          => 993,
                    'encryption'    => "ssl",
                    'validate_cert' => true,
                    'username'      => $emailAddress->username,
                    'password'      => $emailAddress->password,
                    'protocol'      => 'imap',
                ]);

                $imap->connect();

                $types = [
                    'inbox' => [
                        'inbox_name' => 'INBOX',
                        'direction'  => 'from',
                        'type'       => 'incoming',
                    ],
                    'sent'  => [
                        'inbox_name' => 'INBOX.Sent',
                        'direction'  => 'to',
                        'type'       => 'outgoing',
                    ],
                ];


                $available_models = [
                    "supplier" => \App\Supplier::class, "vendor" => \App\Vendor::class,
                    "customer" => \App\Customer::class, "users"  => \App\User::class,
                ];
                $email_list = [];
                foreach ($available_models as $key => $value) {
                    $email_list[$value] = $value::whereNotNull('email')->pluck('id', 'email')->unique()->all();
                }

                foreach ($types as $type) {

                    //dump("Getting emails for: " . $type['type']);
                    $inbox = $imap->getFolder($type['inbox_name']);
                    if ($type['type'] == "incoming") {
                        $latest_email = Email::where('to', $emailAddress->from_address)->where('type', $type['type'])->latest()->first();
                    } else {
                        $latest_email = Email::where('from', $emailAddress->from_address)->where('type', $type['type'])->latest()->first();
                    }

                    $latest_email_date = $latest_email ? Carbon::parse($latest_email->created_at) : false;
                    if ($latest_email_date) {
                        $emails = $inbox->messages()->where('SINCE', $latest_email_date->subDays(1)->format('d-M-Y'));
                    } else {
                        $emails = $inbox->messages();
                    }

                    $emails = $emails->get();
                    foreach ($emails as $email) {

                        $reference_id = $email->references;
                        //                        dump($reference_id);
                        $origin_id = $email->message_id;

                        // Skip if message is already stored
                        if (Email::where('origin_id', $origin_id)->count() > 0) {
                            continue;
                        }

                        // check if email has already been received

                        if ($email->hasHTMLBody()) {
                            $content = $email->getHTMLBody();
                        } else {
                            $content = $email->getTextBody();
                        }

                        $email_subject = $email->getSubject();
                        \Log::channel('customer')->info("Subject  => " . $email_subject);

                        //if (!$latest_email_date || $email->getDate()->timestamp > $latest_email_date->timestamp) {
                        $attachments_array = [];
                        $attachments       = $email->getAttachments();
                        $fromThis          = $email->getFrom()[0]->mail;
                        $attachments->each(function ($attachment) use (&$attachments_array, $fromThis, $email_subject) {
                            $attachment->name = preg_replace("/[^a-z0-9\_\-\.]/i", '', $attachment->name);
                            file_put_contents(storage_path('app/files/email-attachments/' . $attachment->name), $attachment->content);
                            $path = "email-attachments/" . $attachment->name;

                            $attachments_array[] = $path;

                            /*start 3215 attachment fetch from DHL mail */
                            \Log::channel('customer')->info("Match Start  => " . $email_subject);

                            $findFromEmail = explode('@', $fromThis);
                            if (strpos(strtolower($email_subject), "your copy invoice") !== false && isset($findFromEmail[1]) && (strtolower($findFromEmail[1]) == 'dhl.com')) {
                                \Log::channel('customer')->info("Match Found  => " . $email_subject);
                                $this->getEmailAttachedFileData($attachment->name);
                            }
                            /*end 3215 attachment fetch from DHL mail */
                        });

                        $from = $email->getFrom()[0]->mail;
                        $to   = array_key_exists(0, $email->getTo()) ? $email->getTo()[0]->mail : $email->getReplyTo()[0]->mail;

                        // Model is sender if its incoming else its receiver if outgoing
                        if ($type['type'] == 'incoming') {
                            $model_email = $from;
                        } else {
                            $model_email = $to;
                        }

                        // Get model id and model type

                        extract($this->getModel($model_email, $email_list));
                        /**
                         * @var $model_id
                         * @var $model_type
                         */

                        $subject = explode("#", $email_subject);
                        if (isset($subject[1]) && !empty($subject[1])) {
                            $findTicket = \App\Tickets::where('ticket_id', $subject[1])->first();
                            if ($findTicket) {
                                $model_id   = $findTicket->id;
                                $model_type = \App\Tickets::class;
                            }
                        }

                        $params = [
                            'model_id'        => $model_id,
                            'model_type'      => $model_type,
                            'origin_id'       => $origin_id,
                            'reference_id'    => $reference_id,
                            'type'            => $type['type'],
                            'seen'            => $email->getFlags()['seen'],
                            'from'            => $email->getFrom()[0]->mail,
                            'to'              => array_key_exists(0, $email->getTo()) ? $email->getTo()[0]->mail : $email->getReplyTo()[0]->mail,
                            'subject'         => $email->getSubject(),
                            'message'         => $content,
                            'template'        => 'customer-simple',
                            'additional_data' => json_encode(['attachment' => $attachments_array]),
                            'created_at'      => $email->getDate(),
                        ];

                        Email::create($params);

                        if ($type['type'] == 'incoming') {
                            $message = trim($content);
                            $reply    = (new EmailParser())->parse($message);
                            $fragment = current($reply->getFragments());
                            if ($reply) {
                                $customer = \App\Customer::where('email', $from)->first();
                                if (!empty($customer)) {
                                    // store the main message
                                    $params = [
                                        'number'      => $customer->phone,
                                        'message'     => $fragment->getContent(),
                                        'media_url'   => null,
                                        'approved'    => 0,
                                        'status'      => 0,
                                        'contact_id'  => null,
                                        'erp_user'    => null,
                                        'supplier_id' => null,
                                        'task_id'     => null,
                                        'dubizzle_id' => null,
                                        'vendor_id'   => null,
                                        'customer_id' => $customer->id,
                                        'is_email'    => 1
                                    ];
                                    $messageModel = \App\ChatMessage::create($params);
                                    \App\Helpers\MessageHelper::whatsAppSend($customer, $fragment->getContent(), null, null, $isEmail = true);
                                    \App\Helpers\MessageHelper::sendwatson($customer, $fragment->getContent(), null, $messageModel, $params , $isEmail = true);
                                }
                            }
                        }

                        //}
                    }
                }

                $historyParam = [
                    'email_address_id' => $emailAddress->id,
                    'is_success'       => 1,
                ];

                
                EmailRunHistories::create($historyParam);
                $report->update(['end_time' => Carbon::now()]);
                session()->flash('success', 'Emails added successfully');
                return redirect('/email');
            } catch (\Exception $e) {

                \Log::channel('customer')->info($e->getMessage());
                $historyParam = [
                    'email_address_id' => $emailAddress->id,
                    'is_success'       => 0,
                    'message'          => $e->getMessage(),
                ];
                EmailRunHistories::create($historyParam);
                \App\CronJob::insertLastError("fetch:all_emails", $e->getMessage());
                session()->flash('danger', $e->getMessage());
                return redirect('/email');
            }
        }
    }

    public function getModel($email, $email_list)
    {
        $model_id   = null;
        $model_type = null;

        // Traverse all models
        foreach ($email_list as $key => $value) {

            // If email exists in the DB
            if (isset($value[$email])) {
                $model_id   = $value[$email];
                $model_type = $key;
                break;
            }
        }

        return compact('model_id', 'model_type');
    }

    public function getEmailAttachedFileData($fileName = '')
    {
        $file = fopen(storage_path('app/files/email-attachments/' . $fileName), "r");

        $skiprowupto           = 1; //skip first line
        $rowincrement          = 1;
        $attachedFileDataArray = array();
        while (($data = fgetcsv($file, 4000, ",")) !== false) {
            if ($rowincrement > $skiprowupto) {
                //echo '<pre>'.print_r($data = fgetcsv($file, 4000, ","),true).'</pre>';
                if (isset($data[0]) && !empty($data[0])) {
                    try {
                        $due_date              = date('Y-m-d', strtotime($data[9]));
                        $attachedFileDataArray = array(
                            "line_type"                       => $data[0],
                            "billing_source"                  => $data[1],
                            "original_invoice_number"         => $data[2],
                            "invoice_number"                  => $data[3],
                            "invoice_identifier"              => $data[5],
                            "invoice_type"                    => $data[6],
                            "invoice_currency"                => $data[69],
                            "invoice_amount"                  => $data[70],
                            "invoice_type"                    => $data[6],
                            "invoice_date"                    => $data[7],
                            "payment_terms"                   => $data[8],
                            "due_date"                        => $due_date,
                            "billing_account"                 => $data[11],
                            "billing_account_name"            => $data[12],
                            "billing_account_name_additional" => $data[13],
                            "billing_address_1"               => $data[14],
                            "billing_postcode"                => $data[17],
                            "billing_city"                    => $data[18],
                            "billing_state_province"          => $data[19],
                            "billing_country_code"            => $data[20],
                            "billing_contact"                 => $data[21],
                            "shipment_number"                 => $data[23],
                            "shipment_date"                   => $data[24],
                            "product"                         => $data[30],
                            "product_name"                    => $data[31],
                            "pieces"                          => $data[32],
                            "origin"                          => $data[33],
                            "orig_name"                       => $data[34],
                            "orig_country_code"               => $data[35],
                            "orig_country_name"               => $data[36],
                            "senders_name"                    => $data[37],
                            "senders_city"                    => $data[42],
                            'created_at'                      => \Carbon\Carbon::now(),
                            'updated_at'                      => \Carbon\Carbon::now(),
                        );
                        if (!empty($attachedFileDataArray)) {
                            $attachresponse = \App\Waybillinvoice::create($attachedFileDataArray);

                            // check that way bill exist not then create
                            $wayBill = \App\Waybill::where("awb", $attachresponse->shipment_number)->first();
                            if (!$wayBill) {
                                $wayBill      = new \App\Waybill;
                                $wayBill->awb = $attachresponse->shipment_number;

                                $wayBill->from_customer_name      = $data[45];
                                $wayBill->from_city               = $data[42];
                                $wayBill->from_country_code       = $data[44];
                                $wayBill->from_customer_address_1 = $data[38];
                                $wayBill->from_customer_address_2 = $data[39];
                                $wayBill->from_customer_pincode   = $data[41];
                                $wayBill->from_company_name       = $data[39];

                                $wayBill->to_customer_name      = $data[50];
                                $wayBill->to_city               = $data[55];
                                $wayBill->to_country_code       = $data[57];
                                $wayBill->to_customer_phone     = "";
                                $wayBill->to_customer_address_1 = $data[51];
                                $wayBill->to_customer_address_2 = $data[52];
                                $wayBill->to_customer_pincode   = $data[54];
                                $wayBill->to_company_name       = "";

                                $wayBill->actual_weight = $data[68];
                                $wayBill->volume_weight = $data[66];

                                $wayBill->cost_of_shipment = $data[70];
                                $wayBill->package_slip     = $attachresponse->shipment_number;
                                $wayBill->pickup_date      = date("Y-m-d", strtotime($data[24]));
                                $wayBill->save();
                            }

                            $cash_flow = new CashFlow();
                            $cash_flow->fill([
                                'date'                => $attachresponse->due_date ? $attachresponse->due_date : null,
                                'type'                => 'pending',
                                'description'         => 'Waybill invoice details',
                                'cash_flow_able_id'   => $attachresponse->id,
                                'cash_flow_able_type' => \App\Waybillinvoice::class,
                            ])->save();

                        }
                    } catch (\Exception $e) {
                        \Log::error("Error from the dhl invoice : " . $e->getMessage());
                    }

                }
            }
            $rowincrement++;
        }
        fclose($file);
    }
}
