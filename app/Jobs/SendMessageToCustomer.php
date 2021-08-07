<?php

namespace App\Jobs;

use App\ChatMessage;
use App\Currency;
use App\Customer;
use App\Helpers\ProductHelper;
use App\Product;
use Dompdf\Dompdf;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Plank\Mediable\Media;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class SendMessageToCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $params;
    protected $chatbotReply;

    const SENDING_MEDIA_SIZE = 10;
    const MEDIA_PDF_CHUNKS   = 50;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$chatbotReply = null)
    {

        // Set product
        $this->type   = isset($data['type']) ? $data['type'] : "simple";
        $this->params = isset($data) ? $data : [];
        $this->chatbotReply = $chatbotReply;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {
        // Set time limit
        set_time_limit(0);
        $medias      = [];
        $mediaImages = [];
        $params      = $this->params;
        $this->type  = "by_product";
        $haveMedia = false;


        // query section

        if ($this->type == "by_product") {
            $mediaImages = ProductHelper::getImagesByProduct($params);
            if (!empty($mediaImages)) {
                $haveMedia = true;
                $medias = Media::whereIn("id", $mediaImages)->get();
            }
        }

        // if we need to send by images id  direct then use this one
        //if ($this->type == "by_images") {
        if (!empty($params["images"])) {
            $ids = is_array($params["images"]) ? $params["images"] : json_decode($params["images"]);
            $haveMedia = true;
            $medias = Media::whereIn("id", $ids)->get();
        }
        //}

        if (isset($params["images"]) && is_array($params["images"])) {
            $medias = Media::whereIn("id", $params["images"])->get();
        }
        // attach to the customer
        $customerIds = !empty($params["customer_ids"]) ? $params["customer_ids"] : explode(",", $params["customers_id"]);

        // @todo since this message all are auto so no need to update cutomer last message to read
        $customers = Customer::whereIn("id", $customerIds)->get();

        //get the currencies for the customer
        $currencies = $customers->map(
            function ($customer) {
                return $customer->currency;
            }
        )->filter(function ($currency) {
            return isset($currency);
        });

        $currencies = array_values($currencies->toArray());

        // Get the rates
        $ratesDb = Currency::whereIn('code', $currencies)->get();

        $rates = array();
        foreach ($ratesDb as $rate) {
            $rates[$rate->code] = $rate->rate;
        }
        //Base EURO currency
        $rates['EUR'] = 1;

        $insertParams = [
            "message"  => isset($params["message"]) ? $params["message"] : null,
            "status"   => isset($params["status"]) ? $params["status"] : \App\ChatMessage::CHAT_AUTO_BROADCAST,
            "is_queue" => isset($params["is_queue"]) ? $params["is_queue"] : 0,
            "group_id" => isset($params["group_id"]) ? $params["group_id"] : null,
            "user_id"  => isset($params["user_id"]) ? $params["user_id"] : null,
            "message_application_id"  => isset($params["message_application_id"]) ? $params["message_application_id"] : null,
            "number"   => null,
            "is_chatbot" => isset($params["is_chatbot"]) ? $params["is_chatbot"] : 0,
        ];

        $allMediaIds = ($haveMedia) ? $medias->pluck("id")->toArray() : [];
        $mediable    = \DB::table('mediables')->whereIn('media_id', $allMediaIds)->where('mediable_type', 'App\Product')->get();

        $availableMedia = [];
        $productIds     = [];
        if (!$mediable->isEmpty()) {
            foreach ($mediable as $media) {
                $availableMedia[$media->media_id] = $media;
                $productIds[]                     = $media->mediable_id;
            }
        }

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // check first if the media needs to be handled by pdf then first create the images of it
        $allpdf   = [];
        $allMedia = [];
        $translatedPdfs = [];
        if (!empty($medias)) {
            if ($medias->count() > self::SENDING_MEDIA_SIZE || (isset($params["send_pdf"]) && $params["send_pdf"] == 1)) {
                $chunkedMedia = $medias->chunk(self::MEDIA_PDF_CHUNKS);

                foreach ($chunkedMedia as $key => $medias) {

                    foreach ($rates as $currency => $rate) {

                        $products = $products->map(function ($product) use ($rates, $currency) {
                            if (isset($rates[$currency])) {
                                $product->price_inr_special *=  $rates[$currency];
                            }
                            return $product;
                        });
                        $currencySymbol = 'EUR';
                        if (isset($rates[$currency])){
                            $currencySymbol = $currency;
                        }

                        $pdfView = (string) view('pdf_views.images_customer', compact('medias', 'availableMedia', 'products', 'currencySymbol'));

                        // based on view create a pdf
                        $pdf = new Dompdf();
                        $pdf->setPaper([0, 0, 1000, 1000], 'portrait');
                        $pdf->loadHtml($pdfView);

                        if (!empty($params["pdf_file_name"])) {
                            $random = str_replace(" ", "-", $params["pdf_file_name"] . "-" . ($key + 1) . "-" . date("Y-m-d-H-i-s-") . rand());
                        } else {
                            $random = uniqid('sololuxury_', true);
                        }

                        $fileName = public_path() . '/' . $random . '.pdf';
                        $pdf->render();

                        File::put($fileName, $pdf->output());

                       
                        $allpdf[]            = $fileName;
                        $media               = MediaUploader::fromSource($fileName)->toDirectory('chatmessage/0')->upload();
                        $allMedia[$fileName] = $media;

                        $translatedPdfs[$fileName][$currency] = $media;

                    }
                }
            }
        }

        if (!$customers->isEmpty()) {
            foreach ($customers as $customer) {
                $customerPreferredCurrency = $customer->currency;
                $insertParams["customer_id"] = $customer->id;
                $chatMessage                 = ChatMessage::create($insertParams);
                if ($chatMessage->status == ChatMessage::CHAT_AUTO_WATSON_REPLY) {
                    if($this->chatbotReply) {
                        $chatbotReply = $this->chatbotReply;
                        $chatbotReply->chat_id = $chatMessage->id;
                        $chatbotReply->answer = $chatMessage->message;
                        $chatbotReply->reply = isset($params["chatbot_response"]) ? json_encode($params["chatbot_response"]) : null;
                        $chatbotReply->reply_from = 'watson';
                        $chatbotReply->save();
                    }
                }

                if (!empty($medias) && !$medias->isEmpty()) {

                    if ($medias->count() > self::SENDING_MEDIA_SIZE || (isset($params["send_pdf"]) && $params["send_pdf"] == 1)) {
                        // send pdf
                        if (!empty($allpdf)) {
                            foreach ($allpdf as $no => $file) {
                                // if first file then send direct into queue and if then send after it

                                $media = $allMedia[$file];

                                // translated PDF logic starts -->

                                if(isset($translatedPdfs) && isset($translatedPdfs[$fileName])){
                                    // the translated PDF exits and hence check for customer currency
                                    if(isset($translatedPdfs[$fileName][$customerPreferredCurrency])){
                                        // if customer preferred currency exist, use that
                                        $media = $translatedPdfs[$fileName][$customerPreferredCurrency];
                                    }else if(isset($translatedPdfs[$fileName]['EUR'])){
                                        //else if EURO exists
                                        $media = $translatedPdfs[$fileName]['EUR'];
                                    }
                                }

                                // <-- translated PDF logic ends

                                if ($no == 0) {
                                    $chatMessage->attachMedia($media, config('constants.media_tags'));
                                } else {
                                    // attach to customer so we can send later after approval
                                    $extradata             = $insertParams;
                                    $extradata['is_queue'] = 0;
                                    $extraChatMessage      = ChatMessage::create($extradata);
                                    $extraChatMessage->attachMedia($media, config('constants.media_tags'));
                                }
                            }
                        }
                    } else {
                        foreach ($medias as $media) {
                            try {
                                $chatMessage->attachMedia($media, config('constants.media_tags'));
                            } catch (\Exception $e) {
                                \Log::channel('customer')->error($e);
                            }
                        }
                    }
                }

                // chat message for approval
                if ($chatMessage->status == ChatMessage::CHAT_MESSAGE_APPROVED) {
                    $myRequest = new Request();
                    $myRequest->setMethod('POST');
                    $myRequest->request->add(['messageId' => $chatMessage->id]);
                    app(\App\Http\Controllers\WhatsAppController::class)->approveMessage('customer', $myRequest);
                }
            }
        }

        self::deletePdfFiles($allpdf);
    }

    /**
     * delete all pdf files after we send to the customer
     *
     */

    public static function deletePdfFiles($files = [])
    {
        if (!empty($files)) {
            foreach ($files as $key => $file) {
                File::delete($file);
            }
        }
    }
}
