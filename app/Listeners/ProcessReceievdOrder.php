<?php

namespace App\Listeners;

use App\Events\ReceivedOrder;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessReceievdOrder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReceivedOrder  $event
     * @return void
     */
    public function handle(ReceivedOrder $event)
    {
        $receivedOrder = $event->received->payload;
        /** @var Order $order */
        $order = Order::firstOrCreate([
            'shopify_id' => $receivedOrder['id']
        ]);
        $order->fillFromShopify($receivedOrder);
        $order->save();
    }
}
