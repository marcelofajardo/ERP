<?php

namespace App\Jobs;

use App\ChatbotQuestion;
use App\ChatbotQuestionReply;
use App\WatsonAccount;
use App\ChatbotErrorLog;
use App\ChatbotDialogErrorLog;
use App\ChatbotQuestionErrorLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Library\Watson\Language\Assistant\V2\AssistantService;
use App\Library\Watson\Language\Workspaces\V1\DialogService;
use App\Library\Watson\Language\Workspaces\V1\EntitiesService;
use App\Library\Watson\Language\Workspaces\V1\LogService;
use App\Library\Watson\Language\Workspaces\V1\IntentService;

class ManageWatson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $question;
    protected $method;
    protected $storeParams;
    protected $type;
    protected $old_example;
    protected $service;
    protected $oldValue;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($service, $question, array $storeParams, $method, $type = 'value', $old_example = false, $oldValue = null)
    {
        $this->question = $question;
        $this->method = $method;
        $this->storeParams = $storeParams;
        $this->type = $type;
        $this->old_example = $old_example;
        $this->service = $service;
        $this->oldValue = $oldValue;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
// dd($this->storeParams);
        $all_watson_accounts = WatsonAccount::get();
        if ($this->type) {
            $value = $this->question->{$this->type};
        }
        $serviceClass = 'IntentService';

        if ($this->service === 'dialog') {
            $serviceClass = 'DialogService';
        } elseif ($this->service === 'entity') {
            $serviceClass = 'EntitiesService';
        }

        foreach ($all_watson_accounts as $account) {
            if ($this->service === 'dialog') {
                $watson = new DialogService(
                    "apiKey",
                    $account->api_key
                );
            } else if ($this->service === 'entity') {
                $watson = new EntitiesService(
                    "apiKey",
                    $account->api_key
                );
                $value = $this->oldValue;
            }else{
                $watson = new IntentService(
                    "apiKey",
                    $account->api_key
                );
                $value = $this->oldValue;
            }
            $watson->set_url($account->url);
            if ($this->method === 'create') {
                $result = $watson->create($account->work_space_id, $this->storeParams);
            } else if ($this->method === 'update') {
                $result = $watson->update($account->work_space_id, $value, $this->storeParams);
            } else if ($this->method === 'delete') {
                $result = $watson->delete($account->work_space_id, $value);
            } else if ($this->method === 'update_example') {
                $result = $watson->updateExample($account->work_space_id, $value, $this->old_example, $this->storeParams);
            }

            $status = $result->getStatusCode();
            if($status == 201 || $status == 200) {
                $success = 1;
            }
            else {
                $success = 0;
            }
            if ($this->service === 'dialog') {
                $errorlog = new ChatbotDialogErrorLog;
                $errorlog->chatbot_dialog_id = $this->question->id;
                $errorlog->store_website_id = $account->store_website_id;
                $errorlog->status = $success;
                $errorlog->response = $result->getContent();
                $errorlog->save();

                ChatbotQuestion::where( 'id', $this->question->id )->update([ 'watson_status' => $success]);

            } else {

                ChatbotQuestion::where( 'id', $this->question->id )->update([ 'watson_status' => $success]);

                $errorlog = new ChatbotErrorLog;
                $errorlog->chatbot_question_id = $this->question->id;
                $errorlog->store_website_id = $account->store_website_id;
                $errorlog->status = $success;
                $errorlog->response = $result->getContent();
                $errorlog->save();

                $question_error = new ChatbotQuestionErrorLog();
                $question_error->chatbot_question_id = $this->question->id;
                $question_error->type = $this->service;
                $question_error->request = json_encode($this->storeParams);
                $question_error->response = $errorlog->response;
                $question_error->response_type = $success == 0 ? "error" : "success";
                $question_error->save();
                
                
            }
        }
    }

    public function fail($exception = null)
    {
        /* Remove data when job fail while creating..... */
        if ($this->method === 'create' && is_object($this->question)) {
            $this->question->delete();
        }
    }
}
