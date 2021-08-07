<?php

namespace App\Services\Products;

use Illuminate\Http\Request;

class SendImagesOfProduct
{
    public $log = [];

    public function check($chatMessage)
    {   
        $temp_log_params = [
            "model"     => \App\Customer::class,
            "model_id"  => $chatMessage->customer_id,
            "message_sent_id"=> $chatMessage->id,
        ];

        $addKeyword = \App\KeywordAutoGenratedMessageLog::create($temp_log_params);
        
        $this->log[] = "Started to check the auto send message via brand and category";

        if (!empty($chatMessage->message)) {
            $sentence = preg_replace('/\s+/', ' ', $chatMessage->message);
            $sentence = explode(" ", $sentence);

            $brand    = $this->checkWithBrand($sentence);
            $category = $this->checkWithCategory($sentence);

            if($brand && $category) {

                // find the values from setting to get the how many images we need to send in total;    
                try{
                    $setting = \App\Setting::where("name","send_auto_brand_category_image_no")->first();
                    $totalImages = 10;
                    if($setting) {
                        $totalImages = $setting->val;
                    }

                    $myRequest = new Request();
                    $myRequest->setMethod('POST');
                    $myRequest->request->add(['brand' => [$brand->id]]);
                    $myRequest->request->add(['category' => [$category->id]]);
                    $myRequest->request->add(['submit_type' => 'send-to-approval']);
                    $myRequest->request->add(['limit' => $totalImages]);
                    $myRequest->request->add(['need_to_send_message' => 1]);
                    $myRequest->request->add(['keyword_matched' => $chatMessage->message]);

                    $this->log[] = "Started function to call the attach image function with : ".json_encode($myRequest->all());
                    $return = (new \App\Http\Controllers\ProductController)->attachImages('customer',$chatMessage->customer_id,null,null,$myRequest);
                    if(!empty($return)) {
                        $this->log[] = "Total product found for message : ".$return['total_product'];
                    }
                }catch(\Exception $e) {
                    $this->log[] = "Exception found erro thrown : ".$e->getMessage(). " ".$e->getTraceAsString();
                }
            }else{
                $this->log[] = "No brand and category matched for the message"; 
            }
        }else{
            $this->log[] = "Message type is not message or empty message";
        }

        $addKeyword->comment = implode("\n\r",$this->log);
        $addKeyword->save();

        //$addKeyword
    }

    public function checkWithBrand($sentence)
    {
        foreach ((array) $sentence as $s) {
            $brand = \App\Brand::where("name", "like", $s)->orderBy("id","asc")->first();
            if ($brand) {
                $this->log[] = "Brand name matched with '".$s."' and id is '".$brand->id."'";
                return $brand;
            }
        }

        return null;
    }

    public function checkWithCategory($sentence)
    {
        foreach ((array) $sentence as $s) {
            $category = \App\Category::where("title", "like", $s)->first();
            if ($category) {
                $this->log[] = "Category name matched with '".$s."' and id is '".$category->id."'";
                return $category;
            }
        }
    }

}
