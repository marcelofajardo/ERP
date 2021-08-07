<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\ChatMessage;

class Suggestion extends Model
{
        /**
     * @var string
      * @SWG\Property(property="customer_id",type="integer")
      * @SWG\Property(property="chat_message_id",type="integer")
      * @SWG\Property(property="category",type="string")
      * @SWG\Property(property="size",type="string")
      * @SWG\Property(property="supplier",type="string")
     * @SWG\Property(property="number",type="string")
     */
    protected $fillable = [
        'customer_id', 'brand', 'category', 'size', 'supplier', 'number', 'chat_message_id',
    ];

    public function products()
    {
        return $this->hasMany('App\Product', 'suggestion_products', 'suggestion_id', 'product_id');
    }

    public function suggestionProducts()
    {
      return $this->hasMany('App\SuggestionProduct','suggestion_id','id');
    }

    public function customer()
    {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function chatMessage()
    {
        return $this->hasOne('App\ChatMessage', 'id', 'chat_message_id');
    }

    public static function attachMoreProducts($suggestion)
    {
        $data = [];

        if (!empty($suggestion)) {
            // check with customer
            $customer = $suggestion->customer;

            if ($customer) {

                $excludedProductIDs = [];
                if (!$suggestion->suggestionProducts->isEmpty()) {
                    $excludedProductIDs = $suggestion->suggestionProducts->pluck("product_id")->toArray();
                }

                $brands     = json_decode($suggestion->brand);
                $categories = json_decode($suggestion->category);
                $sizes      = json_decode($suggestion->size);
                $suppliers  = json_decode($suggestion->supplier);

                $needToBeRun = false;
                $products    = new Product;

                // check with brands
                if (!empty($brands) && is_array($brands)) {
                    $needToBeRun = true;
                    $products    = $products->whereIn('products.brand', $brands);
                }

                // check with categories
                if (!empty($categories) && is_array($categories)) {
                    $needToBeRun = true;
                    $category   = \App\Category::whereIn("parent_id",$categories)->get()->pluck("id")->toArray();
                    $categories = array_merge($categories,$category);
                    $products   = $products->whereIn('products.category', $categories);
                }

                // check with sizes
                if (!empty($sizes) && is_array($sizes)) {
                    $needToBeRun = true;
                    $products    = $products->where(function ($query) use ($sizes) {
                        foreach ($sizes as $size) {
                            $query->orWhere('products.size', 'LIKE', "%$size%");
                        }
                        return $query;
                    });
                }

                // check with suppliers
                if (!empty($suppliers) && is_array($suppliers)) {
                    $needToBeRun = true;
                    $products = $products->join("product_suppliers as ps","ps.sku","products.sku");
                    $products = $products->whereIn("ps.supplier_id",$suppliers);
                    $products = $products->groupBy("products.id");
                    /*$products    = $products->whereHas('suppliers', function ($query) use ($suppliers) {
                        return $query->where(function ($q) use ($suppliers) {
                            foreach ($suppliers as $supplier) {
                                $q->orWhere('suppliers.id', $supplier);
                            }
                        });
                    });*/
                }

                // now check the params and start getting result
                if ($needToBeRun) {
                    $products = $products->where('category', '!=', 1)->whereNotIn("products.id", $excludedProductIDs)->select(["products.*"])->latest()->take($suggestion->number)->get();
                    if (!$products->isEmpty()) {
                        $params = [
                            'number'      => null,
                            'user_id'     => 6,
                            'approved'    => 0,
                            'status'      => ChatMessage::CHAT_SUGGESTED_IMAGES,
                            'message'     => 'Suggested images',
                            'customer_id' => $customer->id,
                        ];

                        $count = 0;

                        foreach ($products as $product) {
                            if (!$product->suggestions->contains($suggestion->id)) {
                                if ($image = $product->getMedia(config('constants.attach_image_tag'))->first()) {
                                    if ($count == 0) {
                                        if (!$suggestion->chatMessage) {
                                            $chat_message = ChatMessage::create($params);
                                        } else {
                                            $chat_message = $suggestion->chatMessage;
                                        }
                                        $suggestion->chat_message_id = $chat_message->id;
                                        $suggestion->save();
                                    }

                                    $chat_message->attachMedia($image->getKey(), config('constants.media_tags'));
                                    $data[] = [
                                        "id"          => $image->id,
                                        "mediable_id" => $chat_message->id,
                                        "url"         => $image->getUrl(),
                                    ];

                                    $count++;
                                }

                                $product->suggestions()->attach($suggestion->id);
                            }
                        }
                    }
                } else {
                    $suggestion->products()->detach();
                    $suggestion->delete();
                }

            } else {
                $suggestion->products()->detach();
                $suggestion->delete();
            }
        }

        return $data;
    }

}
