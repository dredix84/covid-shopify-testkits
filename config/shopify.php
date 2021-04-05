<?php

use App\Helpers\Util;
use App\Models\Order;

return [
    'headers'       => [
        'topic' => 'x-shopify-topic'
    ],
    'allow_headers' => [
        'receiving' => Util::envArray('ALLOWED_HEADERS_RECEIVING', ['*']),
        'orders'    => Util::envArray(
            'ALLOWED_HEADERS_ORDERS',
            [Order::SHOPIFY_HEADER_TOPIC_CREATE, Order::SHOPIFY_HEADER_TOPIC_UPDATED]
        )
    ]
];
