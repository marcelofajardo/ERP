<?php

namespace App\Console\Commands;

use App\CashFlow;
use App\CronJobReport;
use App\Email;
use App\EmailAddress;
use App\EmailRunHistories;
use App\Supplier;
use Carbon\Carbon;
use EmailReplyParser\Parser\EmailParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Webklex\IMAP\Client;

/**
 * @author Sukhwinder <sukhwinder@sifars.com>
 * This command takes care of receiving all the emails from the smtp set in the environment
 *
 * All fetched emails will go inside emails table
 */
class FetchAllEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:all_emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all emails from the configured SMTP settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $report = CronJobReport::create([
            'signature'  => $this->signature,
            'start_time' => Carbon::now(),
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

                    dump("Getting emails for: " . $type['type']);

                    $inbox = $imap->getFolder($type['inbox_name']);
                    if ($type['type'] == "incoming") {
                        $latest_email = Email::where('to', $emailAddress->from_address)->where('type', $type['type'])->latest()->first();
                    } else {
                        $latest_email = Email::where('from', $emailAddress->from_address)->where('type', $type['type'])->latest()->first();
                    }

                    $latest_email_date = $latest_email ? Carbon::parse($latest_email->created_at) : false;

                    dump("Last received at: " . ($latest_email_date ?: 'never'));
                    // Uncomment below just for testing purpose
                    //                    $latest_email_date = Carbon::parse('2020-01-01');

                    if ($latest_email_date) {
                        $emails = $inbox->messages()->where('SINCE', $latest_email_date->subDays(1)->format('d-M-Y'));
                    } else {
                        $emails = $inbox->messages();
                    }

                    $emails = $emails->get();

                    //
                    // dump($inbox->messages()->where([
                    //     ['SINCE', $latest_email_date->subDays(1)->format('d-M-Y')],
                    //     ])->get());
                    foreach ($emails as $email) {

                        $reference_id = $email->references;
                        //                        dump($reference_id);
                        $origin_id = $email->message_id;

                        // Skip if message is already stored
                        if (Email::where('origin_id', $origin_id)->count() > 0) {
                            continue;
                        }

                        // check if email has already been received

                        $textContent = $email->getTextBody();
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
                        //                            dump("Received from: ". $email->getFrom()[0]->mail);
                        Email::create($params);

                        if ($type['type'] == 'incoming') {

                            $message = trim($textContent);

                            $reply = (new EmailParser())->parse($message);

                            $fragment = current($reply->getFragments());

                            $pattern = '(On[^abc,]*, (Jan(uary)?|Feb(ruary)?|Mar(ch)?|Apr(il)?|May|Jun(e)?|Jul(y)?|Aug(ust)?|Sep(tember)?|Oct(ober)?|Nov(ember)?|Dec(ember)?)\s+\d{1,2},\s+\d{4}, (1[0-2]|0?[1-9]):([0-5][0-9]) ([AaPp][Mm]))';

                            $reply = strip_tags($fragment);

                            $reply = preg_replace($pattern, " ", $reply);

                            $mailFound = false;
                            if ($reply) {
                                $customer = \App\Customer::where('email', $from)->first();
                                if (!empty($customer)) {
                                    // store the main message
                                    $params = [
                                        'number'      => $customer->phone,
                                        'message'     => $reply,
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
                                        'is_email'    => 1,
                                    ];
                                    $messageModel = \App\ChatMessage::create($params);
                                    \App\Helpers\MessageHelper::whatsAppSend($customer, $reply, null, null, $isEmail = true);
                                    \App\Helpers\MessageHelper::sendwatson($customer, $reply, null, $messageModel, $params, $isEmail = true);
                                    $mailFound = true;
                                }

                                if (!$mailFound) {
                                    $vandor = \App\Vendor::where('email', $from)->first();
                                    if ($vandor) {
                                        $params = [
                                            'number'      => $vandor->phone,
                                            'message'     => $reply,
                                            'media_url'   => null,
                                            'approved'    => 0,
                                            'status'      => 0,
                                            'contact_id'  => null,
                                            'erp_user'    => null,
                                            'supplier_id' => null,
                                            'task_id'     => null,
                                            'dubizzle_id' => null,
                                            'vendor_id'   => $vandor->id,
                                            'is_email'    => 1,
                                        ];
                                        $messageModel = \App\ChatMessage::create($params);
                                        $mailFound = true;
                                    }
                                }

                                if (!$mailFound) {
                                    $supplier = \App\Supplier::where('email', $from)->first();
                                    if ($supplier) {
                                        $params = [
                                            'number'      => $supplier->phone,
                                            'message'     => $reply,
                                            'media_url'   => null,
                                            'approved'    => 0,
                                            'status'      => 0,
                                            'contact_id'  => null,
                                            'erp_user'    => null,
                                            'supplier_id' => $supplier->id,
                                            'task_id'     => null,
                                            'dubizzle_id' => null,
                                            'is_email'    => 1,
                                        ];
                                        $messageModel = \App\ChatMessage::create($params);
                                        $mailFound = true;
                                    }
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

                dump('__________');

                $report->update(['end_time' => Carbon::now()]);
            } catch (\Exception $e) {

                \Log::channel('customer')->info($e->getMessage());
                $historyParam = [
                    'email_address_id' => $emailAddress->id,
                    'is_success'       => 0,
                    'message'          => $e->getMessage(),
                ];
                EmailRunHistories::create($historyParam);
                \App\CronJob::insertLastError($this->signature, $e->getMessage());
            }
        }
    }

    /**
     * Check all the emails in the DB and extract the model type from there
     *
     * @param [type] $email
     * @param [type] $email_list
     * @return array(model_id,miodel_type)
     */
    private function getModel($email, $email_list)
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
            ///echo '<pre>'.print_r($data,true).'</pre>'; die('developer working');
            /* foreach($data as $d){
            $d=str_replace('(','',$d);
            $d=str_replace(')','',$d);
            $d=str_replace('.','',$d);
            $d=str_replace('&','',$d);
            $d=str_replace(' ','_',$d);
            $d=strtolower($d);
            //echo $csvArrayIndex.' - $table->string("'.$d.'")->nullable();'."\n";
            //echo '"'.$d.'"=>$data['.$csvArrayIndex.']'."\n";
            $csvArrayIndex++;
            }
            exit; */
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
