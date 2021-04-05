<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = Customer::all();
        $faker     = \Faker\Factory::create();

        $locations = [
            [
                'city'         => 'Toronto',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'Kitchener',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'Waterloo',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'Cambridge',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'Brampton',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'Guelph',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'London',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'Windsor',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
            [
                'city'         => 'Ottawa',
                'state'        => 'Ontario',
                'state_code'   => 'ON',
                'country'      => 'Canada',
                'country_code' => 'CA'
            ],
        ];

        $items = [
            [
                "id"                           => 866550311766439020,
                "variant_id"                   => 808950810,
                "title"                        => "IPod Nano - 8GB",
                "quantity"                     => 1,
                "sku"                          => "IPOD2008PINK",
                "variant_title"                => null,
                "vendor"                       => null,
                "fulfillment_service"          => "manual",
                "product_id"                   => 632910392,
                "requires_shipping"            => true,
                "taxable"                      => true,
                "gift_card"                    => false,
                "name"                         => "IPod Nano - 8GB",
                "variant_inventory_management" => "shopify",
                "properties"                   => [],
                "product_exists"               => true,
                "fulfillable_quantity"         => 1,
                "grams"                        => 567,
                "price"                        => "199.00",
                "total_discount"               => "0.00",
                "fulfillment_status"           => null,
                "price_set"                    => [
                    "shop_money"        => [
                        "amount"        => "199.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "199.00",
                        "currency_code" => "USD"
                    ]
                ],
                "total_discount_set"           => [
                    "shop_money"        => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ]
                ],
                "discount_allocations"         => [],
                "duties"                       => [],
                "admin_graphql_api_id"         => "gid=>//shopify/LineItem/866550311766439020",
                "tax_lines"                    => []
            ],
            [
                "id"                           => 1234657876978,
                "variant_id"                   => 87649876498,
                "title"                        => "Covid Test Kit 1",
                "quantity"                     => 1,
                "sku"                          => "covkit1",
                "variant_title"                => null,
                "vendor"                       => null,
                "fulfillment_service"          => "manual",
                "product_id"                   => 1234566,
                "requires_shipping"            => true,
                "taxable"                      => true,
                "gift_card"                    => false,
                "name"                         => "Covid Test Kit 1",
                "variant_inventory_management" => "shopify",
                "properties"                   => [],
                "product_exists"               => true,
                "fulfillable_quantity"         => 1,
                "grams"                        => 567,
                "price"                        => "25.00",
                "total_discount"               => "0.00",
                "fulfillment_status"           => null,
                "price_set"                    => [
                    "shop_money"        => [
                        "amount"        => "25.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "25.00",
                        "currency_code" => "USD"
                    ]
                ],
                "total_discount_set"           => [
                    "shop_money"        => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ]
                ],
                "discount_allocations"         => [],
                "duties"                       => [],
                "admin_graphql_api_id"         => "gid=>//shopify/LineItem/866550311766439020",
                "tax_lines"                    => []
            ],
            [
                "id"                           => 1234657873452,
                "variant_id"                   => 78349876,
                "title"                        => "Covid Test Kit 2",
                "quantity"                     => 1,
                "sku"                          => "covkit2",
                "variant_title"                => null,
                "vendor"                       => null,
                "fulfillment_service"          => "manual",
                "product_id"                   => 97647547,
                "requires_shipping"            => true,
                "taxable"                      => true,
                "gift_card"                    => false,
                "name"                         => "Covid Test Kit 2",
                "variant_inventory_management" => "shopify",
                "properties"                   => [],
                "product_exists"               => true,
                "fulfillable_quantity"         => 1,
                "grams"                        => 567,
                "price"                        => "50.00",
                "total_discount"               => "0.00",
                "fulfillment_status"           => null,
                "price_set"                    => [
                    "shop_money"        => [
                        "amount"        => "50.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "50.00",
                        "currency_code" => "USD"
                    ]
                ],
                "total_discount_set"           => [
                    "shop_money"        => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ]
                ],
                "discount_allocations"         => [],
                "duties"                       => [],
                "admin_graphql_api_id"         => "gid=>//shopify/LineItem/866550311766439020",
                "tax_lines"                    => []
            ],
            [
                "id"                           => 98487547854,
                "variant_id"                   => 6537653,
                "title"                        => "Covid Test Kit Family Pack",
                "quantity"                     => 1,
                "sku"                          => "covkitfam",
                "variant_title"                => null,
                "vendor"                       => null,
                "fulfillment_service"          => "manual",
                "product_id"                   => 387383,
                "requires_shipping"            => true,
                "taxable"                      => true,
                "gift_card"                    => false,
                "name"                         => "Covid Test Kit Family Pack",
                "variant_inventory_management" => "shopify",
                "properties"                   => [],
                "product_exists"               => true,
                "fulfillable_quantity"         => 1,
                "grams"                        => 567,
                "price"                        => "75.00",
                "total_discount"               => "0.00",
                "fulfillment_status"           => null,
                "price_set"                    => [
                    "shop_money"        => [
                        "amount"        => "75.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "75.00",
                        "currency_code" => "USD"
                    ]
                ],
                "total_discount_set"           => [
                    "shop_money"        => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ]
                ],
                "discount_allocations"         => [],
                "duties"                       => [],
                "admin_graphql_api_id"         => "gid=>//shopify/LineItem/866550311766439020",
                "tax_lines"                    => []
            ],
        ];

        $fulfillmentStatuses = [
            null, 'fulfilled', 'unfulfilled', 'pending', 'scheduled', 'partially_fulfilled', 'on_hold'
        ];
        $financialStatus     = [
            'voided', 'authorized', 'paid', 'pending', 'refunded', 'unpaid', 'partially_refunded', 'partially_paid'
        ];

        foreach ($customers as $customer) {
            $location  = $faker->randomElement($locations);
            $lineItems = [];
            $total     = 0;
            for ($x = 0; $x <= $faker->randomElement([1, 2, 1, 2, 2, 2, 1, 2, 2]); $x++) {
                $item = $faker->randomElement($items);
                if (count($lineItems) === 0 || (count($lineItems) === 1 && $lineItems[0] !== $item)) {
                    $lineItems[] = $item;
                    $total       += (float) $item['price'];
                }
            }

            $orderData = [
                "admin_graphql_api_id"       => "gid=>//shopify/Order/820982911946154508",
                "app_id"                     => null,
                'created_at'                 => $faker->dateTimeBetween('-2 months', 'now'),
                "billing_address"            => [
                    "first_name"    => $customer->first_name,
                    "address1"      => $faker->streetAddress,
                    "phone"         => $faker->phoneNumber,
                    "city"          => $location['state_code'],
                    "zip"           => $faker->postcode,
                    "province"      => $location['city'],
                    "country"       => $location['country'],
                    "last_name"     => $customer->last_name,
                    "address2"      => null,
                    "company"       => $faker->company,
                    "latitude"      => null,
                    "longitude"     => null,
                    "name"          => $customer->full_name,
                    "country_code"  => $location['country_code'],
                    "province_code" => $location['state_code']
                ],
                "browser_ip"                 => null,
                "buyer_accepts_marketing"    => true,
                "cancel_reason"              => "customer",
                "cancelled_at"               => "2021-02-05T20=>47=>59-05=>00",
                "cart_token"                 => null,
                "checkout_id"                => null,
                "checkout_token"             => null,
                "closed_at"                  => null,
                "confirmed"                  => false,
                "contact_email"              => $customer->email,
                "currency"                   => "CAD",
                "current_total_duties_set"   => null,
                "customer_locale"            => "en",
                "device_id"                  => null,
                "discount_applications"      => [
                    [
                        "type"              => "manual",
                        "value"             => "5.0",
                        "value_type"        => "fixed_amount",
                        "allocation_method" => "across",
                        "target_selection"  => "explicit",
                        "target_type"       => "line_item",
                        "description"       => "Discount",
                        "title"             => "Discount"
                    ]
                ],
                "discount_codes"             => [],
                "email"                      => $customer->email,
                "financial_status"           => $faker->randomElement($financialStatus),
                "fulfillment_status"         => $faker->randomElement($fulfillmentStatuses),
                "fulfillments"               => [],
                "gateway"                    => null,
                "landing_site"               => null,
                "landing_site_ref"           => null,
                "line_items"                 => $lineItems,
                /*[
                [
                    "id"                           => 866550311766439020,
                    "variant_id"                   => 808950810,
                    "title"                        => "IPod Nano - 8GB",
                    "quantity"                     => 1,
                    "sku"                          => "IPOD2008PINK",
                    "variant_title"                => null,
                    "vendor"                       => null,
                    "fulfillment_service"          => "manual",
                    "product_id"                   => 632910392,
                    "requires_shipping"            => true,
                    "taxable"                      => true,
                    "gift_card"                    => false,
                    "name"                         => "IPod Nano - 8GB",
                    "variant_inventory_management" => "shopify",
                    "properties"                   => [],
                    "product_exists"               => true,
                    "fulfillable_quantity"         => 1,
                    "grams"                        => 567,
                    "price"                        => "199.00",
                    "total_discount"               => "0.00",
                    "fulfillment_status"           => null,
                    "price_set"                    => [
                        "shop_money"        => [
                            "amount"        => "199.00",
                            "currency_code" => "USD"
                        ],
                        "presentment_money" => [
                            "amount"        => "199.00",
                            "currency_code" => "USD"
                        ]
                    ],
                    "total_discount_set"           => [
                        "shop_money"        => [
                            "amount"        => "0.00",
                            "currency_code" => "USD"
                        ],
                        "presentment_money" => [
                            "amount"        => "0.00",
                            "currency_code" => "USD"
                        ]
                    ],
                    "discount_allocations"         => [],
                    "duties"                       => [],
                    "admin_graphql_api_id"         => "gid=>//shopify/LineItem/866550311766439020",
                    "tax_lines"                    => []
                ],
                [
                    "id"                           => 141249953214522974,
                    "variant_id"                   => 808950810,
                    "title"                        => "IPod Nano - 8GB",
                    "quantity"                     => 1,
                    "sku"                          => "IPOD2008PINK",
                    "variant_title"                => null,
                    "vendor"                       => null,
                    "fulfillment_service"          => "manual",
                    "product_id"                   => 632910392,
                    "requires_shipping"            => true,
                    "taxable"                      => true,
                    "gift_card"                    => false,
                    "name"                         => "IPod Nano - 8GB",
                    "variant_inventory_management" => "shopify",
                    "properties"                   => [],
                    "product_exists"               => true,
                    "fulfillable_quantity"         => 1,
                    "grams"                        => 567,
                    "price"                        => "199.00",
                    "total_discount"               => "5.00",
                    "fulfillment_status"           => null,
                    "price_set"                    => [
                        "shop_money"        => [
                            "amount"        => "199.00",
                            "currency_code" => "USD"
                        ],
                        "presentment_money" => [
                            "amount"        => "199.00",
                            "currency_code" => "USD"
                        ]
                    ],
                    "total_discount_set"           => [
                        "shop_money"        => [
                            "amount"        => "5.00",
                            "currency_code" => "USD"
                        ],
                        "presentment_money" => [
                            "amount"        => "5.00",
                            "currency_code" => "USD"
                        ]
                    ],
                    "discount_allocations"         => [
                        [
                            "amount"                     => "5.00",
                            "discount_application_index" => 0,
                            "amount_set"                 => [
                                "shop_money"        => [
                                    "amount"        => "5.00",
                                    "currency_code" => "USD"
                                ],
                                "presentment_money" => [
                                    "amount"        => "5.00",
                                    "currency_code" => "USD"
                                ]
                            ]
                        ]
                    ],
                    "duties"                       => [],
                    "admin_graphql_api_id"         => "gid=>//shopify/LineItem/141249953214522974",
                    "tax_lines"                    => []
                ]
            ],*/
                "location_id"                => null,
                "name"                       => "#9999",
                "note"                       => null,
                "note_attributes"            => [],
                "number"                     => 234,
                "order_number"               => 1234,
                "order_status_url"           => "https=>//apple.myshopify.com/690933842/orders/123456abcd/authenticate?key=abcdefg",
                "original_total_duties_set"  => null,
                "payment_gateway_names"      => [
                    "visa",
                    "bogus"
                ],
                "phone"                      => null,
                "presentment_currency"       => "USD",
                "processed_at"               => null,
                "processing_method"          => null,
                "reference"                  => null,
                "referring_site"             => null,
                "refunds"                    => [],
                "shipping_address"           => [
                    "first_name"    => "Steve",
                    "address1"      => "123 Shipping Street",
                    "phone"         => "555-555-SHIP",
                    "city"          => "Shippington",
                    "zip"           => "40003",
                    "province"      => "Kentucky",
                    "country"       => "United States",
                    "last_name"     => "Shipper",
                    "address2"      => null,
                    "company"       => "Shipping Company",
                    "latitude"      => null,
                    "longitude"     => null,
                    "name"          => "Steve Shipper",
                    "country_code"  => "US",
                    "province_code" => "KY"
                ],
                "shipping_lines"             => [
                    [
                        "id"                               => 271878346596884015,
                        "title"                            => "Generic Shipping",
                        "price"                            => "10.00",
                        "code"                             => null,
                        "source"                           => "shopify",
                        "phone"                            => null,
                        "requested_fulfillment_service_id" => null,
                        "delivery_category"                => null,
                        "carrier_identifier"               => null,
                        "discounted_price"                 => "10.00",
                        "price_set"                        => [
                            "shop_money"        => [
                                "amount"        => "10.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount"        => "10.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "discounted_price_set"             => [
                            "shop_money"        => [
                                "amount"        => "10.00",
                                "currency_code" => "USD"
                            ],
                            "presentment_money" => [
                                "amount"        => "10.00",
                                "currency_code" => "USD"
                            ]
                        ],
                        "discount_allocations"             => [],
                        "tax_lines"                        => []
                    ]
                ],
                "shopify_id"                 => random_int(10000000, 99999999),
                "source_identifier"          => null,
                "source_name"                => "web",
                "source_url"                 => null,
                "subtotal_price"             => "393.00",
                "subtotal_price_set"         => [
                    "shop_money"        => [
                        "amount"        => "393.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "393.00",
                        "currency_code" => "USD"
                    ]
                ],
                "tags"                       => null,
                "tax_lines"                  => [],
                "taxes_included"             => false,
                "test"                       => true,
                "token"                      => "123456abcd",
                "total_discounts"            => "5.00",
                "total_discounts_set"        => [
                    "shop_money"        => [
                        "amount"        => "5.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "5.00",
                        "currency_code" => "USD"
                    ]
                ],
                "total_line_items_price"     => $total,
                "total_line_items_price_set" => [
                    "shop_money"        => [
                        "amount"        => $total,
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => $total,
                        "currency_code" => "USD"
                    ]
                ],
                "total_price"                => $total + 5,
                "total_price_set"            => [
                    "shop_money"        => [
                        "amount"        => $total + 5,
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => $total + 5,
                        "currency_code" => "USD"
                    ]
                ],
                "total_price_usd"            => null,
                "total_shipping_price_set"   => [
                    "shop_money"        => [
                        "amount"        => "10.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "10.00",
                        "currency_code" => "USD"
                    ]
                ],
                "total_tax"                  => "0.00",
                "total_tax_set"              => [
                    "shop_money"        => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ],
                    "presentment_money" => [
                        "amount"        => "0.00",
                        "currency_code" => "USD"
                    ]
                ],
                "total_tip_received"         => "0.0",
                "total_weight"               => 0,
                "user_id"                    => null
            ];

            $order = new Order($orderData);
            $order->save();
        }
    }
}


