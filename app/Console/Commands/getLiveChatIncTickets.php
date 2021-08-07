<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use App\Tickets;
use Carbon\Carbon;
use Illuminate\Console\Command;

class getLiveChatIncTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livechat:tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get tickets from livechat inc and put them as unread messages';

    /**Created By : Maulik jadvani
    tickets store in tickets table
     *Get tickets from livechat inc and put them as unread messages
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
        try
        {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL            => "https://api.livechatinc.com/v2/tickets",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => array(
                    "Authorization: Basic NmY0M2ZkZDUtOTkwMC00OWY4LWI4M2ItZThkYzg2ZmU3ODcyOmRhbDp0UkFQdWZUclFlLVRkQUI4Y2pFajNn",
                ),
            ));

            $response = curl_exec($curl);

            $result = json_decode($response, true);

            if (!empty($result['tickets'])) {
                $result = $result['tickets'];
            }

            if (isset($result) && count($result) > 0) {
                foreach ($result as $row) {

                    $event  = (isset($row['events'][0])) ? $row['events'][0] : array();
                    $author = (isset($event['author'])) ? $event['author'] : array();

                    $email = (isset($author['id'])) ? $author['id'] : '';
                    $name  = (isset($author['name'])) ? $author['name'] : '';

                    $customer = \App\Customer::where('email', $email)->first();
                    if (isset($customer->id) && ($customer->id) > 0) {
                        $customer_id = $customer->id;
                    } else {
                        $customer        = new \App\Customer;
                        $customer->name  = $name;
                        $customer->email = $email;
                        $customer->save();
                        $customer_id = $customer->id;
                    }

                    $ticket_id = (isset($row['id'])) ? $row['id'] : '';
                    $subject   = (isset($row['subject'])) ? $row['subject'] : '';
                    $message   = (isset($event['message'])) ? $event['message'] : '';
                    $date      = (isset($event['date'])) ? $event['date'] : date();

                    $status = \App\TicketStatuses::where("name", $row['status'])->first();
                    if (!$status) {
                        $status       = new \App\TicketStatuses;
                        $status->name = $row['status'];
                        $status->save();
                    }

                    $Tickets_data = array(
                        'ticket_id'   => $ticket_id,
                        'subject'     => $subject,
                        'message'     => $message,
                        'date'        => $date,
                        'customer_id' => $customer_id,
                        'name'        => $name,
                        'email'       => $email,
                        'status_id'   => $status->id,
                    );

                    $ticketObj = \App\Tickets::where('ticket_id', $ticket_id)->first();
                    if (isset($ticketObj->id) && $ticketObj->id > 0) {

                    } else {
                        Tickets::create($Tickets_data);

                    }
                }

            }

            $report->update(['end_time' => Carbon::now()]);

        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
