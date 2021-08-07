<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tickets;
use Illuminate\Http\Request;
use Mail;
class PriceDropNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pricedrop:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to compair if any drop in product price and notify customer';

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
        $ticketsData = Tickets::select('pr.price as prod_real_price','pr.sku as prod_sku','pr.name as prod_name','tickets.*')->join('products as pr','pr.sku','=','tickets.sku')->get();
        
        foreach($ticketsData as $ticket){
            if($ticket->prod_real_price <= $ticket->amount){
                if($ticket->notify_on=='phone' && $ticket->phone_no!=null){
                    $message = "Your are recieving this message as a notification for your inquiry Ticket No ".$ticket->ticket_id." regarding price drop for product ".$ticket->prod_name;
                    $requestData = new Request();
                    $requestData->setMethod('POST');
                    $requestData->request->add(['ticket_id' => $ticket->id,'message' => $message, 'status' => 1]);
                    app('App\Http\Controllers\WhatsAppController')->sendMessage($requestData, 'ticket');
                }elseif($ticket->notify_on=='email' && $ticket->email!=null){
                    Mail::send('emails.pricedropnotif', ['ticket' => $ticket], function ($m) use ($ticket) {
                        $m->from('contact@sololuxury.co.in', 'LuxuryErp');
                        $m->to($ticket->email)->subject('Price Drop Notification');
                    });
                }
            }
        }
    }
}
