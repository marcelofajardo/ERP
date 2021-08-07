<?php

namespace App\Jobs;

use App\ChatbotQuestion;
use App\Customer;
use App\Library\Watson\Model;
use App\WatsonAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Library\Watson\Language\Assistant\V2\AssistantService;
use App\Library\Watson\Language\Workspaces\V1\DialogService;
use App\Library\Watson\Language\Workspaces\V1\EntitiesService;
use App\Library\Watson\Language\Workspaces\V1\IntentService;
use App\Library\Watson\Language\Workspaces\V1\LogService;

class ManageWatsonAssistant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;
    protected $inputText;
    protected $contextReset;
    protected $message_application_id;
    protected $messageModel;
    protected $userType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer, $inputText, $contextReset, $message_application_id, $messageModel = null, $userType = null)
    {
        $this->customer = $customer;
        $this->inputText = $inputText;
        $this->contextReset = $contextReset;
        $this->message_application_id = $message_application_id;
        $this->messageModel = $messageModel;
        $this->userType = $userType;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $store_website_id = ($this->customer->store_website_id > 0) ? $this->customer->store_website_id : 1;

        $account = WatsonAccount::where('store_website_id', $store_website_id)->first();
        if($account) {
            $asistant = new AssistantService(
                "apiKey",
                $account->api_key
            );
            $asistant->set_url($account->url);
            Model::sendMessageFromJob($this->customer, $account, $asistant, $this->inputText, $this->contextReset, $this->message_application_id, $this->messageModel , $this->userType);
        }
    }

    public function fail($exception = null)
    {
        /* Remove data when job fail while creating..... */
        if($this->method === 'create' && is_object($this->question)){
            $this->question->delete();
        }
    }
}
