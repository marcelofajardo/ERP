<?php

namespace App\Listeners;

use App\CashFlow;
use App\Events\OrderCreated;

class CreateOrderCashFlow
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        $user_id = auth()->id();
        if ($order->order_status_id == \App\Helpers\OrderHelper::$prepaid) {
            $order->cashFlows()->create([
                'date' => $order->order_date,
                'expected' => $order->balance_amount,
                'actual' => $order->balance_amount,
                'type' => 'received',
                'currency' => '',
                'status' => 1,
                'order_status' => 'prepaid',
                'user_id' => $user_id,
                'updated_by' => $user_id,
                'description' => 'Order Received with full pre payment',
            ]);
        } else if ($order->order_status_id == \App\Helpers\OrderHelper::$advanceRecieved) {
            $order->cashFlows()->create([
                'date' => $order->advance_date ?: $order->order_date,
                'expected' => $order->advance_detail,
                'actual' => $order->advance_detail,
                'type' => 'received',
                'currency' => '',
                'status' => 1,
                'order_status' => 'advance received',
                'user_id' => $user_id,
                'updated_by' => $user_id,
                'description' => 'Advance Received',
            ]);
            $order->cashFlows()->create([
                'date' => $order->date_of_delivery ?: ($order->estimated_delivery_date ?: $order->order_date),
                'expected' => $order->balance_amount,
                'actual' => 0,
                'type' => 'received',
                'currency' => '',
                'status' => 0,
                'order_status' => 'pending',
                'user_id' => $user_id,
                'updated_by' => $user_id,
                'description' => 'Pending balance amount',
            ]);
        } else {
            $order->cashFlows()->create([
                'date' => $order->date_of_delivery ?: ($order->estimated_delivery_date ?: $order->order_date),
                'expected' => $order->balance_amount,
                'actual' => 0,
                'type' => 'received',
                'currency' => '',
                'status' => 0,
                'order_status' => 'pending',
                'user_id' => $user_id,
                'updated_by' => $user_id,
                'description' => 'Pending balance amount',
            ]);
        }
    }
}
