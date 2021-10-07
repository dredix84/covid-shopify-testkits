<?php

use App\Helpers\Util;
use App\Models\Order;

return [
    'api'                 => [
        'base_url' => env('SHOPIFY_API_BASE_URL'),
        'token'    => env('SHOPIFY_API_TOKEN')
    ],
    'headers'             => [
        'topic' => 'x-shopify-topic'
    ],
    'allow_headers'       => [
        'receiving' => Util::envArray('ALLOWED_HEADERS_RECEIVING', ['*']),
        'orders'    => Util::envArray(
            'ALLOWED_HEADERS_ORDERS',
            [Order::SHOPIFY_HEADER_TOPIC_CREATE, Order::SHOPIFY_HEADER_TOPIC_UPDATED]
        )
    ],
    'min_reorder_percent' => env('MIN_REORDER_PERCENT', 70)
];
