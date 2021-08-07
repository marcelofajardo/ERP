<?php

namespace App\Console\Commands\Manual;

use App\Helpers\OrderHelper;
use App\Order;
use Illuminate\Console\Command;

class MoveToNewStatusFieldOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order-status:move-to-new-field';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move all order status to new order status id field';

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
        $order_status_ids = self::getStatusID();
        $orders           = Order::where("order_status", "!=", "")->whereNull("order_status_id")->get();
        if (!$orders->isEmpty()) {
            foreach ($orders as $order) {
                $selStatus = strtolower($order->order_status);
                $statusId  = isset($order_status_ids[$selStatus]) ? $order_status_ids[$selStatus] : 0;
                if ($statusId > 0) {
                    $order->order_status_id = $statusId;
                    $order->save();
                }
            }
        }
    }

    public static function getStatusID()
    {
        $status = OrderHelper::getStatus();
        return array_flip(array_map("strtolower", $status));
    }
}
