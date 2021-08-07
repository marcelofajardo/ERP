<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateErpLeadFromCancellationOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-erp-lead-from-cancellation-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create erp lead from cancellation order';

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
            $orders = \App\Order::where('order_status_id', \App\Helpers\OrderHelper::$cancel)->get();
            if ($orders) {
                foreach ($orders as $order) {
                    $orderProduct = $order->order_product()->first();
                    $product      = $orderProduct->products()->first();
                    $brand        = \App\Brand::where('id', $product->id)->first();
                    $erpLeads     = new \App\ErpLeads;
                    $erpLeads->fill([
                        'lead_status_id' => 4,
                        'customer_id'    => $order->customer_id,
                        'product_id'     => $product->id,
                        'brand_id'       => $product->brand,
                        'category_id'    => $product->category,
                        'color'          => $orderProduct->color,
                        'size'           => $orderProduct->size,
                        'min_price'      => $orderProduct->product_price,
                        'max_price'      => $orderProduct->product_price,
                        'brand_segment'  => $brand->brand_segment,
                    ]);
                    $erpLeads->save();

                    $media = $product->getMedia(config('constants.media_tags'))->first();
                    if ($media) {
                        $erpLeads->attachMedia($media, config('constants.media_tags'));
                    }
                    $this->info('order id = ' . $order->id . ' create id = ' . $erpLeads->id . "\n");
                }
            }
            echo 'Successfully update!!';
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
