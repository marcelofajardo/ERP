<?php

namespace App\Services\Instagram;


use App\Account;
use App\ColdLeads;
use App\Customer;
use App\InstagramDirectMessages;
use App\InstagramThread;
use App\InstagramUsersList;
use App\TargetLocation;
use Carbon\Carbon;
use InstagramAPI\Instagram;
use InstagramAPI\Media\Photo\InstagramPhoto;
use InstagramAPI\Signatures;

class Broadcast {


    /**
     * InstagramAPI\Instagram $instagram
     */
    public $instagram;
    private $token;
    private $loggedInAccounts = [];
    private $broadcast;

    public function login($account)
    {
        $instagram = new Instagram();
        $instagram->login($account->last_name, $account->password);
        $this->token = Signatures::generateUUID();
        $this->instagram = $instagram;
    }


    public function followUser($broadcast)
    {
        $self = $this;
        $broadcast->lead()->where('followed_by', null)->chunk(4, function($leads) use ($self) {
            $accountToSend = Account::where('platform', 'instagram')->where('broadcast', 1)->inRandomOrder()->first();

            if (!isset($self->loggedInAccounts[$accountToSend->id])) {
                $acc = new Instagram();
                $acc->login($accountToSend->last_name, $accountToSend->password);
                $self->loggedInAccounts[$accountToSend->id] = $acc;
            }


            foreach ($leads as $lead) {
                echo "FOLLOWING $lead->platform_id \n";
                $self->loggedInAccounts[$accountToSend->id]->people->follow($lead->platform_id);
                $lead->followed_by = $accountToSend->id;
                $lead->save();
            }
        });
    }




    public function sendBulkMessages($leads, $message, $file = null, $account, $b) {
        $this->broadcast = $b;
        $receipts = [];
        foreach ($leads as $lead)
        {
            $receiverId = $lead->platform_id;
            if (strlen($receiverId) < 5) {
                try {
                    $receiverId = $this->instagram->people->getUserIdForName($lead->username);
                } catch (\Exception $exception) {
                    $lead->delete();
                    continue;
                }
                $lead->platform_id = $receiverId;
                $lead->save();
                sleep(2);

            }
            $receipts[$lead->id] = $lead->platform_id;
        }

        //Send message now...

        $this->broadcast->status = 0;
        $this->broadcast->save();




        foreach ($receipts as $key=>$receipt) {
            echo "Looping... \n";
            $cl = ColdLeads::where('platform_id', $receipt)->first();
            $account_id = $cl->followed_by;
            $cl->account_id = $account_id;
            $cl->save();
            $accountToSend = Account::find($account_id);
//            if ($account_id > 0) {
//                $accountToSend = Account::find($account_id);
//            } else {
//                $accountToSend = Account::where('platform', 'instagram')->where('broadcast', 1)->orderBy('broadcasted_messages', 'ASC')->first();
//            }

            echo "Attached account... \n";

            echo "SENDING TO $receipt \n";
            echo "TEXT by $accountToSend->id \n";

            try {
                $this->sendText($message, $receipt, $accountToSend, $account);
            } catch (\Exception $exception) {
            }

            echo "text sent... \n";


            $ct = InstagramThread::where('cold_lead_id', $cl->id)->first();
            if (!$ct) {
                $ct = new InstagramThread();
                $ct->cold_lead_id = $cl->id;
                $ct->account_id = $accountToSend->id;
                $ct->save();
            }


            $m = new InstagramDirectMessages();
            $m->instagram_thread_id = $ct->id;
            $m->message = $message;
            $m->sender_id = $this->loggedInAccounts[$accountToSend->id]->account_id ?? $this->instagram->account_id;
            $m->receiver_id = $receipt;
            $m->message_type = 1;
            $m->save();

            sleep(5);


            if ($file !== null) {
                echo "IMAGE by $accountToSend->id \n";

                try {
                    $this->sendImage($file, $receipt, $accountToSend, $account);
                } catch (\Exception  $exception) {

                }

                $m = new InstagramDirectMessages();
                $m->instagram_thread_id = $ct->id;
                $m->message = $file;
                $m->sender_id = $this->loggedInAccounts[$accountToSend->id]->account_id ?? $this->instagram->account_id;
                $m->message_type = 2;
                $m->receiver_id = $receipt;
                $m->save();

            }
            echo "=========================\n";

            ++$this->broadcast->messages_sent;
            $this->broadcast->save();

            ++$accountToSend->broadcasted_messages;
            $accountToSend->save();

            $l = ColdLeads::where('id', $key)->first();
            ++$l->messages_sent;
            $l->save();
            sleep(10);

            unset($receipts[$key]);
        }

        return count($receipts);
    }

    public function addColdLeadsToCustomersIfMessagIsReplied() {

        $currentInstagramAccountId = $this->instagram->account_id;
        $cursorId = '';

        do {

            $inbox = $this->instagram->direct->getInbox($cursorId)->asArray();


            if (!isset($inbox['inbox']['threads'])) {
                return;
            }

            $cursorId = 'END';

            if (isset($inbox['inbox']['oldest_cursor']) && $inbox['inbox']['oldest_cursor']) {
                $cursorId = $inbox['inbox']['oldest_cursor'];
            }

            $messages = $inbox['inbox']['threads'];

            foreach ($messages as $message) {
                $currentUserAccountId = $message['items'][0]['user_id'] ?? null;

                if ($currentInstagramAccountId !== $currentUserAccountId) {
                    $this->createCustomer($currentUserAccountId);
                    continue;
                }

                $thread = $this->instagram->direct->getThread($message['thread_id'])->asArray();
                $chats = $thread['thread']['items'];
                foreach ($chats as $chat) {
                    if ($chat['user_id'] !== $currentInstagramAccountId) {
                        $this->createCustomer($chat['user_id']);
                        continue;
                    }
                }

            }

        } while($cursorId!='END');
    }

    private function createCustomer($instagramId) {
        $thread = InstagramDirectMessages::where('sender_id', $instagramId)->orWhere('receiver_id', $instagramId)->first();

        if (!$thread) {
            //There was no previous message, so create one now..
            return;
        }

        $ct = InstagramThread::where('id', $thread->id)->first();
        if (!$ct) {
            return;
        }

        $coldLeadId = $ct->cold_lead_id;
        $lead = ColdLeads::where('id', $coldLeadId)->first();
        if (!$lead) {
            return;
        }

        $customer = new Customer();
        $customer->instahandler = $lead->username;
        $customer->ig_username = $lead->platform_id;
        $customer->name = $lead->name;
        $customer->save();

    }


    public function sendText($message, $receipt, $accountToSend, $account) {
        if ($account->id === $accountToSend->id) {
            $this->instagram->direct->sendText([
                'users' => [$receipt]
            ], $message);

            return;
        }

        if (!isset($this->loggedInAccounts[$accountToSend->id]))
        {
            $instagram = new Instagram();
            $instagram->login($accountToSend->last_name, $accountToSend->password);

            $this->loggedInAccounts[$accountToSend->id] = $instagram;

        }

        $this->loggedInAccounts[$accountToSend->id]->direct->sendText([
            'users' => [$receipt]
        ], $message);

    }

    public function sendImage($file, $receipt, $accountToSend, $account) {
        $fileName = public_path().'/uploads/'.$file;
        $fileToSend = new InstagramPhoto($fileName);
        if ($account->id === $accountToSend->id) {
            $this->instagram->direct->sendPhoto([
                'users' => [$receipt]
            ], $fileToSend->getFile());

            return;
        }

        if (!isset($this->loggedInAccounts[$accountToSend->id]))
        {
            $instagram = new Instagram();
            $instagram->login($accountToSend->last_name, $accountToSend->password);

            $this->loggedInAccounts[$accountToSend->id] = $instagram;

        }

        $this->loggedInAccounts[$accountToSend->id]->direct->sendPhoto([
            'users' => [$receipt]
        ], $fileToSend->getFile());
    }
}
