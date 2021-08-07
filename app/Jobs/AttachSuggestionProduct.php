<?php

namespace App\Jobs;

use App\Suggestion;
use App\SuggestedProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttachSuggestionProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $suggestion;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SuggestedProduct $suggestion)
    {

        $this->suggestion = $suggestion;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $suggestion = $this->suggestion;

        if (!empty($suggestion)) {
            // check with customer
            SuggestedProduct::attachMoreProducts($suggestion);
        }
    }

}
