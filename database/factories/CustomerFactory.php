<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

//use Faker\Generator as Faker;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        $faker      = \Faker\Factory::create();
        $customerId = random_int(1000000000, 9999999999);
        return [
            "accepts_marketing"            => false,
            "accepts_marketing_updated_at" => null,
            "admin_graphql_api_id"         => "gid=>//shopify/Customer/".$customerId,
            "currency"                     => "USD",
            "default_address"              => [
                "id"            => random_int(100000000, 999999999),
                "customer_id"   => random_int(100000000, 999999999),
                "first_name"    => null,
                "last_name"     => null,
                "company"       => null,
                "address1"      => "123 Elm St.",
                "address2"      => null,
                "city"          => "Ottawa",
                "province"      => "Ontario",
                "country"       => "Canada",
                "zip"           => "K2H7A8",
                "phone"         => "123-123-1234",
                "name"          => null,
                "province_code" => "ON",
                "country_code"  => "CA",
                "country_name"  => "Canada",
                "default"       => true
            ],
            "email"                        => $faker->email,
            "first_name"                   => $faker->firstName,
            "last_name"                    => $faker->lastName,
            "last_order_id"                => null,
            "last_order_name"              => null,
            "marketing_opt_in_level"       => null,
            "multipass_identifier"         => null,
            "note"                         => $faker->words(5),
            "orders_count"                 => 0,
            "phone"                        => null,
            "shopify_id"                   => $customerId,
            "state"                        => "disabled",
            "tags"                         => null,
            "tax_exempt"                   => false,
            "total_spent"                  => "0.00",
            "verified_email"               => true
        ];
    }
}
