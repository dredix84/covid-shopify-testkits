<?php

namespace App\Listeners;

use App\Events\ReceivedOrder;
use App\Helpers\ExceptionHelper;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessReceivedOrder
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
        $headers       = $event->received->headers;
        $headerTopic   = $headers[config('shopify.headers.topic')];
        $doSave        = true;

        if (Order::isAllowedHeader($headerTopic)) {
            /** @var Order $order */
            $order = Order::firstOrNew([
                'shopify_id' => $receivedOrder['id']
            ]);

//            dd($headerTopic, !blank($order->id), $headerTopic === Order::SHOPIFY_HEADER_TOPIC_CREATE,
//                !blank($order->id) && $headerTopic === Order::SHOPIFY_HEADER_TOPIC_CREATE
//            );
            if (!blank($order->id) && $headerTopic === Order::SHOPIFY_HEADER_TOPIC_CREATE) {
                $doSave = false;
            }

            if ($doSave) {
                $order->created_at = $receivedOrder['created_at'];
                $order->fillFromShopify($receivedOrder);

                try {
                    $order->pickup_location = $order->getShopifyPickLocation();
                } catch (\Exception $e) {
                    ExceptionHelper::logError($e, "Error while attempting to calculate the pickup location");
                }

                $order->save();
                Log::debug('Order created/updated from received record', ['received_id' => $event->received->id]);
            } else {
                Log::debug('Order not updated from "create" received topic', ['received_id' => $event->received->id]);
            }
        } else {
            Log::debug(
                'Order NOT created from received record',
                [
                    'received_id'                   => $event->received->id,
                    config('shopify.headers.topic') => $headers[config('shopify.headers.topic')]
                ]
            );
        }
    }
}
