<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Library\Instagram\PublishPost;

class InstaSchedulePost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $post )
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        
        $media = json_decode( $this->post->ig, true);
        $ig  = [
            'media'    => $media['media'],
            'location' => $media['location'],
        ];
        $this->post->ig = $ig;
        new PublishPost( $this->post );
    }
}
