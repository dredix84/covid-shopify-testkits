<?php


namespace App\Helpers;


use App\Models\Order;
use App\Models\PickupLocation;
use Illuminate\Support\Arr;

class Util
{

    /**
     * Get data from the environment and converts it to an array
     * @param  string  $key
     * @param  mixed  $default
     * @param  string  $separator
     * @return false|mixed|string[]|null
     */
    public static function envArray($key, $default = null, $separator = ',')
    {
        $envData = env($key, $default);
        $outData = $default;
        if (is_string($envData) && !blank($envData)) {
            $outData = explode($separator, $envData);
        }

        return $outData;
    }

    public static function isAllowedHeader()
    {
        return collect(config('shopify.allow_headers'))->flatten()->toArray();
    }

    public static function getPickupLocationFromOrder(Order $order)
    {
        dd($order->note_attributes);
        $filtered = Arr::where($order->note_attributes, function ($value, $key) {
            return in_array($value['name'], ['Pickup-Location-Company', 'Order Location']);
        });

        $pickLocation = PickupLocation::PICKUP_OTHER;
        if (count($filtered)) {
            $location     = Arr::first($filtered);
            $pickLocation = $location['value'];
        }

        return $pickLocation;
    }
}
