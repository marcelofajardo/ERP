<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MailinglistTemplateCategory;
use App\Customer;
use App\MailinglistTemplate;
use App\EmailAddress;
use Mail;
use App\Mail\DobAndAnniversaryMail;
class CustomerDobAndAnniversryMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:dob-and-anniversary-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail to customer on birthdays and anniversaries';

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
        //Birthdays
        $customerBirtdays = \App\Customer::whereDay('dob', date('d'))
        ->WhereMonth('dob', date('m'))
        ->get();

        $mailingListCategory = MailinglistTemplateCategory::where('title','Birthday')->first();
        if($mailingListCategory){
            foreach($customerBirtdays as $customer) {
                try {
                    if($customer->store_website_id) {
                        $templateData = MailinglistTemplate::where('category_id', $mailingListCategory->id )->where("store_website_id",$customer->store_website_id)->first();
                        $storeEmailAddress = EmailAddress::where('store_website_id',$customer->store_website_id)->first();
    
                    }else{
                        $templateData = MailinglistTemplate::where('category_id', $mailingListCategory->id )->first();
                        $storeEmailAddress = EmailAddress::first();
                    }
                    if($templateData && $storeEmailAddress && $customer->email) {
                        if($templateData->static_template) {
                            $arrToReplace = ['{FIRST_NAME}'];
                            $valToReplace = [$customer->name];
                            $bodyText = str_replace($arrToReplace,$valToReplace,$templateData->static_template);
                        }
                        else {
                            $bodyText  = @(string)view($templateData->mail_tpl);
                        }
                        $emailData['subject'] = $templateData->subject;
                        $emailData['template'] = $bodyText;
                        $emailData['from'] = $storeEmailAddress->from_address;
                        //Mail::to($customer->email)->send(new DobAndAnniversaryMail($emailData));


                        $emailClass = (new DobAndAnniversaryMail($emailData))->build();

                        $email = \App\Email::create([
                            'model_id'        => $customer->id,
                            'model_type'      => \App\Customer::class,
                            'from'            => $emailClass->fromMailer,
                            'to'              => $customer->email,
                            'subject'         => $templateData->subject,
                            'message'         => $emailClass->render(),
                            'template'        => 'birthday-mail',
                            'additional_data' => $order->id,
                            'status'          => 'pre-send',
                            'is_draft'        => 1,
                        ]);

                        \App\Jobs\SendEmail::dispatch($email);

                    }
                }
                catch (\Exception $e) {
                    \App\CronJob::insertLastError($this->signature, $e->getMessage());
                    continue;
                }
            }
        }
           //Anniversaries
           $customerAnniversaries = \App\Customer::whereDay('wedding_anniversery', date('d'))
           ->WhereMonth('wedding_anniversery', date('m'))
           ->get();
           $mailingListCategory = MailinglistTemplateCategory::where('title','Wedding Anniversary')->first();
           if($mailingListCategory){
               foreach($customerAnniversaries as $customer) {
                   try {
                       if($customer->store_website_id) {
                           $templateData = MailinglistTemplate::where('category_id', $mailingListCategory->id )->where("store_website_id",$customer->store_website_id)->first();
                           $storeEmailAddress = EmailAddress::where('store_website_id',$customer->store_website_id)->first();
       
                       }else{
                           $templateData = MailinglistTemplate::where('category_id', $mailingListCategory->id )->first();
                           $storeEmailAddress = EmailAddress::first();
                       }
                       if($templateData && $storeEmailAddress && $customer->email) {
                           if($templateData->static_template) {
                               $arrToReplace = ['{FIRST_NAME}'];
                               $valToReplace = [$customer->name];
                               $bodyText = str_replace($arrToReplace,$valToReplace,$templateData->static_template);
                           }
                           else {
                               $bodyText  = @(string)view($templateData->mail_tpl);
                           }
                           $emailData['subject'] = $templateData->subject;
                           $emailData['template'] = $bodyText;
                           $emailData['from'] = $storeEmailAddress->from_address;

                           //Mail::to($customer->email)->send(new DobAndAnniversaryMail($emailData));

                           $emailClass = (new DobAndAnniversaryMail($emailData))->build();
 
                           $email = \App\Email::create([
                              'model_id'        => $customer->id,
                              'model_type'      => \App\Customer::class,
                              'from'            => $emailClass->fromMailer,
                              'to'              => $customer->email,
                              'subject'         => $templateData->subject,
                              'message'         => $emailClass->render(),
                              'template'        => 'wedding-anniversery-mail',
                              'additional_data' => $order->id,
                              'status'          => 'pre-send',
                              'is_draft'        => 1,
                           ]);

                           \App\Jobs\SendEmail::dispatch($email);
                       }
                   }
                   catch (\Exception $e) {
                       \App\CronJob::insertLastError($this->signature, $e->getMessage());
                       continue;
                   }
               }
           }
    }
}
