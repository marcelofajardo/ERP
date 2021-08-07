<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Customer;
use App\FacebookMessages;
use App\Services\Facebook\Facebook;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateCustomerFromFacebook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:import-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $facebook;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Facebook $facebook)
    {
        $this->facebook = $facebook;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $conversations = $this->facebook->getConversations();

            foreach ($conversations['data'] as $conversation) {
                $participants = $conversation['participants']['data'];
                $participant  = $this->extractParticipant($participants);

                if ($participant === false) {
                    continue;
                }

                $customer = $this->createCustomer($participant[0], $participant[1]);

                if ($customer === false) {
                    continue;
                }

                dump("creating customer...");

                $this->storeMessages($customer, $conversation['id']);

            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function createCustomer($facebookId, $name = '')
    {
        $customer = Customer::where('facebook_id', $facebookId)->first();
        if ($customer) {
            return false;
        }

        $customer              = new Customer();
        $customer->name        = $name;
        $customer->facebook_id = $facebookId;
        $customer->save();

        return $customer;

    }

    public function storeMessages($customer, $cid)
    {
        $messages = $this->facebook->getConversation($cid);
        $messages = array_reverse($messages['messages']['data']);

        foreach ($messages as $message) {
            $is_from_me = 0;
            $text       = $message['message'];
            $from       = $message['from']['id'];
            $to         = $message['to']['data'][0]['id'];

            if ($from === '507935072915757') {
                $is_from_me = 1;
            }

            $fbMessage                = new FacebookMessages();
            $fbMessage->customer_id   = $customer->id;
            $fbMessage->sender        = $from;
            $fbMessage->receiver      = $to;
            $fbMessage->is_sent_by_me = $is_from_me;
            $fbMessage->message       = $text;
            $fbMessage->save();

            dump('saved message...');

        }
    }

    private function extractParticipant($ps)
    {
        foreach ($ps as $p) {
            if (!$p['id'] !== '507935072915757') {
                return [$p['id'], $p['name']];
            }
        }

        return false;
    }
}
