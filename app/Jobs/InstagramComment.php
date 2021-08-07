<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\InstagramCommentQueue;

class InstagramComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $_message;
    protected $_postId;
    protected $_account_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->_message = $data['message'];
        $this->_postId = $data['id'];
        $this->_account_id = $data['account_id'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $comment = new InstagramCommentQueue();
       $comment->message = $this->_message;
       $comment->post_id = $this->_postId;
       $comment->account_id = $this->_account_id;
       $comment->save();
    }
}
