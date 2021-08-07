<?php

namespace App\Console\Commands;

use App\ColdLeads;
use App\CronJobReport;
use App\Customer;
use App\RejectedLeads;
use App\Services\Instagram\Automation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutomateLeadsAsColdOrCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automate:leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $automation       = new Automation();
            $customers        = Customer::where('instahandler', '!=', '')->where('rating', '>', 5)->orderBy('created_at', 'DESC')->orderBy('rating', 'DESC')->paginate(5);
            $customers        = $customers->toArray();
            $customerProfiles = $customers['data'];

            foreach ($customerProfiles as $customerProfile) {
                [$followers, $following] = $automation->getUserDetails($customerProfile['instahandler'], true);

                foreach ($followers as $follower) {
                    $c = RejectedLeads::where('identifier', $follower['username'])->first();
                    if ($c) {
                        continue;
                    }
                    $lead           = $automation->getUserDetails($follower['username']);
                    $leadPercentage = $automation->getOverallLeadPercentage($lead);
                    echo $follower['username'] . " => " . $leadPercentage[0] . "\n";
                    if ($leadPercentage[0] > 50) {
                        $customer = ColdLeads::where('username', $lead['user']['username'])->first();
                        if ($customer) {
                            continue;
                        }
                        $customer              = new ColdLeads();
                        $customer->name        = $lead['user']['full_name'];
                        $customer->username    = $lead['user']['username'];
                        $customer->platform_id = $lead['user']['pk'];
                        $customer->rating      = round($leadPercentage[0] * 0.1);
                        $customer->platform    = 'instagram';
                        $customer->image       = $lead['user']['profile_pic_url'];
                        $customer->bio         = $lead['user']['biography'];
                        $customer->because_of  = implode(' ', $automation->getHashtagUsed());
                        $customer->save();

                        $automation->sendMessageTo($customer->platform_id);

                    } else {
                        $rl             = new RejectedLeads();
                        $rl->identifier = $follower['username'];
                        $rl->save();
                    }
                }

                foreach ($following as $follower) {
                    $c = RejectedLeads::where('identifier', $follower['username'])->first();
                    if ($c) {
                        continue;
                    }
                    $lead           = $automation->getUserDetails($follower['username']);
                    $leadPercentage = $automation->getOverallLeadPercentage($lead);
                    echo $follower['username'] . " => " . $leadPercentage[0] . "\n";
                    if ($leadPercentage[0] > 50) {
                        $customer = ColdLeads::where('username', $lead['user']['username'])->first();
                        if ($customer) {
                            continue;
                        }
                        $customer              = new ColdLeads();
                        $customer->name        = $lead['user']['full_name'];
                        $customer->username    = $lead['user']['username'];
                        $customer->platform_id = $lead['user']['pk'];
                        $customer->rating      = round($leadPercentage[0] * 0.1);
                        $customer->platform    = 'instagram';
                        $customer->image       = $lead['user']['profile_pic_url'];
                        $customer->bio         = $lead['user']['biography'];
                        $customer->because_of  = implode(' ', $automation->getHashtagUsed());
                        $customer->save();

                        $automation->sendMessageTo($customer->platform_id);

                    } else {
                        $rl             = new RejectedLeads();
                        $rl->identifier = $follower['username'];
                        $rl->save();
                    }
                }

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
