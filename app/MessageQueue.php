<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class MessageQueue extends Model
{
   /**
     * @var string
       * @SWG\Property(property="user_id",type="integer")
     * @SWG\Property(property="customer_id",type="integer")
     * @SWG\Property(property="phone",type="string")
     * @SWG\Property(property="whatsapp_number",type="string")
   * @SWG\Property(property="type",type="string")
     * @SWG\Property(property="data",type="datetime")
     * @SWG\Property(property="sending_time",type="datetime")
     * @SWG\Property(property="group_id",type="integer")
      
     */
    protected $fillable = [
        'user_id', 'customer_id', 'phone', 'whatsapp_number', 'type', 'data', 'sending_time', 'group_id',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function chat_message()
    {
        return $this->belongsTo('App\ChatMessage');
    }

    /**
     * Get prouduct list and it's images
     *
     */

    public function getImagesWithProducts()
    {
        $return = [];

        if ($this->type == 'message_all') {
            
            $content = json_decode($this->data, true);

            if (array_key_exists('linked_images', $content)) {

                if (!empty($content['linked_images'])) {

                    foreach ($content['linked_images'] as $image) {

                        if (is_array($image)) {

                            $image_key     = $image['key'];
                            $mediable_type = "BroadcastImage";

                            $broadcast = \App\BroadcastImage::with('Media')
                                ->whereRaw("broadcast_images.id IN (SELECT mediables.mediable_id FROM mediables WHERE mediables.media_id = $image_key AND mediables.mediable_type LIKE '%$mediable_type%')")
                                ->first();

                            $return[] = [
                              "key"      => $image_key,
                              "image"    => @$image['url'],
                              "products" => ($broadcast) ? json_decode($broadcast->products, true) : []
                            ];

                        } else {

                            $broadcast_image = \App\BroadcastImage::find($image);
                            $product_ids     = ($broadcast_image) ? json_decode($broadcast_image->products, true) : [];

                            $return[] = [
                              "key"      => null,
                              "image"    => "",
                              "products" => $product_ids
                            ];

                        }
                    }
                }
            }
        }

        return $return;
    }

}
