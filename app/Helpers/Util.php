<?php


namespace App\Helpers;


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
}
