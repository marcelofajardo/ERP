<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChatMessage;
use App\Customer;
use App\PublicKey;

class EncryptMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrpyt:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use For Encrypting Message';

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
        $customerChats = ChatMessage::whereNotNull('customer_id')->whereNotNull('message')->get();
        foreach ($customerChats as $customerChat) {
            $public = PublicKey::first();
            if($public != null){
                $public = hex2bin($public->key);
                $message = sodium_crypto_box_seal($customerChat->message, $public);
                $customerChat->message = bin2hex($message);
                $customerChat->update();
            }
        }
        dump('All Message Encrypted');
    }
}
