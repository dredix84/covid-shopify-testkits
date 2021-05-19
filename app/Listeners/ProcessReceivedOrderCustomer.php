<?php

namespace App\Listeners;

use App\Events\ReceivedOrder;
use App\Helpers\ExceptionHelper;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessReceivedOrderCustomer
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
        try {
            if (Order::isAllowedHeader($event->received->topic)) {
                $receivedCustomer = $event->received->payload['customer'];

                /** @var Customer $customer */
                $customer = Customer::firstOrCreate([
                    'email' => $receivedCustomer['email']
                ]);
                $customer->fillFromShopify($receivedCustomer);

                if ($event->received->topic === Order::SHOPIFY_HEADER_TOPIC_CREATE) {
                    /** @var Order $order */
                    $order = new Order();
                    $order->fillFromShopify($event->received->payload);
                    $customer->last_order = $order->toArray();
                }

                $customer->save();
            }
        } catch (\Exception $e) {
            ExceptionHelper::logError(
                $e,
                "ProcessReceivedOrderCustomer: There was an error while attempting to process the data",
                [
                    'payload' => $event->received->payload
                ]
            );
        }
    }
}
