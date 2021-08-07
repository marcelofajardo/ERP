<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\SuggestedProductList;
use App\SuggestedProduct;
use App\Helpers\CompareImagesHelper;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchAttachedImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $req_data; 
    protected $url; 
    protected $first_time; 
    protected $is_matched; 
    protected $suggested_product; 

    public function __construct($id, $url, $req_data)
    {
        $this->id = $id; 
        $this->url = $url; 
        $this->req_data = $req_data; 
        $this->first_time = true; 
        $this->is_matched = false; 
        $this->suggested_product = false; 
    }

    public function handle()
    {
        Log::error('SearchAttachedImages() : id => ' . $this->id . ' url => ' . $this->url . ' request => ' . json_encode($this->req_data));
        set_time_limit(0);

        $id = $this->id;
        $ref_file = str_replace('|', '/', $this->url);
        $ref_file = str_replace("'", '', $ref_file);
        $params = $this->req_data;
        $customer_id = false;
        $chat_message = false;
        if(isset($params['customer_id'])){
            $customer_id = $params['customer_id'];
        }else{
            $chat_message = \App\ChatMessage::where('id', $id)->first();
        }
        Log::error(' ref_file => ' . $ref_file . ' chat_message => ' . json_encode($chat_message));
        Log::error(' ref_file => ' . $ref_file . ' chat_message => ' . json_encode($chat_message));
        if(@file_get_contents($ref_file)){
            $i1 = CompareImagesHelper::createImage($ref_file);
                
            $i1 = CompareImagesHelper::resizeImage($i1,$ref_file);
            
            imagefilter($i1, IMG_FILTER_GRAYSCALE);
            
            $colorMean1 = CompareImagesHelper::colorMeanValue($i1);
            
            $bits1 = CompareImagesHelper::bits($colorMean1);

            $bits = implode($bits1); 
            Log::error('bits => ' . $bits);
            DB::table('media')->whereNotNull('bits')->where('bits', '!=', 0)->where('bits', '!=', 1)->where('directory', 'like', '%product/%')->orderBy('id')->chunk(1000, function($medias)
             use ($bits, $chat_message, $customer_id)
            {
            foreach ($medias as $k => $m)
                {
                    $hammeringDistance = 0;
                    $m_bits = $m->bits; 
                    for($a = 0;$a<64;$a++)
                    {
                        if($bits[$a] != $m_bits[$a])
                        {
                            $hammeringDistance++;
                        }
                        
                    } 
                    Log::error(' bits => ' . $bits . ' m_bits => ' . $m_bits  . ' hammeringDistance => ' . $hammeringDistance . ' media => ' . $m->id );
                    if($hammeringDistance < 10){
                        $this->is_matched = true;
                        Log::error('matched_media => ' . json_encode($m)); 
                        if($this->first_time){
                            $this->suggested_product = SuggestedProduct::create([
                                'total' => 0,
                                'customer_id' => $chat_message ? $chat_message->customer_id : $customer_id,
                                'chat_message_id' => $chat_message ? $chat_message->id : null,
                            ]);
                            Log::error('$this->suggested_product => ' . json_encode($this->suggested_product)); 
                            $this->first_time = false;
                        } 
                        $mediable = DB::table('mediables')->where('media_id', $m->id)->where('mediable_type', 'App\Product')->first();
                        if($mediable){
                            Log::error('mediable => ' . json_encode($mediable)); 
                            SuggestedProductList::create([
                                'customer_id' => $chat_message ? $chat_message->customer_id : $customer_id,
                                'product_id' => $mediable->mediable_id,
                                'chat_message_id' => $chat_message ? $chat_message->id : null,
                                'suggested_products_id' => $this->suggested_product !== null ? $this->suggested_product->id : null
                            ]); 
                        }
                    }
                }
            });
        }

        $user = Auth::user();
        if($this->is_matched){
            $msg = 'Your image find process is completed.';
        }else{
            $msg = 'Your image find process is completed, No results found';
        } 
        app('App\Http\Controllers\WhatsAppController')->sendWithThirdApi($user->phone, $user->whatsapp_number, $msg);

    }

    public function tags()
    {
        return ['search_images'];
    }

}
