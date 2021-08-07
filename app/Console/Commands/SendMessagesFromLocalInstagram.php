<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use InstagramAPI\Instagram;

class SendMessagesFromLocalInstagram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:process-users-message';

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
         $ch = curl_init();
            $username = Config('instagram.admin_account');

            $url = 'https://erp.theluxuryunlimited.com/api/instagram/get-comments-list/'.$username;

            // set url
            curl_setopt($ch, CURLOPT_URL, $url);

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // $output contains the output string
            $output = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch); 

            $messages = json_decode($output);

            if($messages->result == true){

                
                $instagram = new Instagram();

                $username = Config('instagram.admin_account');
                $password = Config('instagram.admin_password');

                if($username != '' && $password != ''){
                    
                    $instagram->login($username, $password);
                
                }else{
                    
                    return response()->json([
                    'status' => 'Not Able To Connect'
                    ]);
                
                }
                
                $comments = $messages->comments;

                foreach ($comments as $comment) {

                    $instagram->media->comment($comment->post_id, $comment->message);

                    //Send Request to ERP
                    $detail = [
                        'id' => $comment->id,
                    ];

                    $ch = curl_init('https://erp.theluxuryunlimited.com/api/instagram/comment-sent');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $detail);

                    // execute!
                    $response = curl_exec($ch);

                    // close the connection, release resources used
                    curl_close($ch);
                    
                }

            }else{
                dump('No Messsges Found');
            }
    }
}
