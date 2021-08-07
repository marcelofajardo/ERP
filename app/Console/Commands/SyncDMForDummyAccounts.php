<?php

namespace App\Console\Commands;

use App\Account;
use App\CronJobReport;
use App\Customer;
use App\InstagramThread;
use App\Review;
use Carbon\Carbon;
use Illuminate\Console\Command;
use InstagramAPI\Instagram;

Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;

class SyncDMForDummyAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:instagram-messages-for-dm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Instagram Direct Messaging With Customers Page';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $messages;

    public function __construct(Instagram $messages)
    {
        parent::__construct();
        $this->messages = $messages;
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

            $accounts = Account::where('platform', 'instagram')->get();

            foreach ($accounts as $account) {
                try {
                    $this->messages->login($account->last_name, $account->password);
                } catch (\Exception $e) {
                    dd($e);
                    echo "ERROR $account->last_name \n";
                    continue;
                }

                $this->messages->logout();

                $inbox = $this->messages->direct->getInbox()->asArray();
                if (isset($inbox['inbox']['threads'])) {
                    $threads = $inbox['inbox']['threads'];
                    foreach ($threads as $thread) {
                        $user = $thread['users'];
                        if (count($user) !== 1) {
                            continue;
                        }
                        echo $user[0]['username'] . " will be created now.\n";
                        $customer = $this->createCustomer($user[0]);

                        if (!$customer) {
                            continue;
                        }

                        $currentUser = $this->messages->account_id;

                        $this->createThread($customer, $thread);
                        $this->createReview($customer, $thread, $account->last_name, $currentUser);

                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    /**
     * @param $user
     * @return Customer|void
     */
    private function createCustomer($user)
    {
        $customer = Customer::where('instahandler', $user['pk'])->first();
        if (!$customer) {
            $customer = Customer::where('ig_username', $user['username'])->first();
        }

        if (!$customer) {
            $customer       = new Customer();
            $customer->name = $user['full_name'];
        }

        $customer->instahandler = $user['pk'];
        $customer->ig_username  = $user['username'];
        $customer->save();

        return $customer;
    }

    private function createThread($customer, $t)
    {

        $thread               = new InstagramThread();
        $thread->customer_id  = $customer->id;
        $thread->thread_id    = $t['thread_id'];
        $thread->thread_v2_id = $t['thread_v2_id'];
        $thread->save();

    }

    private function createReview($customer, $t, $accUser, $currentUserId)
    {
        $review                 = new Review();
        $review->customer_id    = $customer->id;
        $thread                 = $this->messages->direct->getThread($t['thread_id'])->asArray();
        $thread                 = $thread['thread'];
        $threadJson['messages'] = array_map(function ($item) use ($customer, $accUser, $currentUserId) {
            $text = '';
            if ($item['item_type'] == 'text') {
                $text = $item['text'];
            } else if ($item['item_type'] == 'like') {
                $text = $item['like'];
            } else if ($item['item_type'] == 'media') {
                $text = $item['media']['image_versions2']['candidates'][0]['url'];
            }
            return '<strong>' . ($item['user_id'] == $currentUserId ? $accUser : $customer->ig_username) . '</strong>' . ' =>' . $text;
        }, $thread['items']);

        $review->review   = '<ul>' . implode('<li>', $threadJson['messages']) . '</ul>';
        $review->platform = 'instagram_dm';
        $review->save();

    }
}
