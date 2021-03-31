<?php


namespace App\ModelTraits;


use App\Models\Order;

trait ShopifyFill
{
    /**
     * @param $data
     * @return $this
     */
    public function fillFromShopify($data)
    {
        $fillableExceptions = $this->fillableExceptions ?? [];

        foreach ($data as $key => $value) {
            if (!in_array($key, $fillableExceptions, true)) {
                if ($key === 'id') {
                    $this->shopify_id = $value;
                } else {
                    $this->{$key} = $value;
                }
            }
        }
        return $this;
    }
}
