<?php

namespace App\Console\Commands;

use App\Category;
use App\ChatMessage;
use App\CronJobReport;
use App\Customer;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoInterestMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:image-interest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a message to a customer with latest interested products';

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $params = [
                'number'  => null,
                'status'  => 7, // message status for auto messaging
                'user_id' => 6,
            ];

            $customers_leads = Customer::with(['Leads' => function ($query) {
                $query->whereNotNull('multi_brand')->orWhereNotNull('multi_category')->latest();
            }])->whereHas('Leads', function ($query) {
                $query->whereNotNull('multi_brand')->orWhereNotNull('multi_category')->latest();
            })->get()->toArray();

            $customers_orders = Customer::with(['Orders' => function ($query) {
                $query->with(['Order_Product' => function ($order_product_query) {
                    $order_product_query->with(['Product' => function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    }])->whereHas('Product', function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    });
                }])->whereHas('Order_Product', function ($order_product_query) {
                    $order_product_query->with(['Product' => function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    }])->whereHas('Product', function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    });
                });
            }])->whereHas('Orders', function ($query) {
                $query->with(['Order_Product' => function ($order_product_query) {
                    $order_product_query->with(['Product' => function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    }])->whereHas('Product', function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    });
                }])->whereHas('Order_Product', function ($order_product_query) {
                    $order_product_query->with(['Product' => function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    }])->whereHas('Product', function ($product_query) {
                        $product_query->whereNotNull('brand')->orWhere('category', '!=', 1)->latest();
                    });
                });
            })->get()->toArray();

            foreach ($customers_leads as $customer) {
                $brands = $customer['leads'][0]['multi_brand'] ? json_decode($customer['leads'][0]['multi_brand']) : [];

                if (count($brands) > 0) {
                    $products = Product::whereIn('brand', $brands)->where('category', $customer['leads'][0]['multi_category'])->latest()->take(20)->get();
                } else {
                    $products = Product::where('category', $customer['leads'][0]['multi_category'])->latest()->take(20)->get();
                }

                if (count($products) > 0) {
                    $params['customer_id'] = $customer['id'];

                    $chat_message = ChatMessage::create($params);

                    foreach ($products as $product) {
                        $chat_message->attachMedia($product->getMedia(config('constants.media_tags'))->first(), config('constants.media_tags'));
                    }
                }
            }

            foreach ($customers_orders as $customer) {
                $brand    = (int) $customer['orders'][0]['order__product'][0]['product']['brand'];
                $category = (int) $customer['orders'][0]['order__product'][0]['product']['category'];

                if ($category != 0 && $category != 1 && $category != 2 && $category != 3) {
                    $is_parent         = Category::isParent($category);
                    $category_children = [];

                    if ($is_parent) {
                        $children = Category::find($category)->childs()->get();

                        foreach ($children as $child) {
                            array_push($category_children, $child->id);
                        }
                    } else {
                        $children = Category::find($category)->parent->childs;

                        foreach ($children as $child) {
                            array_push($category_children, $child->id);
                        }

                        if (($key = array_search($category, $category_children)) !== false) {
                            unset($category_children[$key]);
                        }
                    }
                }

                if ($brand && $category != 1) {
                    $products = Product::where('brand', $brand)->whereIn('category', $category_children)->latest()->take(20)->get();
                } elseif ($brand) {
                    $products = Product::where('brand', $brand)->latest()->take(20)->get();
                } elseif ($category != 1) {
                    $products = Product::where('category', $category)->latest()->take(20)->get();
                }

                if (count($products) > 0) {
                    $params['customer_id'] = $customer['id'];

                    $chat_message = ChatMessage::create($params);

                    foreach ($products as $product) {
                        $chat_message->attachMedia($product->getMedia(config('constants.media_tags'))->first(), config('constants.media_tags'));
                    }
                }
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
