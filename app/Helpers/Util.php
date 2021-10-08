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

    /**
     * Used to extract the postal code from the order notes array
     * @param $orderNotes
     * @return mixed|null
     */
    public static function getPostalCodeFromOrderNotesArray($orderNotes)
    {
        $postalCode = null;

        $filtered = Arr::first($orderNotes, function ($value, $key) {
            return $value['name'] === 'Pickup-Location-Postal-Code';
        });
        if ($filtered) {
            $postalCode = $filtered['value'];
        } else {
            $filtered = Arr::first($orderNotes, function ($value, $key) {
                return $value['name'] === 'Order Location Address';
            });
            if ($filtered) {
                $address    = $filtered['value'];
                $postalCode = Util::getPostcodeFromString($address);
            }
        }

        return $postalCode;
    }

    /**
     * Used to extract the Canadian postal code from a string
     * @param $string
     * @return string|null
     */
    public static function getPostcodeFromString($string)
    {
        $postcode      = null;
        $postcodeRegex = "/[A-Z][0-9][A-Z]\s?[0-9][A-Z][0-9]/i";
        if (preg_match($postcodeRegex, $string, $matches)) {
            $postcode = $matches[0];
        }
        return $postcode;
    }

    /**
     * Returns a has key of the object passed in.
     * @param $object
     * @return string
     */
    public static function getObjectHash($object)
    {
        return md5(json_encode($object));
    }
}
