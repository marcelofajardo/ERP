<?php

namespace App\Console\Commands;

use App\MailinglistEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MailingListSendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailingListSendMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending templates with sendinblue';

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
        $mailing_list = MailinglistEmail::orderBy('created_at','desc')->get();
        $now = Carbon::now();
        foreach ($mailing_list as $mailing){
            $emails = $mailing->audience->listCustomers->pluck('email');
            $array_emails = [];
            foreach ($emails as $email){
                array_push($array_emails,["email" => $email]);
            }
            $diff = $now->diffInMinutes($mailing->scheduled_date);
            if($diff <= 15 && $mailing->progress == 0){
                $htmlContent = $mailing->html;
                $data = [
                    "to" => $array_emails,
                    "sender" => [
                        "id" => 1,
                        "email" => 'Info@theluxuryunlimited.com'
                    ],
                    "subject" => 'test',
                    "htmlContent" => $htmlContent
                ];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",

                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_HTTPHEADER => array(
                        "api-key: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",
                        "Content-Type: application/json"
                    ),
                ));
                curl_close($curl);
                $mailing->progress = 1;
                $mailing->save();
            }
        }
    }
}
