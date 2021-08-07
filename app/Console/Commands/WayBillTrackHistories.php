<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\Library\DHL\TrackShipmentRequest;
use Illuminate\Http\Request;

class WayBillTrackHistories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:waybilltrack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $statuses = ['delivered','cancel'];
        $orders = Order::join('order_statuses','order_statuses.id','=','orders.order_status_id')->whereNotIn('order_statuses.status', $statuses )->with(['waybill','waybill.waybill_track_histories'])->get();
        //dd($orders);
        foreach($orders as $order){

            if($order->waybill != null && $order->waybill->awb){
                $trackShipment = new TrackShipmentRequest;
                $trackShipment->setAwbNumbers([$order->waybill->awb]);
                $results    = $trackShipment->call();
                $response   = $results->getResponse();
                dump($response);
                foreach($response as $res){ 
                    if(!empty($res->ShipmentInfo->ShipmentEvent->ArrayOfShipmentEventItem)) { 
                        $i = 1; 
                        foreach($res->ShipmentInfo->ShipmentEvent->ArrayOfShipmentEventItem as $shipmentEvent){
                            $shipment[$i]['waybill_id'] = $order->waybill->id;
                            $shipment[$i]['dat'] = $shipmentEvent->Date." ".$shipmentEvent->Time;
                            $shipment[$i]['comment'] = $shipmentEvent->ServiceEvent->Description; 
                            $shipment[$i]['location'] = $shipmentEvent->ServiceArea->Description; 
                            if($order->waybill->waybill_track_histories == null || $order->waybill->waybill_track_histories->count() == 0 || $order->waybill->waybill_track_histories->last() != $shipmentEvent->ServiceArea->Description){
                                if (!empty($message)) {
                                    $requestData = new Request();
                                    $requestData->setMethod('POST');
                                    $params = [];
                                    $params['customer_id'] = $order->customer_id;
                                    $params['message'] = "Your order with order ID ".$order->id." has been reached at ".$shipmentEvent->ServiceArea->Description." location.";
                                    $params['status'] = 2;
                                    $requestData->request->add($params);
                                    app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'priority');
                                }
                            }
                            $i++; 
                        } 
                    } 
                }  
            }
        }
    }
}
