<?php

namespace App\Listeners;

use App\Events\ReceivedOrder;
use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        $receivedCustomer = $event->received->payload['customer'];

        /** @var Customer $customer */
        $customer = Customer::firstOrCreate([
            'email' => $receivedCustomer['email']
        ]);
        $customer->fillFromShopify($receivedCustomer);
        $customer->save();
    }
}
